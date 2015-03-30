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


class Mirasvit_Helpdesk_Model_Ticket extends Mage_Core_Model_Abstract
{

    protected function _construct()
    {
        $this->_init('helpdesk/ticket');
    }

    public function toOptionArray($emptyOption = false)
    {
    	return $this->getCollection()->toOptionArray($emptyOption);
    }

    protected $_department = null;
    public function getDepartment()
    {
        if (!$this->getDepartmentId()) {
            return false;
        }
    	if ($this->_department === null) {
            $this->_department = Mage::getModel('helpdesk/department')->load($this->getDepartmentId());
    	}
    	return $this->_department;
    }

    protected $_priority = null;
    public function getPriority()
    {
        if (!$this->getPriorityId()) {
            return false;
        }
    	if ($this->_priority === null) {
            $this->_priority = Mage::getModel('helpdesk/priority')->load($this->getPriorityId());
    	}
    	return $this->_priority;
    }

    protected $_status = null;
    public function getStatus()
    {
        if (!$this->getStatusId()) {
            return false;
        }
    	if ($this->_status === null) {
            $this->_status = Mage::getModel('helpdesk/status')->load($this->getStatusId());
    	}
    	return $this->_status;
    }

    protected $_user = null;
    public function getUser()
    {
        if (!$this->getUserId()) {
            return false;
        }
    	if ($this->_user === null) {
            $this->_user = Mage::getModel('admin/user')->load($this->getUserId());
    	}
    	return $this->_user;
    }

    protected $_store = null;
    public function getStore()
    {
        if (!$this->getStoreId()) {
            return false;
        }
        if ($this->_store === null) {
            $this->_store = Mage::getModel('core/store')->load($this->getStoreId());
        }
        return $this->_store;
    }

	/************************/

    public function addMessage($text, $customer, $user, $triggeredBy, $messageType = Mirasvit_Helpdesk_Model_Config::MESSAGE_PUBLIC, $email = false)
    {
        $message = Mage::getModel('helpdesk/message')
            ->setTicketId($this->getId())
            ->setType($messageType)
            ->setBody($text)
            ->setTriggeredBy($triggeredBy)
            ;
        if ($triggeredBy == Mirasvit_Helpdesk_Model_Config::CUSTOMER) {
            $message->setCustomerId($customer->getId());
            $message->setCustomerName($customer->getName());
            $message->setCustomerEmail($customer->getEmail());
            $message->setIsRead(true);
            $this->setLastReplyName($customer->getName());
        } elseif ($triggeredBy == Mirasvit_Helpdesk_Model_Config::USER) {
            $message->setUserId($user->getId());
            if ($this->getOrigData('user_id') == $this->getData('user_id')) {
    			$this->setUserId($user->getId());
            }
            $this->setLastReplyName($user->getName());
            if ($message->isThirdParty()) {
                $message->setThirdPartyEmail($this->getThirdPartyEmail());
            }
        } elseif ($triggeredBy == Mirasvit_Helpdesk_Model_Config::THIRD) {
            $message->setThirdPartyEmail($this->getThirdPartyEmail());
            if ($email) {
                $this->setLastReplyName($email->getSenderNameOrEmail());
                $message->setThirdPartyName($email->getSenderName());
            }
        }
        if ($email) {
            $message->setEmailId($email->getId());
        }
        //ÐµÑÐ»Ð¸ ÑÐ¸ÐºÐµÑ Ð±ÑÐ» Ð·Ð°ÐºÑÑÑ, Ð·Ð°ÑÐµÐ¼ Ð¿Ð¾ÑÑÑÐ¿Ð¸Ð»Ð¾ ÑÐ¾Ð¾Ð±ÑÐµÐ½Ð¸Ðµ Ð¾Ñ Ð¿Ð¾Ð»ÑÐ·Ð¾Ð²Ð°ÑÐµÐ»Ñ - Ð¼Ñ ÐµÐ³Ð¾ Ð¾ÑÐºÑÑÐ²Ð°ÐµÐ¼
        if ($triggeredBy != Mirasvit_Helpdesk_Model_Config::USER) {
            if ($this->isClosed()) {
                $status = Mage::getModel('helpdesk/status')->loadByCode(Mirasvit_Helpdesk_Model_Config::STATUS_OPEN);
                $this->setStatusId($status->getId());
            }
            $this->setIsArchived(false);
        }
        $message->save();

        if ($email) {
            $email->setIsProcessed(true)
                  ->setAttachmentMessageId($message->getId())
                  ->save();
        } else {
            Mage::helper('helpdesk')->saveAttachments($message);
        }

        if (!$this->getIsSpam()) {
            if ($this->getReplyCnt() == 0) {
                Mage::helper('helpdesk/notification')->newTicket($this, $customer, $user, $triggeredBy, $messageType);
            } else {
                Mage::helper('helpdesk/notification')->newMessage($this, $customer, $user, $triggeredBy, $messageType);
            }
        }

        // if ($messageType == Mirasvit_Helpdesk_Model_Config::MESSAGE_PUBLIC || $messageType == Mirasvit_Helpdesk_Model_Config::MESSAGE_PUBLIC_THIRD) {
        $this->setReplyCnt($this->getReplyCnt() + 1);
        if(!$this->getFirstReplyAt() && $user) {
            $this->setFirstReplyAt(Mage::getSingleton('core/date')->gmtDate());
        }
        $this->setLastReplyAt(Mage::getSingleton('core/date')->gmtDate());
        // }

        $this->save();
        Mage::helper('helpdesk/history')->addMessage($this, $text, $triggeredBy, array('customer' => $customer, 'user' => $user, 'email' => $email), $messageType);
		return $message;
    }

    protected function updateFields()
    {
        $config = Mage::getSingleton('helpdesk/config');
        if (!$this->getPriorityId()) {
            $this->setPriorityId($config->getDefaultPriority());
        }
        if (!$this->getStatusId()) {
            $this->setStatusId($config->getDefaultStatus());
        }
        if (!$this->getCode()) {
            $this->setCode(Mage::helper('helpdesk/string')->generateTicketCode());
        }
        if (!$this->getExternalId()) {
            $this->setExternalId(md5($this->getCode().Mage::helper('helpdesk/string')->generateRandNum(10)));
        }
        if ($this->getCustomerId() > 0) {
            $customer = Mage::getModel('customer/customer')->load($this->getCustomerId());
            // Ð¼Ñ Ð½Ðµ Ð¼ÐµÐ½ÑÐµÐ¼ ÐµÐ¼ÐµÐ¹Ð», Ñ.Ðº. ÑÐµÐ»Ð¾Ð²ÐµÐº Ð¼Ð¾Ð³ Ð¿ÑÐ¸ÑÐ»Ð°ÑÑ ÑÐ¸ÐºÐµÑ Ð½Ðµ ÑÑÐ¾Ð³Ð¾ ÐµÐ¼ÐµÐ¹Ð»Ð° Ð¿Ð¾ ÐºÐ¾ÑÐ¾ÑÐ¾Ð¼Ñ ÑÐµÐ³Ð¸Ð»ÑÑ
            // Ð¼Ð¾Ð¶ÐµÑ ÑÑÐ¾Ñ if ÑÐ¶Ðµ Ð½Ðµ Ð½ÑÐ¶Ð½Ð¾???
            if (!$this->getCustomerEmail()) {
                $this->setCustomerEmail($customer->getEmail());
            }
            $this->setCustomerName($customer->getName());
        } elseif ($this->getQuoteAddressId() > 0) {
            $address = Mage::getModel('sales/quote_address')->load($this->getQuoteAddressId());
            $this->setCustomerName($address->getName());
        }
        if(!$this->getFirstSolvedAt() && $this->isClosed()) {
            $this->setFirstSolvedAt(Mage::getSingleton('core/date')->gmtDate());
        }
        if (in_array($this->getStatusId(), $config->getGeneralArchivedStatusList())) {
            $this->setIsArchived(true);
        }
    }

    protected function _beforeSave()
    {
        $this->updateFields();

        if ($this->getData('user_id') && ($this->getOrigData('user_id') != $this->getData('user_id'))) {
            Mage::helper('helpdesk/ruleevent')->newEvent(Mirasvit_Helpdesk_Model_Config::RULE_EVENT_TICKET_ASSIGNED, $this);
        }
        Mage::helper('helpdesk/ruleevent')->newEvent(Mirasvit_Helpdesk_Model_Config::RULE_EVENT_TICKET_UPDATED, $this);
        return parent::_beforeSave();
    }

    public function getUrl() {
        $url = Mage::getUrl('helpdesk/ticket/view', array('id' => $this->getId()));
        return $url;
    }

    public function getExternalUrl() {
        $url = Mage::getUrl('helpdesk/ticket/external', array('id' => $this->getExternalId(), '_store' => $this->getStoreId()));
        return $url;
    }

    public function getStopRemindUrl() {
        $url = Mage::getUrl('helpdesk/ticket/stopremind', array('id' => $this->getExternalId(), '_store' =>$this->getStoreId()));
        return $url;
    }

    public function getBackendUrl() {
        $url = Mage::helper("adminhtml")->getUrl("helpdeskadmin/adminhtml_ticket/edit", array('id'=>$this->getId()));
        return $url;
    }

    public function getMessages($includePrivate = false) {
        $collection = Mage::getModel('helpdesk/message')->getCollection()
            ->addFieldToFilter('ticket_id', $this->getId())
            ->setOrder('created_at', 'desc');
        if (!$includePrivate) {
            $collection->addFieldToFilter('is_internal', 0);
            $collection->addFieldToFilter('type',
                    array(
                        array('eq' => ''),
                        array('eq' => Mirasvit_Helpdesk_Model_Config::MESSAGE_PUBLIC),
                        array('eq' => Mirasvit_Helpdesk_Model_Config::MESSAGE_PUBLIC_THIRD),
                    )
                );
        }
        return $collection;
    }

	public function getLastMessage() {
        $collection = Mage::getModel('helpdesk/message')->getCollection()
            ->addFieldToFilter('ticket_id', $this->getId())
			->setOrder('message_id', 'asc');
		return $collection->getLastItem();
	}

    public function getLastMessageHtmlText() {
        $body = $this->getLastMessage()->getBody();
        $body = Mage::helper('helpdesk/string')->convertToHtml($body);
        return $body;
    }

    public function getLastMessagePlainText() {
        $body = $this->getLastMessage()->getBody();
        if ($this->getLastMessage()->getFormat() == Mirasvit_Helpdesk_Model_Config::FORMAT_HTML) {
            $body = Mage::helper('helpdesk/process')->convertToPlain($body);
        }
        return $body;
    }

    public function getCreatedAtFormated($format)
    {
        return Mage::helper('core')->formatDate($this->getCreatedAt(), $format).' '.Mage::helper('core')->formatTime($this->getCreatedAt(), $format);
    }

    public function getUpdatedAtFormated($format)
    {
        return Mage::helper('core')->formatDate($this->getUpdatedAt(), $format).' '.Mage::helper('core')->formatTime($this->getUpdatedAt(), $format);
    }


    public function open() {
        $status = Mage::getModel('helpdesk/status')->loadByCode(Mirasvit_Helpdesk_Model_Config::STATUS_OPEN);
        $this->setStatusId($status->getId())->save();
    }

    public function close() {
        $status = Mage::getModel('helpdesk/status')->loadByCode(Mirasvit_Helpdesk_Model_Config::STATUS_CLOSED);
        $this->setStatusId($status->getId())->save();
    }

    public function isClosed() {
        $status = Mage::getModel('helpdesk/status')->loadByCode(Mirasvit_Helpdesk_Model_Config::STATUS_CLOSED);
        if ($status->getId() == $this->getStatusId()) {
            return true;
        }
        return false;
    }

    public function initOwner($value, $prefix = false)
    {
        //set ticket user and department
        if ($value) {
            $owner = $value;
            $owner = explode('_', $owner);
            if ($prefix) {
                $prefix .='_';
            }
            $this->setData($prefix.'department_id', (int)$owner[0]);
            $this->setData($prefix.'user_id', (int)$owner[1]);
        }
        return $this;
    }

    public function markAsSpam($pattern = -1)
    {
        if (is_object($pattern)) {
            $pattern = $pattern->getId();
        }
        $this->setIsSpam(true)->save();
    }

    public function markAsNotSpam()
    {
        $this->setIsSpam(false)->save();
        if ($emailId = $this->getEmailId()) {
            $email = Mage::getModel('helpdesk/email')->load($emailId);
            $email->setPatternId(0)->save();
        }
    }

    protected $_customer = null;
    public function getCustomer()
    {
        if ($this->_customer === null) {
            if ($this->getCustomerId()) {
                $this->_customer = Mage::getModel('customer/customer')->load($this->getCustomerId());
            } elseif ($this->getCustomerEmail()) {
                $this->_customer = new Varien_Object(array(
                    'name' => $this->getCustomerName(),
                    'email' => $this->getCustomerEmail(),
                ));
            } else {
                $this->_customer = false;
            }
        }
        return $this->_customer;
    }

    protected $_order = null;
    public function getOrder()
    {
        if (!$this->getOrderId()) {
            return false;
        }
        if ($this->_order === null) {
            $this->_order = Mage::getModel('sales/order')->load($this->getOrderId());
        }
        return $this->_order;
    }

    public function getEmailSubject($subject = '')
    {
        if ($this->getEmailSubjectPrefix()) {
            $subject = $this->getEmailSubjectPrefix() . $subject;
        }
        return Mage::helper('helpdesk/email')->getEmailSubject($this, $subject);
    }

    public function getHiddenCodeHtml()
    {
        if (!Mage::getSingleton('helpdesk/config')->getNotificationIsShowCode()) {
            return Mage::helper('helpdesk/email')->getHiddenCode($this->getCode());
        }
    }

    public function getHistoryHtml()
    {
        return Mage::helper('helpdesk')->getHistoryHtml($this);
    }

    public function getUserName()
    {
        if ($this->getUser()) {
            return $this->getUser()->getName();
        }
    }

    public function getTags()
    {
        $tags = array(0);
        if (is_array($this->getTagIds())) {
            $tags = array_merge($tags, $this->getTagIds());
        }
        $collection = Mage::getModel('helpdesk/tag')->getCollection()
                        ->addFieldToFilter('tag_id', $tags);
        return $collection;
    }

    public function loadTagIds()
    {
        if ($this->getData('tag_ids') === null) {
            $this->getResource()->loadTagIds($this);
        }
    }

    public function hasCustomer()
    {
        return $this->getCustomerId() > 0 || $this->getQuoteAddressId() > 0;
    }

    public function initFromOrder($orderId)
    {
        $this->setOrderId($orderId);
        $order = $this->getOrder();
        $address = $order->getShippingAddress();

        $this->setQuoteAddressId($address->getId());
        $this->setCustomerId($order->getCustomerId());

        if ($this->getCustomerId()) {
            $this->setCustomerEmail($this->getCustomer()->getEmail());
        } elseif ($order->getCustomerEmail()) {
            $this->setCustomerEmail($order->getCustomerEmail());
        } else {
            $this->setCustomerEmail($address->getEmail());
        }
        // pr($this->getData());die;
        return $this;
    }

    public function isThirdPartyPublic()
    {
        foreach ($this->getMessages(true) as $message) {
            if ($message->getType() == Mirasvit_Helpdesk_Model_Config::MESSAGE_PUBLIC_THIRD) {
                return true;
            }
            if ($message->getType() == Mirasvit_Helpdesk_Model_Config::MESSAGE_INTERNAL_THIRD) {
                return false;
            }
        }
        return true;
    }
}
