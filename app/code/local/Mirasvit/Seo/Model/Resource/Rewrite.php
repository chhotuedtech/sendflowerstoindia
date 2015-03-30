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
 * @package   Advanced SEO Suite
 * @version   1.1.1
 * @build     803
 * @copyright Copyright (C) 2014 Mirasvit (http://mirasvit.com/)
 */


class Mirasvit_Seo_Model_Resource_Rewrite extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('seo/rewrite', 'rewrite_id');
    }

    public function loadStore(Mage_Core_Model_Abstract $object)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('seo/rewrite_store'))
            ->where('rewrite_id = ?', $object->getId());

        if ($data = $this->_getReadAdapter()->fetchAll($select)) {
            $array = array();
            foreach ($data as $row) {
                $array[] = $row['store_id'];
            }
            $object->setData('store_id', $array);
        }
        return $object;
    }

    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        if (!$object->getIsMassDelete()) {
            $object = $this->loadStore($object);
        }

        return parent::_afterLoad($object);
    }

    /**
     * Call-back function
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        $url = $object->getUrl();
        $url = trim($url);
        $object->setUrl($url);
        return parent::_beforeSave($object);
    }

    /**
     * Call-back function
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        if (!$object->getIsMassStatus()) {
            $this->_saveToStoreTable($object);
        }

        return parent::_afterSave($object);
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @return Zend_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);
        $select->limit(1);

        return $select;
    }

    protected function _saveToStoreTable($object)
    {
        if (!$object->getData('stores')) {
            $condition = $this->_getWriteAdapter()->quoteInto('rewrite_id = ?', $object->getId());
            $this->_getWriteAdapter()->delete($this->getTable('seo/rewrite_store'), $condition);

            $storeArray = array(
                'rewrite_id'  => $object->getId(),
                'store_id' => '0'
            );
            $this->_getWriteAdapter()->insert(
                $this->getTable('seo/rewrite_store'), $storeArray);
            return true;
        }

        $condition = $this->_getWriteAdapter()->quoteInto('rewrite_id = ?', $object->getId());
        $this->_getWriteAdapter()->delete($this->getTable('seo/rewrite_store'), $condition);
        foreach ((array)$object->getData('stores') as $store) {
            $storeArray = array(
                'rewrite_id'  => $object->getId(),
                'store_id' => $store
            );
            $this->_getWriteAdapter()->insert(
                $this->getTable('seo/rewrite_store'), $storeArray);
        }
    }
}