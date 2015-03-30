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


class Mirasvit_Helpdesk_Block_Ticket_List extends Mage_Core_Block_Template
{
	protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($headBlock = $this->getLayout()->getBlock('head')) {
            $headBlock->setTitle(Mage::helper('helpdesk')->__('My Tickets'));
        }
    }

    protected function getCustomer()
    {
        return Mage::getSingleton('customer/session')->getCustomer();
    }

    public function getTicketCollection()
    {
      $collection = Mage::getModel('helpdesk/ticket')->getCollection()
        ->addFieldToFilter('customer_id', $this->getCustomer()->getId())
        ->addFieldToFilter('is_spam', false)
        ;
        return $collection;
    }

    /************************/

    public function getPriorityCollection()
    {
        return Mage::getModel('helpdesk/priority')->getCollection()
                ->setOrder('sort_order', 'asc');
    }

    public function getDepartmentCollection()
    {
        return Mage::getModel('helpdesk/department')->getCollection()
                ->setOrder('sort_order', 'asc');
    }

    public function getOrderCollection()
    {
        $collection = Mage::getModel('sales/order')->getCollection()
                ->addAttributeToSelect('*')
                ->addFieldToFilter('customer_id', (int)$this->getCustomer()->getId())
                ;
        return $collection;
    }

    public function getCustomFields()
    {
        $collection = Mage::getModel('helpdesk/field')->getCollection()
                    ->addFieldToFilter('is_active', true)
                    ->addFieldToFilter('is_editable_customer', true)
                    ->setOrder('sort_order');
        return $collection;
    }

    public function getInputHtml($field)
    {
        return Mage::helper('helpdesk/field')->getInputHtml($field);
    }

    public function getConfig()
    {
        return Mage::getSingleton('helpdesk/config');
    }

    public function getFrontendIsAllowPriority()
    {
        return $this->getConfig()->getFrontendIsAllowPriority();
    }

    public function getFrontendIsAllowDepartment()
    {
        return $this->getConfig()->getFrontendIsAllowDepartment();
    }

    public function getFrontendIsAllowOrder()
    {
        return $this->getConfig()->getFrontendIsAllowOrder();
    }
}
