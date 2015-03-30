<?php
/*------------------------------------------------------------------------
 # SM Zen - Version 1.0
 # Copyright (c) 2014 The YouTech Company. All Rights Reserved.
 # @license - Copyrighted Commercial Software
 # Author: YouTech Company
 # Websites: http://www.magentech.com
-------------------------------------------------------------------------*/

class Sm_Milan_Model_System_Config_Source_ListBodyFont
{
	public function toOptionArray()
	{	
		return array(
			array('value'=>'Arial', 'label'=>Mage::helper('milan')->__('Arial')),
			array('value'=>'Arial Black', 'label'=>Mage::helper('milan')->__('Arial-black')),
			array('value'=>'Courier New', 'label'=>Mage::helper('milan')->__('Courier New')),
			array('value'=>'Georgia', 'label'=>Mage::helper('milan')->__('Georgia')),
			array('value'=>'Impact', 'label'=>Mage::helper('milan')->__('Impact')),
			array('value'=>'Lucida Console', 'label'=>Mage::helper('milan')->__('Lucida-console')),
			array('value'=>'Lucida Grande', 'label'=>Mage::helper('milan')->__('Lucida-grande')),
			array('value'=>'Palatino', 'label'=>Mage::helper('milan')->__('Palatino')),
			array('value'=>'Tahoma', 'label'=>Mage::helper('milan')->__('Tahoma')),
			array('value'=>'Times New Roman', 'label'=>Mage::helper('milan')->__('Times New Roman')),	
			array('value'=>'Trebuchet', 'label'=>Mage::helper('milan')->__('Trebuchet')),	
			array('value'=>'Verdana', 'label'=>Mage::helper('milan')->__('Verdana'))		
		);
	}
}
