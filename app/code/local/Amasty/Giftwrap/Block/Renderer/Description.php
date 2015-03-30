<?php

/**
 * @author    Amasty
 * @copyright Amasty
 * @package   Amasty_Customerattr
 */
class Amasty_Giftwrap_Block_Renderer_Description extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $desc = $row->getData($this->getColumn()->getIndex());
        $desc = Mage::helper('core/string')->truncate($desc, 450);

        return $desc;
    }
}