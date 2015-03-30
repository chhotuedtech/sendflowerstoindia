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


class Mirasvit_Helpdesk_Helper_Followup extends Mage_Core_Helper_Abstract
{
	public function process($ticket)
	{
		if ($ticket->getFpPriorityId()) {
			$ticket->setPriorityId($ticket->getFpPriorityId());
		}
		if ($ticket->getFpStatusId()) {
			$ticket->setStatusId($ticket->getFpStatusId());
		}
		if ($ticket->getFpDepartmentId()) {
			$ticket->setDepartmentId($ticket->getFpDepartmentId());
		}
		if ($ticket->getFpUserId()) {
			$ticket->setUserId($ticket->getFpUserId());
		}
		if ($ticket->getFpIsRemind()) {
			Mage::helper('helpdesk/mail')->sendNotificationReminder($ticket);
		}
		$ticket->setData('fp_execute_at', null)
				->setData('fp_period_value', null)
				->setData('fp_period_unit', null)
				->setData('fp_is_remind', false);
		$ticket->save();
	}
}