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


class Mirasvit_Helpdesk_Model_Resource_Field extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('helpdesk/field', 'field_id');
    }

    protected function loadStoreIds(Mage_Core_Model_Abstract $object)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('helpdesk/field_store'))
            ->where('fs_field_id = ?', $object->getId());
        if ($data = $this->_getReadAdapter()->fetchAll($select)) {
            $array = array();
            foreach ($data as $row) {
                $array[] = $row['fs_store_id'];
            }
            $object->setData('store_ids', $array);
        }
        return $object;
    }

    protected function saveStoreIds($object)
    {
        $condition = $this->_getWriteAdapter()->quoteInto('fs_field_id = ?', $object->getId());
        $this->_getWriteAdapter()->delete($this->getTable('helpdesk/field_store'), $condition);
        foreach ((array)$object->getData('store_ids') as $id) {
            $objArray = array(
                'fs_field_id'  => $object->getId(),
                'fs_store_id' => $id
            );
            $this->_getWriteAdapter()->insert(
                $this->getTable('helpdesk/field_store'), $objArray);
        }
    }

    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        if (!$object->getIsMassDelete()) {
            $this->loadStoreIds($object);
        }
        return parent::_afterLoad($object);
    }

    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        if (!$object->getId()) {
            $object->setCreatedAt(Mage::getSingleton('core/date')->gmtDate());
            $object->setCode($this->normalize($object->getCode()));
            if (in_array($object->getCode(), array('name', 'code', 'external_id', 'user_id', 'description', 'customer_email', 'customer_name', 'order_id', 'last_reply_at'))) {
                throw new Exception("Code {$object->getCode()} is not allowed. Please, try different code");
            }
        }
        $object->setUpdatedAt(Mage::getSingleton('core/date')->gmtDate());
        return parent::_beforeSave($object);
    }

    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        if (!$object->getIsMassStatus()) {
            $this->saveStoreIds($object);
        }
        return parent::_afterSave($object);
    }

    public function normalize($string)
    {
        $string = Mage::getSingleton('catalog/product_url')->formatUrlKey($string);
        $string = str_replace('-', '_', $string);
        return 'f_'.$string;
    }
}