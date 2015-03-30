<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Model_Source_Format
{
    public function toOptionArray() 
    {
        $hlp = Mage::helper('amdeliverydate');
        return array(
            array(
                'value' => 'M/D/Y',
                'label' => $hlp->__('Month / Day / Year')
            ),
            array(
                'value' => 'D/M/Y',
                'label' => $hlp->__('Day / Month / Year')
            ),
            array(
                'value' => 'Y/M/D',
                'label' => $hlp->__('Year / Month / Day')
            ),
        );
    }
}