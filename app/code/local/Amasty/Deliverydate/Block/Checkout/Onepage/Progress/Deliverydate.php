<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Block_Checkout_Onepage_Progress_Deliverydate extends Mage_Core_Block_Template        
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('amasty/amdeliverydate/progress.phtml');
    }
    
    public function getDeliveryDateProgress()
    {
        $checkout = Mage::getSingleton('checkout/type_onepage')->getCheckout();
        $deliveryDate = $checkout->getAmastyDeliveryDate();
        return $deliveryDate;
    }
}