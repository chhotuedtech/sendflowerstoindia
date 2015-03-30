<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Block_Adminhtml_Renderer_Multiselect extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $value = $row->getData($this->getColumn()->getIndex());
        
        if ($value) {
            $value = trim($value, ',');
            $value = explode(',', $value);
        } else {
            return '';
        }
        
        if (in_array(0, $value)) {
            return Mage::helper('amdeliverydate')->__('All Store Views');
        }
        
        $html = '';
        $data = Mage::getSingleton('adminhtml/system_store')->getStoresStructure(false, $value);
        foreach ($data as $website) {
            $html .= $website['label'] . '<br/>';
            foreach ($website['children'] as $group) {
                $html .= str_repeat('&nbsp;', 3) . $group['label'] . '<br/>';
                foreach ($group['children'] as $store) {
                    $html .= str_repeat('&nbsp;', 6) . $store['label'] . '<br/>';
                }
            }
        }
        return $html;
    }
}