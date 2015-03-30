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


class Mirasvit_Helpdesk_Model_Observer
{
	protected $cronChecked = false;
	public function checkCronStatus()
	{
		$admin = Mage::getSingleton('admin/session')->getUser();
		if (!$admin) {
			return;
		}
		if (Mage::app()->getRequest()->isXmlHttpRequest() || Mage::app()->getRequest()->getParam('isAjax')) {
			return;
		}
		if ($this->cronChecked) {
			return;
		}
		$gateways = Mage::getModel('helpdesk/gateway')->getCollection()
					->addFieldToFilter('is_active', true);
		if ($gateways->count() == 0) {
			return;
		}
    	if (!Mage::helper('mstcore/cron')->isCronRunning('mirasvit_helpdesk')) {
			$message = Mage::helper('helpdesk')->__('Help Desk can\'t fetch new emails. Cron is not running. You need to setup a cron job for Magento. To do this, add the following expression to your crontab <br><i>%s</i> <br><a href="http://mirasvit.com/docs/hdmx/1.0.1/configuration/cron.html" target="_blank">Read more</a>. <br> To temporary hide this message, disable all <a href="%s">help desk gateways</a>.', Mage::helper('mstcore/cron')->getCronExpression(),

				Mage::helper("adminhtml")->getUrl('helpdeskadmin/adminhtml_gateway'));
	        Mage::getSingleton('adminhtml/session')->addError($message);
	    }
	    $this->cronChecked = true;
	}
}
