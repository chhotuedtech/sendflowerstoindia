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


class Mirasvit_Helpdesk_Helper_RuleEvent extends Mage_Core_Helper_Abstract
{
    protected $_sentEmails      = array();
    protected $_processedEvents = array();

    public function getConfig()
    {
        return Mage::getSingleton('helpdesk/config');
    }

    public function newEventCheck($eventType)
    {
        $this->_sentEmails = false; //Ð½Ð° Ð¾Ð´Ð¸Ð½ ÐµÐ¼ÐµÐ¹Ð» Ð¼Ñ Ð¼Ð¾Ð¶ÐµÐ¼ Ð¾ÑÐ¿ÑÐ°Ð²Ð¸ÑÑ Ð½ÐµÑÐºÐ¾Ð»ÑÐºÐ¾ Ð¿Ð¸ÑÐµÐ¼
        $rules = Mage::getModel('helpdesk/rule')->getCollection()
            ->addFieldToFilter('is_active', true)
            ->addFieldToFilter('event', $eventType)
            ->setOrder('sort_order')
            ;
        $tickets = Mage::getModel('helpdesk/ticket')->getCollection()
            ->addFieldToFilter('is_archived', false)
            ->addFieldToFilter('is_spam', false)
            ;

        foreach ($tickets as $ticket) {
            foreach ($rules as $rule) {
                $rule->afterLoad();
                if (!$rule->validate($ticket)) {
                    continue;
                }
                $this->processRule($rule, $ticket);
                if ($rule->getIsStopProcessing()) {
                    break;
                }
            }
        }
    }

    public function newEvent($eventType, $ticket)
    {
        $uid = Mage::helper('mstcore/debug')->start();
        $key = $eventType.$ticket->getId();
        if (isset($this->_processedEvents[$key])) {
            return;
        } else {
            $this->_processedEvents[$key] = true;
        }

        $this->_sentEmails = array();
        $collection = Mage::getModel('helpdesk/rule')->getCollection()
            ->addFieldToFilter('is_active', true)
            ->addFieldToFilter('event', $eventType)
            ->setOrder('sort_order')
            ;
        foreach ($collection as $rule) {
            $rule->afterLoad();
            // var_dump($rule->validate($ticket));die;
            if (!$rule->validate($ticket)) {
                continue;
            }
            $this->processRule($rule, $ticket);
            if ($rule->getIsStopProcessing()) {
                break;
            }
        }
        Mage::helper('mstcore/debug')->end($uid, array('$eventType' => $eventType, '$ticket' => $ticket->getData(), '$ticketOrigData' => $ticket->getOrigData()));

    }

    protected function processRule($rule, $ticket)
    {
        $uid = Mage::helper('mstcore/debug')->start();
        /** set attributes **/
        if ($rule->getStatusId()) {
            $ticket->setStatusId($rule->getStatusId());
        }
        if ($rule->getPriorityId()) {
            $ticket->setPriorityId($rule->getPriorityId());
        }
        if ($rule->getDepartmentId()) {
            $ticket->setDepartmentId($rule->getDepartmentId());
        }
        if ($rule->getUserId()) {
            $ticket->setUserId($rule->getUserId());
        }

        if ($rule->getIsArchive() == Mirasvit_Helpdesk_Model_Config::IS_ARCHIVE_TO_ARCHIVE) {
            $ticket->setIsArchived(true);
        } elseif ($rule->getIsArchive() == Mirasvit_Helpdesk_Model_Config::IS_ARCHIVE_FROM_ARCHIVE) {
            $ticket->setIsArchived(false);
        }

        if ($tags = $rule->getAddTags()) {
            Mage::helper('helpdesk/tag')->addTags($ticket, $tags);
        }
        if ($tags = $rule->getRemoveTags()) {
            Mage::helper('helpdesk/tag')->removeTags($ticket, $tags);
        }
        $ticket->save();

        /** send notifications **/
        $isSendDepartment = false;
        if ($rule->getIsSendOwner()) {
            if ($user = $ticket->getUser()) {
                $this->_sendEventNotification($user->getEmail(), $user->getName(), $rule, $ticket);
            } else {
                $isSendDepartment = true;
            }
        }
        if ($rule->getIsSendDepartment() || $isSendDepartment) {
            foreach ($ticket->getDepartment()->getUsers() as $user) {
                $this->_sendEventNotification($user->getEmail(), $user->getName(), $rule, $ticket);
            }
        }
        if ($rule->getIsSendUser()) { //small bug here. better to name it getIsSendCustomer
            if ($customer = $ticket->getCustomer()) {
                $this->_sendEventNotification($customer->getEmail(), $customer->getName(), $rule, $ticket);
            }
        }
        if ($otherEmail = $rule->getOtherEmail()) {
            $this->_sendEventNotification($otherEmail, '', $rule, $ticket);
        }
        Mage::helper('helpdesk/history')->changeTicket($ticket, Mirasvit_Helpdesk_Model_Config::RULE, array('rule' => $rule));
        Mage::helper('mstcore/debug')->end($uid, array('$rule' => $rule->getData(), '$ticket' => $ticket->getData(), '$ticketOrigData' => $ticket->getOrigData()));
    }

    protected function _sendEventNotification($email, $name, $rule, $ticket)
    {
        $uid = Mage::helper('mstcore/debug')->start();
        if (!is_array($this->_sentEmails) || !in_array($email, $this->_sentEmails)) {
            $variables = array(
                'email_subject' => $rule->getEmailSubject(),
                'email_body'    => $rule->getEmailBody(),
            );
            $template = Mage::getSingleton('helpdesk/config')->getNotificationRuleTemplate($ticket->getStoreId());

            $attachments = array();
            if ($rule->getIsSendAttachment()) {
                $attachments = $ticket->getLastMessage()->getAttachments();
            }

            Mage::helper('helpdesk/notification')->mail($ticket, false, false, $email, $name, $template, $attachments, $variables);
            $this->_sentEmails[] = $email;
        }
        Mage::helper('mstcore/debug')->end($uid, array('email' => $email, 'ticket' => $ticket->getData(), 'template' => $template));
    }
}