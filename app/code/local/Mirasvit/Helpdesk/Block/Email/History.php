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



class Mirasvit_Helpdesk_Block_Email_History extends Mage_Core_Block_Template
{
    protected function _construct()
    {
        parent::_construct();
        $this->setData('area','frontend');
    }

	public function getLimit()
	{
		return Mage::getSingleton('helpdesk/config')->getNotificationHistoryRecordsNumber();
	}

	public function getMessages()
	{
		$collection = $this->getTicket()->getMessages();
		$collection->getSelect()->limit($this->getLimit(), 1); //don't show first message
		return $collection;
	}
}