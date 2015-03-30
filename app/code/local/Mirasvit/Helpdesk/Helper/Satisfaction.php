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



class Mirasvit_HelpDesk_Helper_Satisfaction extends Varien_Object
{
    public function addRate($messageUid, $rate)
    {
        $message = $this->getMessageByUid($messageUid);
        $satisfaction = $this->getSatisfactionByMessage($message);

        $ticket = Mage::getModel('helpdesk/ticket')->load($message->getTicketId());

        $satisfaction->setTicketId($message->getTicketId())
            ->setMessageId($message->getMessageId())
            ->setCustomerId($message->getCustomerId())
            ->setUserId($message->getUserId())
            ->setStoreId($ticket->getStoreId())
            ->setRate($rate)
            ->save();

        Mage::helper('helpdesk/mail')->sendNotificationStaffNewSatisfaction($satisfaction);

        return $satisfaction;
    }

    public function addComment($messageUid, $comment)
    {
        $message = $this->getMessageByUid($messageUid);
        $satisfaction = $this->getSatisfactionByMessage($message);
        $satisfaction->setComment($comment)
            ->save();

        Mage::helper('helpdesk/mail')->sendNotificationStaffNewSatisfaction($satisfaction);
    }

    public function getMessageByUid($messageUid)
    {
        $messages = Mage::getModel('helpdesk/message')->getCollection()
                    ->addFieldToFilter('uid', $messageUid);
        if (!$messages->count()) {
            throw new Mage_Core_Exception("Wrong URL");
        }
        $message = $messages->getFirstItem();

        return $message;
    }

    public function getSatisfactionByMessage($message)
    {
        $satisfactions = Mage::getModel('helpdesk/satisfaction')->getCollection()
            ->addFieldToFilter('message_id', $message->getId());
        if ($satisfactions->count()) {
            $satisfaction = $satisfactions->getFirstItem();
        } else {
            $satisfaction = Mage::getModel('helpdesk/satisfaction');
        }

        return $satisfaction;
    }
}