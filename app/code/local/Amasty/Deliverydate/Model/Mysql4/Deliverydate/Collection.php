<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/ 
class Amasty_Deliverydate_Model_Mysql4_Deliverydate_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('amdeliverydate/deliverydate');
    }
    
    public function getOlderThan($start)
    {
        $this->getSelect()
            ->where('`date` <> \'0000-00-00\'')
            ->where('`date` >= ?', $start);
    }
}