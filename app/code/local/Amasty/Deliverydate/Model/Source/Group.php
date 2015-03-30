<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (http://www.amasty.com)
 * @package Amasty_Deliverydate
 */
class Amasty_Deliverydate_Model_Source_Group
{
    public function toOptionArray()
    {
        return Mage::getResourceModel('customer/group_collection')->load()->toOptionArray();
    }
}
