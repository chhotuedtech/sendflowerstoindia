<?php class Magentomasters_Supplier_Model_Source_Types{


	public function toOptionArray()
        {
			$options = array(
                '0'     => Mage::helper('supplier')->__('None'),
				'1'     => Mage::helper('supplier')->__('Email'),
                '2'     => Mage::helper('supplier')->__('XML'),
				'3'     => Mage::helper('supplier')->__('FTP XML'),
            );
            return $options;
        }
		
}