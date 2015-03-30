<?php

/**
 * @author Amasty
 */
class Amasty_Giftwrap_Block_Adminhtml_Cards_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'amgiftwrap';
        $this->_controller = 'adminhtml_cards';
    }

    public function getHeaderText()
    {
        return Mage::helper('amgiftwrap')->__('Gift Wrap Cards');
    }
}