<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Model_Sales_Order_Api extends Mage_Sales_Model_Order_Api
{
    public function info($orderIncrementId)
    {
        $result = parent::info($orderIncrementId);
        
        if (isset($result['order_id'])) {
            $deliveryDate = Mage::getModel('amdeliverydate/deliverydate');
            $deliveryDate->load($result['order_id'], 'order_id');
            
            $custom = array();
            $fields = array('date', 'time', 'comment');
            foreach ($fields as $field) {
                if ($value = $deliveryDate->getData($field)) {
                    switch ($field) {
                        case 'date':
                            if ('0000-00-00' != $value) {
                                $order = Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);
                                $value = date(Mage::helper('amdeliverydate')->getPhpFormat($order->getStoreId()), strtotime($value));
                            } else {
                                $value = '';
                            }
                            break;
                        case 'comment':
                            $value = htmlentities(preg_replace('/\$/','\\\$', $value), ENT_COMPAT, "UTF-8");
                            break;
                    }
                    if ($value) {
                        $custom[] = array('key' => $field, 'value' => $value);
                    }
                }
            }
            
            if ($custom) {
                $result['deliverydate'] = $custom;
            }
        }
        
        return $result;
    }
}