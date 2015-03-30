<?php
/*------------------------------------------------------------------------
 # SM Zen - Version 1.0
 # Copyright (c) 2014 The YouTech Company. All Rights Reserved.
 # @license - Copyrighted Commercial Software
 # Author: YouTech Company
 # Websites: http://www.magentech.com
-------------------------------------------------------------------------*/

class Sm_Milan_Model_System_Config_Source_ListGoogleFont
{
	public function toOptionArray()
	{	
		return array(
			array('value'=>'', 'label'=>Mage::helper('milan')->__('No select')),
			array('value'=>'Dosis Regular', 'label'=>Mage::helper('milan')->__('Dosis Regular')),
			array('value'=>'Anton', 'label'=>Mage::helper('milan')->__('Anton')),
			array('value'=>'Questrial', 'label'=>Mage::helper('milan')->__('Questrial')),
			array('value'=>'Kameron', 'label'=>Mage::helper('milan')->__('Kameron')),
			array('value'=>'Oswald', 'label'=>Mage::helper('milan')->__('Oswald')),
			array('value'=>'Open Sans', 'label'=>Mage::helper('milan')->__('Open Sans')),
			array('value'=>'BenchNine', 'label'=>Mage::helper('milan')->__('BenchNine')),
			array('value'=>'Droid Sans', 'label'=>Mage::helper('milan')->__('Droid Sans')),
			array('value'=>'Droid Serif', 'label'=>Mage::helper('milan')->__('Droid Serif')),
			array('value'=>'PT Sans', 'label'=>Mage::helper('milan')->__('PT Sans')),
			array('value'=>'Vollkorn', 'label'=>Mage::helper('milan')->__('Vollkorn')),
			array('value'=>'Ubuntu', 'label'=>Mage::helper('milan')->__('Ubuntu')),
			array('value'=>'Neucha', 'label'=>Mage::helper('milan')->__('Neucha')),
			array('value'=>'Cuprum', 'label'=>Mage::helper('milan')->__('Cuprum'))	
		);
	}
}
