<?php

class Amasty_Giftwrap_Model_Mysql4_Design extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('amgiftwrap/design', 'design_id');
    }

    public function massDelete($ids)
    {
        $db = $this->_getWriteAdapter();

        $ids[] = 0;
        $cond = $db->quoteInto('design_id IN(?)', $ids);
        $db->delete($this->getMainTable(), $cond);

        return true;
    }

    public function massEnable($ids)
    {
        $db = $this->_getWriteAdapter();

        $ids[] = 0;
        $cond  = $db->quoteInto('design_id IN(?)', $ids);
        $db->update($this->getMainTable(), array('enabled' => '1'), $cond);

        return true;
    }

    public function massDisable($ids)
    {
        $db = $this->_getWriteAdapter();

        $ids[] = 0;
        $cond  = $db->quoteInto('design_id IN(?)', $ids);
        $db->update($this->getMainTable(), array('enabled' => '0'), $cond);

        return true;
    }
}