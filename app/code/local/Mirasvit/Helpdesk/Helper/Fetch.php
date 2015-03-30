<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at http://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   Help Desk MX
 * @version   1.0.7
 * @build     907
 * @copyright Copyright (C) 2014 Mirasvit (http://mirasvit.com/)
 */



class Mirasvit_Helpdesk_Helper_Fetch extends Varien_Object
{
    public function isDev()
    {
        return Mage::getSingleton('helpdesk/config')->getDeveloperIsActive();
    }

    public function connect($gateway)
    {
        $this->gateway = $gateway;
        $flags = sprintf('/%s', $gateway->getProtocol());
        if($gateway->getEncryption() == 'ssl') {
            $flags .= '/ssl';
        }
        $flags .= '/novalidate-cert';

        // echo $flags;die;
        $server = new Mirasvit_Ddeboer_Imap_Server($gateway->getHost(), $gateway->getPort(), $flags);
        if(function_exists('imap_timeout')) {
            imap_timeout(1,20);
        }
        if (!$this->connection = $server->authenticate($gateway->getLogin(), $gateway->getPassword())) {
            return false;
        }

        $mailboxes = $this->connection->getMailboxNames();
        if (in_array('INBOX',  $mailboxes)) {
            $mailboxName = 'INBOX';
        } elseif (in_array('Inbox',  $mailboxes)) {
            $mailboxName = 'Inbox';
        } else {
            $mailboxName = $mailboxes[0];
        }

        $this->mailbox = $this->connection->getMailbox($mailboxName);

        return true;
    }

    public function close()
    {
        $this->connection->close();
    }

    public function getFromEmail($message)
    {
        // ÐµÑÐ»Ð¸ ÐµÑÑÑ reply to, ÑÐ¾ Ð¼Ñ ÐµÐ³Ð¾ ÑÑÑÐ°Ð½Ð°Ð²Ð»Ð¸Ð²Ð°ÐµÐ¼ ÐºÐ°Ðº from, Ñ.Ðº. Ð½Ð° Ð½ÐµÐ³Ð¾ Ð¼Ñ Ð±ÑÐ´ÐµÐ¼ Ð¾ÑÐ²ÐµÑÐ°ÑÑ
        if ($message->getReplyTo() && $message->getReplyTo()->getAddress()) {
            $fromEmail = $message->getReplyTo()->getAddress();
        } else {
            $fromEmail = $message->getFrom()->getAddress();
        }

        return $fromEmail;
    }

    public function createEmail($message)
    {
        $uid = Mage::helper('mstcore/debug')->start();
        try {
            $emails = Mage::getModel('helpdesk/email')->getCollection()
                ->addFieldToFilter('message_id', $message->getId())
                ->addFieldToFilter('from_email', $this->getFromEmail($message));

            if($emails->count()) {
                return false;
            }
            $bodyHtml = $message->getBodyHtml();
            $bodyPlain = $message->getBodyText();
            if (!empty($bodyHtml)) {
                $format = Mirasvit_Helpdesk_Model_Config::FORMAT_HTML;
                $body = $bodyHtml;
            } else {
                $body = $bodyPlain;
                $format = Mirasvit_Helpdesk_Model_Config::FORMAT_PLAIN;
                $tags = array('<div', '<br', '<tr');
                foreach ($tags as $tag) {
                    if (stripos($body, $tag) !== false) {
                        $format = Mirasvit_Helpdesk_Model_Config::FORMAT_HTML;
                        break;
                    }
                }
            }
            $to = array();
            foreach($message->getTo() as $email) {
                $to[] = $email->getAddress();
            }

            $fromEmail = $this->getFromEmail($message);
            $email = Mage::getModel('helpdesk/email')
                ->setMessageId($message->getId())
                ->setFromEmail($fromEmail)
                ->setSenderName($message->getFrom()->getName())
                ->setToEmail(implode($to, ','))
                ->setSubject($message->getSubject())
                ->setBody($body)
                ->setFormat($format)
                ->setHeaders($message->getHeaders()->toString())
                ->setIsProcessed(false);
                if ($this->gateway) { //may be null during tests
                    $email->setGatewayId($this->gateway->getId());
                }
            $email->save();
            // if ($this->isDev()) {
            //     echo "Email '{$email->getSubject()}' was fetched\n";
            // }
            //Save attachments if any.
            $attachments = $message->getAttachments();

            if($attachments) {
                foreach($attachments as $a) {
                    $attachment = Mage::getModel('helpdesk/attachment');
                    $attachment->setName($a->getFilename())
                        ->setType($a->getType())
                        ->setSize($a->getSize())
                        ->setBody($a->getDecodedContent())
                        ->setEmailId($email->getId())
                        ->save();
                    // if ($this->isDev()) {
                    //     echo "Attached file '{$attachment->getName()}' was saved\n";
                    //     Mage::log("Attached file '{$attachment->getName()}'", null, 'helpdesk.log');
                    // }
                }
            }
            Mage::helper('mstcore/debug')->end($uid, array('email_subject' => $email->getSubject()));

            return $email;
        } catch (Exception $e) {
            Mage::helper('mstcore/debug')->end($uid, array('error' => $e->getMessage()));
            echo $e->getMessage()."\n";
            Mage::log($e);

            return false;
        }
    }

    protected function fetchEmails()
    {
        $uid = Mage::helper('mstcore/debug')->start();
        $msgs = $errors = 0;
        $max = $this->gateway->getFetchMax();

        $messages = $this->mailbox->getMessages('UNSEEN');
        $emailsNumber = $this->mailbox->count();
        //echo "Total Emails Number: $emailsNumber \n";

        if ($limit = $this->gateway->getFetchLimit()) {
            $start = $emailsNumber - $limit + 1;
            if ($start < 1) {
                $start = 1;
            }
            for ($num = $start; $num <= $emailsNumber; $num++) {
                $message = $this->mailbox->getMessage($num);
                if($this->createEmail($message)) {
                    if ($this->gateway->getIsDeleteEmails()) {
                        $message->delete();
                        $this->mailbox->expunge();
                    }
                    $msgs++;
                }
                if($max && $msgs >= $max) {
                    break;
                }
            }
        } else {
            foreach ($messages as $message) {
                if($this->createEmail($message)) {
                    if ($this->gateway->getIsDeleteEmails()) {
                        $message->delete();
                        $this->mailbox->expunge();
                    }
                    $msgs++;
                }
                if($max && $msgs >= $max) {
                    break;
                }
            }
        }

        //echo "Fetch is finished \n";
        Mage::helper('mstcore/debug')->end($uid, array('gateway' => $this->gateway->getName(), 'emailsNumber' => $emailsNumber));
    }

    public function fetch($gateway)
    {
        if(!$this->connect($gateway)) {
            return false;
        }
        $this->fetchEmails();
        $this->close();

        return true;
    }
}
