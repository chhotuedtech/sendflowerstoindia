<?php
/*------------------------------------------------------------------------
 # SM Listing Tabs - Version 2.0.0
 # Copyright (c) 2014 YouTech Company. All Rights Reserved.
 # @license - Copyrighted Commercial Software
 # Author: YouTech Company
 # Websites: http://www.magentech.com
-------------------------------------------------------------------------*/
class Sm_Listingtabs_Model_System_Config_Source_ListFilterType
{
	public function toOptionArray()
	{
		return array(
		array('value'	=>		'categories',		    'label'=>Mage::helper('listingtabs')->__('Categories')),
		array('value'	=>		'fieldproducts',		'label'=>Mage::helper('listingtabs')->__('Field Products')),
		);
	}
}
