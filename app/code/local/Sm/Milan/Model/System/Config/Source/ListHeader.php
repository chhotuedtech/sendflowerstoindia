<?php
/*------------------------------------------------------------------------
 # SM Milan - Version 1.1
 # Copyright (c) 2013 The YouTech Company. All Rights Reserved.
 # @license - Copyrighted Commercial Software
 # Author: YouTech Company
 # Websites: http://www.magentech.com
-------------------------------------------------------------------------*/

class Sm_Milan_Model_System_Config_Source_ListHeader
{
	public function toOptionArray()
	{	
		return array(
		array('value'=>'df', 'label'=>Mage::helper('milan')->__('Default')),
		array('value'=>'hd1', 'label'=>Mage::helper('milan')->__('Header 1')),
		array('value'=>'hd2', 'label'=>Mage::helper('milan')->__('Header 2')),
		array('value'=>'hd3', 'label'=>Mage::helper('milan')->__('Header 3')),
		array('value'=>'hd4', 'label'=>Mage::helper('milan')->__('Header 4'))
		);
	}
}
