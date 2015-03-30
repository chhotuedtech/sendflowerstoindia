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


class Mirasvit_Helpdesk_Model_Email extends Mage_Core_Model_Abstract
{

    protected function _construct()
    {
        $this->_init('helpdesk/email');
    }

    public function toOptionArray($emptyOption = false)
    {
    	return $this->getCollection()->toOptionArray($emptyOption);
    }


	/************************/

	public function getAttachments() {
		return Mage::getModel('helpdesk/attachment')->getCollection()
			->addFieldToFilter('email_id', $this->getId());
	}

    public function getSenderNameOrEmail()
    {
        if ($this->getSenderName()) {
            return $this->getSenderName();
        }
        return $this->getFromEmail();
    }

    protected $_gateway = null;
    public function getGateway()
    {
        if (!$this->getGatewayId()) {
            return false;
        }
        if ($this->_gateway === null) {
            $this->_gateway = Mage::getModel('helpdesk/gateway')->load($this->getGatewayId());
        }
        return $this->_gateway;
    }
}
