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


class Mirasvit_Helpdesk_Model_Config_Source_Ticket_Grid_Columns
{

    public function toArray()
    {
        $options = array(
            Mirasvit_Helpdesk_Model_Config::TICKET_GRID_COLUMNS_CODE => Mage::helper('helpdesk')->__('ID'),
            Mirasvit_Helpdesk_Model_Config::TICKET_GRID_COLUMNS_NAME => Mage::helper('helpdesk')->__('Subject'),
            Mirasvit_Helpdesk_Model_Config::TICKET_GRID_COLUMNS_CUSTOMER_NAME => Mage::helper('helpdesk')->__('Customer Name'),
            Mirasvit_Helpdesk_Model_Config::TICKET_GRID_COLUMNS_LAST_REPLY_NAME => Mage::helper('helpdesk')->__('Last Replier'),
            Mirasvit_Helpdesk_Model_Config::TICKET_GRID_COLUMNS_USER_ID => Mage::helper('helpdesk')->__('Owner'),
            Mirasvit_Helpdesk_Model_Config::TICKET_GRID_COLUMNS_DEPARTMENT_ID => Mage::helper('helpdesk')->__('Department'),
            Mirasvit_Helpdesk_Model_Config::TICKET_GRID_COLUMNS_STORE_ID => Mage::helper('helpdesk')->__('Store'),
            Mirasvit_Helpdesk_Model_Config::TICKET_GRID_COLUMNS_STATUS_ID => Mage::helper('helpdesk')->__('Status'),
            Mirasvit_Helpdesk_Model_Config::TICKET_GRID_COLUMNS_PRIORITY_ID => Mage::helper('helpdesk')->__('Priority'),
            Mirasvit_Helpdesk_Model_Config::TICKET_GRID_COLUMNS_REPLY_CNT => Mage::helper('helpdesk')->__('Replies'),
            Mirasvit_Helpdesk_Model_Config::TICKET_GRID_COLUMNS_CREATED_AT => Mage::helper('helpdesk')->__('Created At'),
            Mirasvit_Helpdesk_Model_Config::TICKET_GRID_COLUMNS_UPDATED_AT => Mage::helper('helpdesk')->__('Updated At'),
            Mirasvit_Helpdesk_Model_Config::TICKET_GRID_COLUMNS_LAST_REPLY_AT => Mage::helper('helpdesk')->__('Last Reply At'),
            Mirasvit_Helpdesk_Model_Config::TICKET_GRID_COLUMNS_LAST_ACTIVITY => Mage::helper('helpdesk')->__('Last Reply'),
            Mirasvit_Helpdesk_Model_Config::TICKET_GRID_COLUMNS_ACTION => Mage::helper('helpdesk')->__('View link'),
        );

        foreach (Mage::helper('helpdesk/field')->getStaffCollection() as $field) {
            $options[$field->getCode()] = $field->getName();
        }
        return $options;
    }
    public function toOptionArray()
    {
        $result = array();
        foreach($this->toArray() as $k=>$v) {
            $result[] = array('value'=>$k, 'label'=>$v);
        }
        $collection = Mage::helper('helpdesk/field')->getActiveCollection();
        foreach ($collection as $field) {
            $result[] = array('value'=>$field->getCode(), 'label'=>$field->getName());
        }
        return $result;
    }
}
