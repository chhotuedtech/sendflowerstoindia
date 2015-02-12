<?php
/*------------------------------------------------------------------------
 # SM Zen - Version 1.0
 # Copyright (c) 2014 The YouTech Company. All Rights Reserved.
 # @license - Copyrighted Commercial Software
 # Author: YouTech Company
 # Websites: http://www.magentech.com
-------------------------------------------------------------------------*/

class Sm_Milan_Model_System_Config_Source_ListColor
{
	public function toOptionArray()
	{	
		return array(
		array('value'=>'blue', 'label'=>Mage::helper('milan')->__('Blue')),
		array('value'=>'cyan', 'label'=>Mage::helper('milan')->__('Cyan')),
		array('value'=>'red', 'label'=>Mage::helper('milan')->__('Red')),
		array('value'=>'green', 'label'=>Mage::helper('milan')->__('Green')),
		array('value'=>'orange', 'label'=>Mage::helper('milan')->__('Orange')),
		array('value'=>'avocado', 'label'=>Mage::helper('milan')->__('Avocado'))
		);
	}
}
