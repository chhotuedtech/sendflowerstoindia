<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/  
class Amasty_Deliverydate_Model_Mysql4_Dinterval extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('amdeliverydate/dinterval', 'dinterval_id');
    }
}