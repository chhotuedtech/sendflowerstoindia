<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Model_Source_Style
{
    public function toOptionArray()
    {
        $hlp = Mage::helper('amdeliverydate');
        return array(
            array(
                'value' => 'as_is',
                'label' => $hlp->__('As is')
            ),
            array(
                'value' => 'notice',
                'label' => $hlp->__('Magento Notice')
            ),
        );
    }
}
