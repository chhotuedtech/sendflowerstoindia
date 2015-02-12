<?php
/*------------------------------------------------------------------------
 # SM Facebook - Version 1.0.0
 # Copyright (c) 2014 YouTech Company. All Rights Reserved.
 # @license - Copyrighted Commercial Software
 # Author: YouTech Company
 # Websites: http://www.magentech.com
-------------------------------------------------------------------------*/

class Sm_Facebook_Model_System_Config_Source_FacebookType
{
	public function toOptionArray()
	{
		return array(
			array('value'	=>	1,		'label' => Mage::helper('facebook')->__('Activity Feed')),
			array('value'	=>	2,		'label' => Mage::helper('facebook')->__('Like Box')),
			array('value'	=>	3,		'label' => Mage::helper('facebook')->__('Recommendations')),
		);
	}
}
