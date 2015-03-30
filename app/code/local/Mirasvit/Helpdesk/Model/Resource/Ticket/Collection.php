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


class Mirasvit_Helpdesk_Model_Resource_Ticket_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{

    protected function _construct()
    {
        $this->_init('helpdesk/ticket');
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

    public function joinEmails()
    {
        $select = $this->getSelect();
        $select->joinLeft(array('email' => $this->getTable('helpdesk/email')), 'main_table.email_id = email.email_id', array('pattern_id'));
        return $this;
    }

    public function joinFields()
    {
        $select = $this->getSelect();
        $select->joinLeft(array('department' => $this->getTable('helpdesk/department')), 'main_table.department_id = department.department_id', array('department' => 'name'));
        $select->joinLeft(array('status' => $this->getTable('helpdesk/status')), 'main_table.status_id = status.status_id', array('status' => 'name'));
        $select->joinLeft(array('priority' => $this->getTable('helpdesk/priority')), 'main_table.priority_id = priority.priority_id', array('priority' => 'name'));
        $select->joinLeft(array('user' => $this->getTable('admin/user')), 'main_table.user_id = user.user_id', array('user_name' => 'CONCAT(firstname, " ", lastname)'));
        return $this;
    }

    public function joinMessages()
    {
        $select = $this->getSelect();
        $select->joinLeft(array('message' => $this->getTable('helpdesk/message')), 'main_table.ticket_id = message.ticket_id', array('message_body' => 'group_concat(message.body)'))
            ->group('main_table.ticket_id');

        return $this;
    }


    public function joinColors()
    {
        $select = $this->getSelect();
        $select->joinLeft(array('status' => $this->getTable('helpdesk/status')), 'main_table.status_id = status.status_id', array('status_color' => 'color'));
        $select->joinLeft(array('priority' => $this->getTable('helpdesk/priority')), 'main_table.priority_id = priority.priority_id', array('priority_color' => 'color'));
        $select->joinLeft(array('department' => $this->getTable('helpdesk/department')), 'main_table.department_id = department.department_id', array());
        return $this;
    }

    public function addTagFilter($tagId)
    {
        $this->getSelect()
            ->where("EXISTS (SELECT * FROM `{$this->getTable('helpdesk/ticket_tag')}`
                AS `ticket_tag_table`
                WHERE main_table.ticket_id = ticket_tag_table.tt_ticket_id
                AND ticket_tag_table.tt_tag_id in (?))", array(0, $tagId));
        return $this;
    }

    protected function initFields()
    {
        $select = $this->getSelect();
        //$select->joinLeft(array('department' => $this->getTable('helpdesk/department')), 'main_table.department_id = department.department_id', array('department_name' => 'department.name'));
        //$select->joinLeft(array('priority' => $this->getTable('helpdesk/priority')), 'main_table.priority_id = priority.priority_id', array('priority_name' => 'priority.name'));
        //$select->joinLeft(array('status' => $this->getTable('helpdesk/status')), 'main_table.status_id = status.status_id', array('status_name' => 'status.name'));
        //$select->joinLeft(array('user' => $this->getTable('admin/user')), 'main_table.user_id = user.user_id', array('user_name' => 'user.name'));
        // $select->columns(array('is_replied' => new Zend_Db_Expr("answer <> ''")));
    }

    protected function _initSelect()
    {
        parent::_initSelect();
        $this->initFields();
    }

     /************************/

    public function getSearchInstance()
    {
        $collection = Mage::getModel('helpdesk/ticket')->getCollection()
            ->joinEmails()
            ->joinFields()
            ->joinMessages();

        $search = Mage::getModel('helpdesk/search');
        $search->setSearchableCollection($collection);
        $attributes = array(
            'main_table.ticket_id'       => 0,
            'main_table.description'     => 0,
            'main_table.name'            => 100,
            'main_table.code'            => 0,
            'main_table.order_id'        => 0,
            'main_table.last_reply_name' => 0,
            'main_table.search_index'    => 0,
            'user_name'                  => 0,
            'message_body'               => 0,
            //customer name
            //customer_email
            //order_id
            'department.name'            => 0,
            'status.name'                => 0,
            'priority.name'              => 0,
        );
        foreach (Mage::helper('helpdesk/field')->getStaffCollection() as $field) {
            if ($field->getType() == 'text' || $field->getType() == 'textarea' ) {
                $attributes['main_table.'.$field->getCode()] = 0;
            }
        }
        $search->setSearchableAttributes($attributes);
        $search->setPrimaryKey('ticket_id');

        return $search;
    }
}