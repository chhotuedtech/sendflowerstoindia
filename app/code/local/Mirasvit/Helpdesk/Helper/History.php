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


class Mirasvit_Helpdesk_Helper_History extends Mage_Core_Helper_Abstract
{
	static $history = array();

	public static function getHistoryRecord($ticket, $triggeredBy, $by)
	{
		if (!isset(self::$history[$triggeredBy])) {
			$history = Mage::getModel('helpdesk/history');
			$history->setTicketId($ticket->getId());
			$history->setTriggeredBy($triggeredBy);
	        switch ($triggeredBy) {
	        	case Mirasvit_Helpdesk_Model_Config::CUSTOMER:
	        		$history->setName($by['customer']->getName());
	        		break;
	        	case Mirasvit_Helpdesk_Model_Config::USER:
	        		$history->setName($by['user']->getName());
	        		break;
	        	case Mirasvit_Helpdesk_Model_Config::THIRD:
	        		$history->setName($by['email']->getSenderNameOrEmail());
	        		break;
	        	case Mirasvit_Helpdesk_Model_Config::RULE:
	        		$history->setName($by['rule']->getName());
	        		break;
	        }
	        self::$history[$triggeredBy] = $history;
		}

		return self::$history[$triggeredBy];
	}

	public function changeTicket($ticket, $triggeredBy, $by)
	{
		$history = self::getHistoryRecord($ticket, $triggeredBy, $by);
		$text = array();
		if ($ticket->getStatusId() != $ticket->getOrigData('status_id')) {
			if ($ticket->getOrigData('status_id')) {
				$oldStatus = Mage::getModel('helpdesk/status')->load($ticket->getOrigData('status_id'));
				$text[] = $this->__('Ticket status changed from: %s to: %s', $oldStatus->getName(), $ticket->getStatus()->getName());
			} else {
				$text[] = $this->__('Ticket status set to: %s', $ticket->getStatus()->getName());
			}
		}
		if ($ticket->getPriorityId() != $ticket->getOrigData('priority_id')) {
			if ($ticket->getOrigData('priority_id')) {
				$oldPriority = Mage::getModel('helpdesk/priority')->load($ticket->getOrigData('priority_id'));
				$text[] = $this->__('Ticket priority changed from: %s to: %s', $oldPriority->getName(), $ticket->getPriority()->getName());
			} else {
				$text[] = $this->__('Ticket priority set to: %s', $ticket->getPriority()->getName());
			}
		}
		if ($ticket->getUserId() != $ticket->getOrigData('user_id')) {
			if ($ticket->getOrigData('user_id')) {
				$oldUser = Mage::getModel('admin/user')->load($ticket->getOrigData('user_id'));
                if ($oldUser && $ticket->getUser()) {
    				$text[] = $this->__('Ticket owner changed from: %s to: %s', $oldUser->getName(), $ticket->getUser()->getName());
                }
			} else {
				$text[] = $this->__('Ticket owner set to: %s', $ticket->getUser()->getName());
			}
		}
		if ($ticket->getDepartmentId() != $ticket->getOrigData('department_id')) {
			if ($ticket->getOrigData('department_id')) {
				$oldDepartment = Mage::getModel('helpdesk/department')->load($ticket->getOrigData('department_id'));
				$text[] = $this->__('Ticket department changed from: %s to: %s', $oldDepartment->getName(), $ticket->getDepartment()->getName());
			} else {
				$text[] = $this->__('Ticket department set to: %s', $ticket->getDepartment()->getName());
			}
		}
        $history->addMessage($text);
	}

	public function addMessage($ticket, $text, $triggeredBy, $by, $messageType)
	{
		$history = self::getHistoryRecord($ticket, $triggeredBy, $by);
        $text = array();
        switch ($messageType) {
        	case Mirasvit_Helpdesk_Model_Config::MESSAGE_PUBLIC:
        		$text[] = $this->__('Message added to ticket');
        		break;
        	case Mirasvit_Helpdesk_Model_Config::MESSAGE_INTERNAL:
        		$text[] = $this->__('Internal note added to ticket');
        		break;
        	case Mirasvit_Helpdesk_Model_Config::MESSAGE_PUBLIC_THIRD:
        		$text[] = $this->__('Third party message added to ticket');
        		break;
        	case Mirasvit_Helpdesk_Model_Config::MESSAGE_INTERNAL_THIRD:
        		$text[] = $this->__('Private third party message added to ticket');
        		break;
        }
        $history->addMessage($text);
	}
}