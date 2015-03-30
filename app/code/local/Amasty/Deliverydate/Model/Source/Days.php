<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Model_Source_Days
{
    public function toOptionArray()
    {
        $hlp = Mage::helper('amdeliverydate');
        return array(
            array( // magento wants at least one option to be selected
                'value' => '0',
                'label' => '',
            ),
            array(
                'value' => '1',
                'label' => $hlp->__('Sunday')
            ),
            array(
                'value' => '2',
                'label' => $hlp->__('Monday')
            ),
            array(
                'value' => '3',
                'label' => $hlp->__('Tuesday')
            ),
            array(
                'value' => '4',
                'label' => $hlp->__('Wednesday')
            ),
            array(
                'value' => '5',
                'label' => $hlp->__('Thursday')
            ),
            array(
                'value' => '6',
                'label' => $hlp->__('Friday')
            ),
            array(
                'value' => '7',
                'label' => $hlp->__('Saturday')
            ),
        );
    }
}
