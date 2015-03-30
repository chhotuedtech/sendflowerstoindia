<?php
/*------------------------------------------------------------------------
 # SM Mega Menu - Version 1.1
 # Copyright (c) 2013 YouTech Company. All Rights Reserved.
 # @license - Copyrighted Commercial Software
 # Author: YouTech Company
 # Websites: http://www.magentech.com
-------------------------------------------------------------------------*/

class Sm_Megamenu_Model_Menugroup extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('megamenu/menugroup');
    }

    public function getGroupId(){
    	$collection = Mage::getModel('megamenu/menuitems')
    		->getCollection()
    		->addFieldToFilter('group_id', $this->getId())
    		->addFieldToFilter('lft', 1);
    	if ( $collection->getSize() ){
    		return $collection->getFirstItem()->getid();
    	}
    	return 0;
    }	
	
}