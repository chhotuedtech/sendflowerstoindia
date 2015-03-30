<?php

/**
 * @copyright  Amasty (http://www.amasty.com)
 */
class Amasty_Giftwrap_Model_Design extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('amgiftwrap/design');
    }

    public function massDelete($ids)
    {
        return $this->getResource()->massDelete($ids);
    }

    public function massEnable($ids)
    {
        return $this->getResource()->massEnable($ids);
    }

    public function massDisable($ids)
    {
        return $this->getResource()->massDisable($ids);
    }
}