<?php

class MagentoMasters_Supplier_Model_Invoiceshipment {
    public function toOptionArray()
    {
        return array(
        	array('value' => 'disabled', 'label'=>Mage::helper('adminhtml')->__('Disabled')),
            array('value' => 'invoice', 'label'=>Mage::helper('adminhtml')->__('Invoice Create')),
			array('value' => 'ordercreate', 'label'=>Mage::helper('adminhtml')->__('Order Create')),
			array('value' => 'orderstatus', 'label'=>Mage::helper('adminhtml')->__('Order Status')),
        );
    }
}