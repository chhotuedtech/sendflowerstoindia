<?php

/**
 * @author    Amasty
 * @copyright Amasty
 * @package   Amasty_Customerattr
 */
class Amasty_Giftwrap_Block_Renderer_Enabled extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $hlp = Mage::helper('amgiftwrap');

        return $row->getData($this->getColumn()->getIndex()) ? $hlp->__('Yes') : $hlp->__('No');
    }
}