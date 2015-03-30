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


class Mirasvit_Helpdesk_Model_Resource_Message_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{

    protected function _construct()
    {
        $this->_init('helpdesk/message');
    }

    public function toOptionArray($emptyOption = false)
    {
        $arr = array();
        if ($emptyOption) {
            $arr[0] = array('value' => 0, 'label' => Mage::helper('helpdesk')->__('-- Please Select --'));
        }
        foreach ($this as $item) {
            $arr[] = array('value' => $item->getId(), 'label' => $item->getName());
        }
        return $arr;
    }

    public function getOptionArray($emptyOption = false)
    {
        $arr = array();
        if ($emptyOption) {
            $arr[0] = Mage::helper('helpdesk')->__('-- Please Select --');
        }
        foreach ($this as $item) {
            $arr[$item->getId()] = $item->getName();
        }
        return $arr;
    }

    protected function joinFields()
    {
        $select = $this->getSelect();
        //$select->joinLeft(array('ticket' => $this->getTable('helpdesk/ticket')), 'main_table.ticket_id = ticket.ticket_id', array('ticket_name' => 'ticket.name'));
        $select->joinLeft(array('user' => $this->getTable('admin/user')), 'main_table.user_id = user.user_id', array('user_name' => 'CONCAT(firstname, " ", lastname)'));
        // $select->columns(array('is_replied' => new Zend_Db_Expr("answer <> ''")));
    }

    protected function _initSelect()
    {
        $this->joinFields();
        parent::_initSelect();
    }

}
