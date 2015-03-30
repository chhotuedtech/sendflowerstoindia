<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Block_Sales_Order_Email_Deliverydate extends Mage_Adminhtml_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('amasty/amdeliverydate/email.phtml');
    }
    
    public function setDeliveryDate()
    {
        $deliveryDate = Mage::getModel('amdeliverydate/deliverydate');
        $deliveryDate->load($this->getOrder()->getId(), 'order_id');
        Mage::register('current_deliverydate', $deliveryDate, true);
    }
    
    public function getDeliveryDate()
    {
        return Mage::registry('current_deliverydate');
    }
    
    protected function _getStoreId()
    {
        return $this->getOrder()->getStoreId();
    }
}