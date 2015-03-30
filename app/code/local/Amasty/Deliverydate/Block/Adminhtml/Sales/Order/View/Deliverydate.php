<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Block_Adminhtml_Sales_Order_View_Deliverydate extends Mage_Adminhtml_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('amasty/amdeliverydate/deliverydate.phtml');
        
        $deliveryDate = Mage::getModel('amdeliverydate/deliverydate');
        $deliveryDate->load($this->_getOrderId(), 'order_id');
        Mage::register('current_deliverydate', $deliveryDate, true);
    }
    
    protected function _getOrderId()
    {
        if (Mage::registry('current_order')) {
            return Mage::registry('current_order')->getId();
        }
        if (Mage::registry('current_invoice')) {
            return Mage::registry('current_invoice')->getOrderId();
        }
        if (Mage::registry('current_shipment')) {
            return Mage::registry('current_shipment')->getOrderId();
        }
    }
    
    public function getDeliveryDateFields($place = 'order')
    {
        $fields = array();
        $hlp = Mage::helper('amdeliverydate');
        $deliveryDate = $this->getDeliveryDate();
        $show = Mage::helper('amdeliverydate')->whatShow($place . '_view');
        foreach ($show as $key) {
            switch ($key) {
                case 'date':
                    if ('0000-00-00' == $deliveryDate['date']) {
                        $deliveryDate['date'] = '';
                    }
                    $fields[] = array('code' => 'date',
                                      'label' => $hlp->__('Delivery Date'),
                                      'value' => $deliveryDate['date']);
                    break;
                case 'time':
                    $fields[] = array('code' => 'time',
                                  'label' => $hlp->__('Delivery Time Interval'),
                                  'value' => $deliveryDate['time']);
                    break;
                case 'comment':
                    $fields[] = array('code' => 'comment',
                                  'label' => $hlp->__('Delivery Comments'),
                                  'value' => $deliveryDate['comment']);
                    break;
            }
        }
        return $fields;
    }
    
    public function getDeliveryDate()
    {
        return Mage::registry('current_deliverydate');
    }
    
    public function getEditUrl()
    {
        if (Mage::registry('current_order') && $this->isAllowedEdit()) {
            return $this->getUrl('amdeliverydate/adminhtml_order/edit', array('order_id' => Mage::registry('current_order')->getId()));
        } else {
            return '';
        }
    }
    
    public function isAllowedEdit()
    {
        return Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/edit_deliverydate');
    }
}
