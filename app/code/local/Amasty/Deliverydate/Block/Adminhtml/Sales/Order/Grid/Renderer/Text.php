<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Block_Adminhtml_Sales_Order_Grid_Renderer_Text extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
{
    public function render(Varien_Object $row)
    {
        if ($data = $this->_getValue($row)) {
            return nl2br($data);
        }
        return $this->getColumn()->getDefault();
    }
}