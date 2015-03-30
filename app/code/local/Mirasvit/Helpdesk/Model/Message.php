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


class Mirasvit_Helpdesk_Model_Message extends Mage_Core_Model_Abstract
{

    protected function _construct()
    {
        $this->_init('helpdesk/message');
    }

    public function toOptionArray($emptyOption = false)
    {
    	return $this->getCollection()->toOptionArray($emptyOption);
    }

    protected $_ticket = null;
    public function getTicket()
    {
        if (!$this->getTicketId()) {
            return false;
        }
    	if ($this->_ticket === null) {
            $this->_ticket = Mage::getModel('helpdesk/ticket')->load($this->getTicketId());
    	}
    	return $this->_ticket;
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

	/************************/

	public function getAttachments() {
		return Mage::getModel('helpdesk/attachment')->getCollection()
			->addFieldToFilter('message_id', $this->getId());
	}

    public function getFrontendUserName()
    {
        if (Mage::getSingleton('helpdesk/config')->getGeneralSignTicketBy() == Mirasvit_Helpdesk_Model_Config::SIGN_TICKET_BY_DEPARTMENT) {
            $departments = Mage::getModel('helpdesk/department')->getCollection()
                    ->addUserFilter($this->getUserId());
            if ($departments->count()) {
                return $departments->getFirstItem()->getName();
            } else {
                return $this->getTicket()->getDepartmentName();
            }
        } else {
            return $this->getUserName();
        }
    }

    public function _beforeSave()
    {
        parent::_beforeSave();
        if (!$this->getUid()) {
            $uid = md5(
                Mage::getSingleton('core/date')->gmtDate() .
                Mage::helper('mstcore/string')->generateRandHeavy(100));
            $this->setUid($uid);
        }
    }

    public function isThirdParty()
    {
        return $this->getType() == Mirasvit_Helpdesk_Model_Config::MESSAGE_PUBLIC_THIRD
                    || $this->getType() == Mirasvit_Helpdesk_Model_Config::MESSAGE_INTERNAL_THIRD;
    }

    public function isInternal()
    {
        return $this->getType() == Mirasvit_Helpdesk_Model_Config::MESSAGE_INTERNAL;
    }


    /**
     * we need this method to support DB from old releases
     */
    public function getTriggeredBy()
    {
        if ($this->getData('triggered_by')) {
            return $this->getData('triggered_by');
        }
        if ($this->getUser()) {
            return Mirasvit_Helpdesk_Model_Config::USER;
        }
        return Mirasvit_Helpdesk_Model_Config::CUSTOMER;
    }
}
