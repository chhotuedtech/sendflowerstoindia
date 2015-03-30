<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Model_Source_Include
{
    public function toOptionArray()
    {
        $hlp = Mage::helper('amdeliverydate');
        return array(
            array(
                'value' => 'order_email',
                'label' => $hlp->__('Order Confirmation E-mail')
            ),
            array(
                'value' => 'invoice_email',
                'label' => $hlp->__('Invoice E-mail')
            ),
            array(
                'value' => 'shipment_email',
                'label' => $hlp->__('Shipment E-mail')
            ),
            array(
                'value' => 'invoice_pdf',
                'label' => $hlp->__('Invoice PDF')
            ),
            array(
                'value' => 'shipment_pdf',
                'label' => $hlp->__('Shipment PDF (Packing Slip)')
            ),
        );
    }
}
