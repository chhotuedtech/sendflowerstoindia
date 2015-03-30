<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Block_Adminhtml_Sales_Order_View_Deliverydate_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'amdeliverydate';
        $this->_objectId   = 'deliverydate_id';
        $this->_controller = 'adminhtml_sales_order_view_deliverydate';
        
        parent::__construct();

        $backUrl = $this->getUrl('adminhtml/sales_order/view', array('order_id' => Mage::app()->getRequest()->getParam('order_id')));
        $this->_updateButton('back', 'onclick', "setLocation('{$backUrl}')");
        $this->_updateButton('save', 'label', Mage::helper('amdeliverydate')->__('Save Delivery Date'));
        $this->_removeButton('delete');
    }

    public function getHeaderText()
    {
        $order = Mage::registry('current_order');
        return Mage::helper('amdeliverydate')->__('Edit Delivery Date For The Order #%s', $order->getIncrementId());
    }

    public function getValidationUrl()
    {
        return $this->getUrl('*/*/validate', array('_current'=>true));
    }

    public function getSaveUrl()
    {
        return $this->getUrl('*/*/save', array('_current'=>true, 'back'=>null));
    }
}