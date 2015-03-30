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



class Mirasvit_HelpDesk_Helper_Process extends Varien_Object
{
    public function getConfig()
    {
        return Mage::getSingleton('helpdesk/config');
    }

    /**
     * creates ticket from frontend
     * @param  array $post
     * @param  Varien_Object or Customer model $customer
     * @return Ticket
     */
    public function createFromPost($post, $channel)
    {
        $ticket = Mage::getModel('helpdesk/ticket');
        // ÐµÑÐ»Ð¸ ÐºÐ°ÑÑÐ¾Ð¼ÐµÑ Ð½Ðµ Ð±ÑÐ» Ð°Ð²ÑÐ¾ÑÐ¸Ð·Ð¸ÑÐ¾Ð²Ð°Ð½, ÑÐ¾ Ð¸ÑÐµÐ¼ ÐµÐ³Ð¾
        $customer = Mage::helper('helpdesk/customer')->getCustomerByPost($post);

        $ticket->setCustomerId($customer->getId())
            ->setCustomerEmail($customer->getEmail())
            ->setCustomerName($customer->getName())
            ->setQuoteAddressId($customer->getQuoteAddressId())
            ->setCode(Mage::helper('helpdesk/string')->generateTicketCode())
            ->setName($post['name'])
            ->setDescription($this->getEnviromentDescription());

        if (isset($post['priority_id'])) {
            $ticket->setPriorityId((int)$post['priority_id']);
        }
        if (isset($post['department_id'])) {
            $ticket->setDepartmentId((int)$post['department_id']);
        } else {
            $ticket->setDepartmentId($this->getConfig()->getContactFormDefaultDepartment());
        }
        if (isset($post['order_id'])) {
            $ticket->setOrderId((int)$post['order_id']);
        }
        $ticket->setStoreId(Mage::app()->getStore()->getStoreId());
        $ticket->setChannel($channel);
        if ($channel == Mirasvit_Helpdesk_Model_Config::CHANNEL_FEEDBACK_TAB) {
            $url = Mage::getSingleton('customer/session')->getFeedbackUrl();
            $ticket->setChannelData(array('url' => $url));
        }

        Mage::helper('helpdesk/field')->processPost($post, $ticket);
        $ticket->save();
        $body = Mage::helper('helpdesk/string')->parseBody($post['message'], Mirasvit_Helpdesk_Model_Config::FORMAT_PLAIN);
        $ticket->addMessage($body, $customer, false, Mirasvit_Helpdesk_Model_Config::CUSTOMER);
        Mage::helper('helpdesk/history')->changeTicket($ticket, Mirasvit_Helpdesk_Model_Config::CUSTOMER, array('customer' => $customer));

        return $ticket;
    }

    public function getEnviromentDescription()
    {
        return print_r($_SERVER, true);
    }

    public function createOrUpdateFromBackendPost($data, $user)
    {
        $ticket = Mage::getModel('helpdesk/ticket');
        if (isset($data['ticket_id']) && (int)$data['ticket_id'] > 0) {
            $ticket->load((int)$data['ticket_id']);
        }
        if (!Zend_Validate::is($data['customer_email'] , 'EmailAddress')) {
             throw new Mage_Core_Exception("Invalid Customer Email");
        }
        if (!isset($data['customer_id']) || !$data['customer_id']) {
            if (!$ticket->getCustomerName()) {
                $data['customer_name'] = $data['customer_email'];
            }
        }
        if (isset($data['customer_id']) && strpos($data['customer_id'], 'address_') !== false) {
            $data['quote_address_id'] = (int)str_replace('address_', '', $data['customer_id']);
            $data['customer_id'] = null;
        } else {
            $data['quote_address_id'] = null;
        }

        $ticket->addData($data);

        Mage::helper('helpdesk/tag')->setTags($ticket, $data['tags']);
        //set custom fields
        Mage::helper('helpdesk/field')->processPost($data, $ticket);
        //set ticket user and department
        if (isset($data['owner'])) {
            $ticket->initOwner($data['owner']);
        }
        if (isset($data['fp_owner'])) {
            $ticket->initOwner($data['fp_owner'], 'fp');
        }
        if ($data['fp_period_unit'] == 'custom') {
            $format = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
            Mage::helper('mstcore/date')->formatDateForSave($ticket, 'fp_execute_at', $format);
        } elseif($data['fp_period_value'])  {
            $ticket->setData('fp_execute_at', $this->createFpDate($data['fp_period_unit'], $data['fp_period_value']));
        }
        if (!$ticket->getId()) {
            $ticket->setChannel(Mirasvit_Helpdesk_Model_Config::CHANNEL_BACKEND);
        }

        $ticket->save();
        if (trim($data['reply']) || $_FILES['attachment']['name'][0] != '') {
            $message = $ticket->addMessage($data['reply'], false, $user, Mirasvit_Helpdesk_Model_Config::USER, $data['reply_type']);
        }
        Mage::helper('helpdesk/history')->changeTicket($ticket, Mirasvit_Helpdesk_Model_Config::USER, array('user' => $user));

        return $ticket;
    }

    public function createFpDate($unit, $value)
    {
        switch ($unit) {
            case 'minutes':
                $timeshift = $value;
                break;
            case 'hours':
                $timeshift = $value * 60;
                break;
            case 'days':
                $timeshift = $value * 60 * 24;
                break;
            case 'weeks':
                $timeshift = $value * 60 * 24 * 7;
                break;
            case 'weeks':
                $timeshift = $value * 60 * 24 * 31;
                break;
        }
        $timeshift *= 60; //in seconds
        $time = strtotime(Mage::getSingleton('core/date')->gmtDate()) + $timeshift;
        $time = date('Y-m-d H:i:s', $time);

        return $time;
    }

    public function isDev()
    {
        return Mage::getSingleton('helpdesk/config')->getDeveloperIsActive();
    }

    public function processEmail($email, $code)
    {
        $ticket      = false;
        $customer    = false;
        $user        = false;
        $triggeredBy = Mirasvit_Helpdesk_Model_Config::CUSTOMER;
        $messageType = Mirasvit_Helpdesk_Model_Config::MESSAGE_PUBLIC;

        if($code) {
            //try to find customer for this email
            $tickets = Mage::getModel('helpdesk/ticket')->getCollection()
                        ->addFieldToFilter('code', $code)
                        ->addFieldToFilter('customer_email', $email->getFromEmail())
                        ;
            if ($tickets->count()) {
                $ticket = $tickets->getFirstItem();
            } else {
                //try to find staff user for this email
                $users = Mage::getModel('admin/user')->getCollection()
                    ->addFieldToFilter('email', $email->getFromEmail())
                    ;

                if ($users->count()) {
                    $user = $users->getFirstItem();
                    $tickets = Mage::getModel('helpdesk/ticket')->getCollection()
                                ->addFieldToFilter('code', $code)
                                ;
                    if ($tickets->count()) {
                        $ticket = $tickets->getFirstItem();
                        $ticket->setUserId($user->getId());
                        $ticket->save();
                        $triggeredBy = Mirasvit_Helpdesk_Model_Config::USER;
                    } else {
                      $user = false; //@temp dva for testing
                    }
                } else { //third party
                    $tickets = Mage::getModel('helpdesk/ticket')->getCollection()
                                ->addFieldToFilter('code', $code)
                                ;
                    if ($tickets->count()) {
                        $ticket = $tickets->getFirstItem();
                        $triggeredBy = Mirasvit_Helpdesk_Model_Config::THIRD;
                        if ($ticket->isThirdPartyPublic()) {
                            $messageType = Mirasvit_Helpdesk_Model_Config::MESSAGE_PUBLIC_THIRD;
                        } else {
                            $messageType = Mirasvit_Helpdesk_Model_Config::MESSAGE_INTERNAL_THIRD;
                        }
                    }
                }
            }
        }

        if (!$user) {
            $customer = Mage::helper('helpdesk/customer')->getCustomerByEmail($email);
        }
        // create a new ticket
        if (!$ticket) {
            $ticket = Mage::getModel('helpdesk/ticket');
            if (!$code) {
              $ticket->setCode(Mage::helper('helpdesk/string')->generateTicketCode());
            } else {
              $ticket->setCode($code);//temporary for testing to fix @dva
            }
            $gateway = Mage::getModel('helpdesk/gateway')->load($email->getGatewayId());
            if ($gateway->getId()) {
                $ticket->setDepartmentId($gateway->getDepartmentId());
                $ticket->setStoreId($gateway->getStoreId());
            }

            $ticket
                ->setName($email->getSubject())
                ->setCustomerName($customer->getName())
                ->setCustomerId($customer->getId())
                ->setQuoteAddressId($customer->getQuoteAddressId())
                ->setCustomerEmail($email->getFromEmail())
                ->setChannel(Mirasvit_Helpdesk_Model_Config::CHANNEL_EMAIL)
                ;
            $ticket->setEmailId($email->getId());
            $ticket->save();
            if ($pattern = $this->checkForSpamPattern($email)) {
                $ticket->markAsSpam($pattern);
                if ($email) {
                    $email->setPatternId($pattern->getId())->save();
                }
            }
        }

        //add message to ticket
        $text = $email->getBody();
        $encodingHelper = Mage::helper('helpdesk/encoding');
        $text = $encodingHelper->toUTF8($text);
        $body = Mage::helper('helpdesk/string')->parseBody($text, $email->getFormat());
        $message = $ticket->addMessage($body, $customer, $user, $triggeredBy, $messageType, $email);

        Mage::dispatchEvent('helpdesk_process_email', array('body'=>$body, 'customer' => $customer, 'user' => $user, 'ticket' => $ticket));

        Mage::helper('helpdesk/history')->changeTicket($ticket, $triggeredBy, array('user' => $user, 'customer' => $customer));

        return $ticket;
   }

    public function checkForSpamPattern($email) {
        $patterns = Mage::getModel('helpdesk/pattern')->getCollection()
            ->addFieldToFilter('is_active', true);
        foreach ($patterns as $pattern) {
            if ($pattern->checkEmail($email)) {
                return $pattern;
            }
        }

        return false;
    }
}