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


class Mirasvit_Helpdesk_Model_Satisfaction extends Mage_Core_Model_Abstract
{

    protected function _construct()
    {
        $this->_init('helpdesk/satisfaction');
    }

    public function toOptionArray($emptyOption = false)
    {
    	return $this->getCollection()->toOptionArray($emptyOption);
    }
 
    protected $_message = null;
    public function getMessage()
    {
        if (!$this->getMessageId()) {
            return false;
        }
    	if ($this->_message === null) {
            $this->_message = Mage::getModel('helpdesk/message')->load($this->getMessageId());
    	}
    	return $this->_message;
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

	/************************/

}