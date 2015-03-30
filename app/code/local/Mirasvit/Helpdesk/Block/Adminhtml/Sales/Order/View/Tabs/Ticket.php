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



class Mirasvit_Helpdesk_Block_Adminhtml_Sales_Order_View_Tabs_Ticket extends Mage_Adminhtml_Block_Widget
implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    public function getTabLabel()
    {
        return Mage::helper('helpdesk')->__('Customer Tickets');
    }

    public function getTabTitle()
    {
        return Mage::helper('helpdesk')->__('Customer Tickets');
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }

    protected function _toHtml()
    {
        $id = $this->getRequest()->getParam('order_id');
        $ticketNewUrl = $this->getUrl('helpdeskadmin/adminhtml_ticket/add', array('order_id' => $id));
        $button = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setClass('add')
            ->setType('button')
            ->setOnClick('window.location.href=\'' . $ticketNewUrl . '\'')
            ->setLabel($this->__('Create ticket for this order'));


        $grid = $this->getLayout()->createBlock('helpdesk/adminhtml_ticket_grid');
        $grid->addCustomFilter('order_id', $id);
        $grid->removeFilter('is_archived');
        $grid->setFilterVisibility(false);
        $grid->setPagerVisibility(0);
        $grid->setTabMode(true);

        return '<div>' . $button->toHtml() . '<br><br>'. $grid->toHtml().'</div>' ;

        // return '<div class="content-buttons-placeholder" style="height:25px;">' .
        // '<p class="content-buttons form-buttons" >' . $button->toHtml() . '</p>' .
        // '</div>' . $grid->toHtml();
    }
}