<?php class Magentomasters_Supplier_Model_Source_Dropship{


	public function toOptionArray()
        {
			$options = array(
					'1'     => Mage::helper('supplier')->__('Pending'),
                    '2'     => Mage::helper('supplier')->__('Scheduled'),
                    '3'     => Mage::helper('supplier')->__('Canceled'),
                    '4'     => Mage::helper('supplier')->__('Refunded'),
                    '5'     => Mage::helper('supplier')->__('Completed'), 
            );
            return $options;
        }
		
}