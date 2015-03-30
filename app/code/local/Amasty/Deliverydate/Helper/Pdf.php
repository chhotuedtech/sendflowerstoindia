<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Helper_Pdf extends Mage_Core_Helper_Abstract
{
    public function addDeliverydate(&$page, $obj, $control)
    {
        if ($obj instanceof Mage_Sales_Model_Order) {
            $shipment = null;
            $order = $obj;
            $currentStore = $order->getStoreId();
            $fields = Mage::helper('amdeliverydate')->whatShow('invoice_pdf', $currentStore, 'include');
        } elseif ($obj instanceof Mage_Sales_Model_Order_Shipment) {
            $shipment = $obj;
            $order = $shipment->getOrder();
            $currentStore = $order->getStoreId();
            $fields = Mage::helper('amdeliverydate')->whatShow('shipment_pdf', $currentStore, 'include');
        }
        
        if (!Mage::getStoreConfig('amdeliverydate/general/enabled', $currentStore)) {
            return ;
        }
        
        if (!is_array($fields) || empty($fields)) {
            return ;
        }
        
        $deliveryDate = Mage::getModel('amdeliverydate/deliverydate');
        $deliveryDate->load($order->getId(), 'order_id');
        
        $list = array();
        foreach ($fields as $field) {
            $value = $deliveryDate->getData($field);
            if ('date' == $field) {
                $label = $this->__('Delivery Date');
                if ('0000-00-00' != $value) {
                    $value = date(Mage::helper('amdeliverydate')->getPhpFormat($currentStore), strtotime($value));
                } else {
                    $value = '';
                }
            } elseif ('time' == $field) {
                $label = $this->__('Delivery Time Interval');
            } elseif ('comment' == $field) {
                $label = $this->__('Delivery Comments');
                $value = htmlentities(preg_replace('/\$/','\\\$', $value), ENT_COMPAT, "UTF-8");
                $text = str_replace(array("\r\n", "\n", "\r"), '~~~', $value);
                $value = array();
                foreach (explode('~~~', $text) as $str) {
                    foreach (Mage::helper('core/string')->str_split($str, 120, true, true) as $part) {
                        if (empty($part)) {
                            continue;
                        }
                        $value[] = $part;
                    }
                }
            }
            if (is_array($value)) {
                $list[$label] = $value;
            } elseif ($value) {
                $list[$label] = $value;
            }
        }
        
        if (empty($list)) {
            return ;
        }
        
        $page->setFillColor(new Zend_Pdf_Color_RGB(0.93, 0.92, 0.92));
        $page->setLineColor(new Zend_Pdf_Color_GrayScale(0.5));
        $page->setLineWidth(0.5);
        
        $page->drawRectangle(25, $control->y, 570, $control->y -15);
        $control->y -= 10;
        $page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
        $page->drawText($this->__('Delivery Date'), 35, $control->y, 'UTF-8');
        $control->y -= 15;
        
        foreach ($list as $label => $value) {
            if (is_array($value)) {
                $page->drawText($label . ': ', 35, $control->y, 'UTF-8');
                foreach ($value as $str) {
                    $page->drawText($str, 120, $control->y, 'UTF-8');
                    $control->y -= 10;
                }
            } else {
                $page->drawText($label . ': ', 35, $control->y, 'UTF-8');
                $page->drawText($value, 120, $control->y, 'UTF-8');
                $control->y -= 10;
            }
        }
        
        $control->y -= 10;
    }
}