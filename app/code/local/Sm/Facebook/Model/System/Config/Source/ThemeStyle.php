<?php
/*------------------------------------------------------------------------
 # SM Facebook - Version 1.0.0
 # Copyright (c) 2014 YouTech Company. All Rights Reserved.
 # @license - Copyrighted Commercial Software
 # Author: YouTech Company
 # Websites: http://www.magentech.com
-------------------------------------------------------------------------*/

class Sm_Facebook_Model_System_Config_Source_ThemeStyle
{
	public function toOptionArray()
	{
		return array(
			array('value' =>	'light',    'label' => Mage::helper('facebook')->__('Light')),
			array('value' =>	'dark',		'label' => Mage::helper('facebook')->__('Dark')),
		);
	}
}
