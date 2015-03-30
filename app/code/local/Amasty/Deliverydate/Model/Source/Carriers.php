<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Model_Source_Carriers
{
    public function toOptionArray()
    {
        $methods = array();
        $activeCarriers = Mage::getSingleton('shipping/config')->getActiveCarriers();
        foreach ($activeCarriers as $carrierCode => $carrierModel) {
            $options = array();
            if ($carrierMethods = $carrierModel->getAllowedMethods()) {
                foreach ($carrierMethods as $methodCode => $method) {
                    $code = $carrierCode . '_' . $methodCode;
                    $options[] = array('value' => $code, 'label' => $method);
                }
                $carrierTitle = Mage::getStoreConfig('carriers/'.$carrierCode.'/title');
                $methods[] = array('value' => $options, 'label' => $carrierTitle);
            }
        }
        return $methods;
    }
}
