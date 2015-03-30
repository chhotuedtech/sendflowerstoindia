<?php class Magentomasters_Supplier_Model_Source_Status{

	public function toOptionArray(){
        $options = array(
			'1'=> Mage::helper('supplier')->__('Active'),
			'2'=> Mage::helper('supplier')->__('Deactivated'),
			'3'=> Mage::helper('supplier')->__('New Registration'),
		);
        return $options;
    }
		
}