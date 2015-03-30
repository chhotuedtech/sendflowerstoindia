<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Block_Adminhtml_Holidays_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('holidaysTabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('amdeliverydate')->__('Holiday Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('main', array(
            'label'     => Mage::helper('amdeliverydate')->__('Properties'),
            'title'     => Mage::helper('amdeliverydate')->__('Properties'),
            'content'   => $this->getLayout()->createBlock('amdeliverydate/adminhtml_holidays_edit_tab_main')->toHtml(),
            'active'    => true
        ));

        $model = Mage::registry('amdeliverydate_holidays');
        
        return parent::_beforeToHtml();
    }
}