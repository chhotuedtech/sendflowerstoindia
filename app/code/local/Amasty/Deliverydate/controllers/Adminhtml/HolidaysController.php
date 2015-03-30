<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Adminhtml_HolidaysController extends Amasty_Deliverydate_Controller_Abstract
{
    public function preDispatch()
    {
        parent::preDispatch();
        $this->_tabs      =  true;
        $this->_modelName = 'holidays';
        $this->_title     = 'Exceptions: Dates and Holidays';
        $this->_modelId   = 'holiday_id';
    }
}
