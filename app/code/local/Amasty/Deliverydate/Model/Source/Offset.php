<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Model_Source_Offset
{
    public function toOptionArray()
    {
        $options = array(); 
        $hlp = Mage::helper('amdeliverydate');
        
        for ($i = -12; $i <= 12; $i++) {
            $v = $i > 0 ? "+$i" : $i;
            $hours = ($i==1 || $i==-1) ? '%d hour %s': '%d hours %s';
            $now = date('U') + 3600 * $v;
            $time = '(' . date('H', $now) . ':' . date('i', $now) . ')';
            $options[] = array(
                'value' => $v,
                'label' => $hlp->__($hours, $v, $time),
            );
        }
        
        return $options;
    }
}