<?php

/**
 * @copyright  Amasty (http://www.amasty.com)
 */
class Amasty_Giftwrap_Model_Mysql4_Design_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('amgiftwrap/design');
    }
}