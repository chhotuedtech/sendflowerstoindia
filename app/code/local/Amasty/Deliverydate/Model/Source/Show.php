<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Model_Source_Show
{
    public function toOptionArray()
    {
        $hlp = Mage::helper('amdeliverydate');
        return array(
            array(
                'value' => 'order_grid',
                'label' => $hlp->__('Order Grid (Backend)')
            ),
            array(
                'value' => 'invoice_grid',
                'label' => $hlp->__('Invoice Grid (Backend)')
            ),
            array(
                'value' => 'shipment_grid',
                'label' => $hlp->__('Shipment Grid (Backend)')
            ),
            array(
                'value' => 'order_view',
                'label' => $hlp->__('Order View Page (Backend)')
            ),
            array(
                'value' => 'order_create',
                'label' => $hlp->__('New/Edit/Reorder Order Page (Backend)')
            ),
            array(
                'value' => 'invoice_view',
                'label' => $hlp->__('Invoice View Page (Backend)')
            ),
            array(
                'value' => 'shipment_view',
                'label' => $hlp->__('Shipment View Page (Backend)')
            ),
            array(
                'value' => 'order_info',
                'label' => $hlp->__('Order Info Page (Frontend)')
            ),
        );
    }
}
