<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Block_Adminhtml_Sales_Order_Grid_Renderer_Date extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Date
{
public function render(Varien_Object $row)
    {
        if ($data = $this->_getValue($row)) {
            if ($data === '0000-00-00' ||
                $data === '0000-00-00 00:00:00') {
                return '';
            }
            
            $data = date(Mage::helper('amdeliverydate')->getPhpFormat(), strtotime($data));
            
            return $data;
        }
        return $this->getColumn()->getDefault();
    }
}
