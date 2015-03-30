<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Block_Adminhtml_Tinterval_Generate_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('generateTabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('amdeliverydate')->__('Generation Setup'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('main', array(
            'label'     => Mage::helper('amdeliverydate')->__('Configuration'),
            'title'     => Mage::helper('amdeliverydate')->__('Configuration'),
            'content'   => $this->getLayout()->createBlock('amdeliverydate/adminhtml_tinterval_generate_tab_main')->toHtml(),
            'active'    => true
        ));

        return parent::_beforeToHtml();
    }
}