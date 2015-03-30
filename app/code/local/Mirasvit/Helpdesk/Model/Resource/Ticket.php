<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at http://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   Help Desk MX
 * @version   1.0.7
 * @build     907
 * @copyright Copyright (C) 2014 Mirasvit (http://mirasvit.com/)
 */


class Mirasvit_Helpdesk_Model_Resource_Ticket extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('helpdesk/ticket', 'ticket_id');
    }

    public function loadTagIds(Mage_Core_Model_Abstract $object)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('helpdesk/ticket_tag'))
            ->where('tt_ticket_id = ?', $object->getId());
        $array = array();
        if ($data = $this->_getReadAdapter()->fetchAll($select)) {
            foreach ($data as $row) {
                $array[] = $row['tt_tag_id'];
            }
        }
        $object->setData('tag_ids', $array);
        return $object;
    }

    protected function saveTagIds($object)
    {
        $condition = $this->_getWriteAdapter()->quoteInto('tt_ticket_id = ?', $object->getId());
        $this->_getWriteAdapter()->delete($this->getTable('helpdesk/ticket_tag'), $condition);
        foreach ((array)$object->getData('tag_ids') as $id) {
            $objArray = array(
                'tt_ticket_id'  => $object->getId(),
                'tt_tag_id' => $id
            );
            $this->_getWriteAdapter()->insert(
                $this->getTable('helpdesk/ticket_tag'), $objArray);
        }
    }

    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        if (!$object->getIsMassDelete()) {
            $this->loadTagIds($object);
        }
        if (is_string($object->getChannelData())) {
            $object->setChannelData(@unserialize($object->getChannelData()));
        }
        return parent::_afterLoad($object);
    }

    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        if (!$object->getId()) {
            $object->setCreatedAt(Mage::getSingleton('core/date')->gmtDate());
        }
        if (is_array($object->getChannelData())) {
            $object->setChannelData(serialize($object->getChannelData()));
        }
        $object->setUpdatedAt(Mage::getSingleton('core/date')->gmtDate());

        $tags = array();
        foreach ($object->getTags() as $tag) {
            $tags[] = $tag->getName();
        }
        $object->setSearchIndex(implode(',', $tags));

        return parent::_beforeSave($object);
    }

    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        if (!$object->getIsMassStatus()) {
            $this->saveTagIds($object);
        }
        return parent::_afterSave($object);
    }

    /************************/

}

