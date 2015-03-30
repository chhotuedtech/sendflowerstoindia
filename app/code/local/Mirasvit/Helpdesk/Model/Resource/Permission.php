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


class Mirasvit_Helpdesk_Model_Resource_Permission extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('helpdesk/permission', 'permission_id');
    }

    public function loadDepartmentIds(Mage_Core_Model_Abstract $object)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('helpdesk/permission_department'))
            ->where('permission_id = ?', $object->getId());
        if ($data = $this->_getReadAdapter()->fetchAll($select)) {
            $array = array();
            foreach ($data as $row) {
                $array[] = (int)$row['department_id'];
            }
            $object->setData('department_ids', $array);
        }
        return $object;
    }

    protected function saveDepartmentIds($object)
    {
        $condition = $this->_getWriteAdapter()->quoteInto('permission_id = ?', $object->getId());
        $this->_getWriteAdapter()->delete($this->getTable('helpdesk/permission_department'), $condition);
        foreach ((array)$object->getData('department_ids') as $id) {

            $objArray = array(
                'permission_id'  => $object->getId(),
                'department_id' =>  $id? $id : new Zend_Db_Expr('NULL')
            );
            $this->_getWriteAdapter()->insert(
                $this->getTable('helpdesk/permission_department'), $objArray);

        }
    }

    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        if (!$object->getIsMassDelete()) {
            $this->loadDepartmentIds($object);
        }
        return parent::_afterLoad($object);
    }

    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        if (!$object->getId()) {
            $object->setCreatedAt(Mage::getSingleton('core/date')->gmtDate());
        }
        if (!$object->getRoleId()) {
            $object->setRoleId(new Zend_Db_Expr('NULL'));
        }
        $object->setUpdatedAt(Mage::getSingleton('core/date')->gmtDate());
        return parent::_beforeSave($object);
    }

    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        if (!$object->getIsMassStatus()) {
            $this->saveDepartmentIds($object);
        }
        return parent::_afterSave($object);
    }

    /************************/

}