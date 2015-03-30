<?php

/**
 * @copyright  Amasty (http://www.amasty.com)
 */
class Amasty_Giftwrap_Block_Adminhtml_Design extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_design';
        $this->_blockGroup = 'amgiftwrap';
        $this->_headerText = Mage::helper('amgiftwrap')->__('Gift Wrap Design');
        parent::__construct();
    }
}