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



class Mirasvit_Helpdesk_Block_Ticket_View extends Mage_Core_Block_Template
{
	protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $ticket = $this->getTicket();
        if ($headBlock = $this->getLayout()->getBlock('head')) {
            $headBlock->setTitle(Mage::helper('helpdesk')->__('['.$ticket->getCode().'] '.$ticket->getName()));
        }
    }

    public function getTicket()
    {
        return Mage::registry('current_ticket');
    }

    /************************/

    public function getPostUrl() {
        $ticket = $this->getTicket();
        if (Mage::registry('external_ticket')) {
            return Mage::getUrl('helpdesk/ticket/postexternal', array('id'=>$ticket->getExternalId()));
        } else {
            return Mage::getUrl('helpdesk/ticket/postmessage', array('id'=>$ticket->getId()));
        }
    }

    public function getCustomFields()
    {
        $collection = Mage::helper('helpdesk/field')->getVisibleCustomerCollection();
        return $collection;
    }

    public function getOrderUrl($orderId)
    {
        return Mage::getUrl('sales/order/view', array('order_id' => $orderId));
    }
}

