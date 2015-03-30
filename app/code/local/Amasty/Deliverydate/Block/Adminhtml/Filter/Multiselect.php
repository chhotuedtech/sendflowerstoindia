<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Block_Adminhtml_Filter_Multiselect extends Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Select
{
    public function getCondition()
    {
        if (is_null($this->getValue())) {
            return null;
        }
        
        return array('or'=> array(
                 array('eq'   => $this->getValue()),
                 array('like' => '%,' . $this->getValue() . ''),
                 array('like' => '' . $this->getValue() . ',%'),
                 array('like' => '%,' . $this->getValue() . ',%')
               ));
    }
}