<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Adminhtml_DintervalController extends Amasty_Deliverydate_Controller_Abstract
{
    public function preDispatch()
    {
        parent::preDispatch();
        $this->_tabs      =  true;
        $this->_modelName = 'dinterval';
        $this->_title     = 'Exceptions: Date Intervals';
        $this->_modelId   = 'dinterval_id';
    }
}
