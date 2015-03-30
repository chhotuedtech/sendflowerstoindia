<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Block_Adminhtml_Tinterval extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller     = 'adminhtml_tinterval';
        $this->_headerText     = Mage::helper('amdeliverydate')->__('Manage Time Intervals');
        $this->_blockGroup     = 'amdeliverydate';
        $this->_addButtonLabel = Mage::helper('amdeliverydate')->__('Add New Time Interval');
        parent::__construct();
        $this->_addButton('generate', array(
            'label'     => Mage::helper('amdeliverydate')->__('Generate Time Intervals'),
            'onclick'   => 'setLocation(\'' . $this->getUrl('*/*/setup') .'\')',
            'class'     => 'add',
        ));
    }
}