<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Model_Sales_Order extends Mage_Sales_Model_Order
{
    public function deliverydate($key, $nltobr = false)
    {
        $deliveryDate = Mage::getModel('amdeliverydate/deliverydate');
        $deliveryDate->load($this->getId(), 'order_id');
        
        $value = $deliveryDate->getData($key);
        
        if ($value) {
            switch ($key) {
                case 'date':
                    if ('0000-00-00' != $value) {
                        $value = date(Mage::helper('amdeliverydate')->getPhpFormat($this->getStoreId()), strtotime($value));
                    } else {
                        $value = '';
                    }
                    break;
                case 'comment':
                    $value = htmlentities(preg_replace('/\$/','\\\$', $value), ENT_COMPAT, "UTF-8");
                    if ($nltobr) {
                        $value = nl2br($value);
                    }
                    break;
            }
        }
        
        return $value;
    }
}