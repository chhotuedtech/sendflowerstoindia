<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Block_Adminhtml_Tinterval_Generate extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId   = 'generate'; 
        $this->_blockGroup = 'amdeliverydate';
        $this->_controller = 'adminhtml_tinterval';
        $this->_mode = 'generate';
        
        parent::__construct();
        
        $this->_updateButton('save', 'label', Mage::helper('amdeliverydate')->__('Generate'));
    }

    public function getHeaderText()
    {
        return Mage::helper('amdeliverydate')->__('Generate Time Intervals');
    }
}