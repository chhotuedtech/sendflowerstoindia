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


class Mirasvit_Helpdesk_Model_Config_Source_Rule_Event
{

    public function toArray()
    {
        return array(
            Mirasvit_Helpdesk_Model_Config::RULE_EVENT_NEW_TICKET => Mage::helper('helpdesk')->__('New ticket created'),
            Mirasvit_Helpdesk_Model_Config::RULE_EVENT_NEW_CUSTOMER_REPLY => Mage::helper('helpdesk')->__('New reply from customer'),
            Mirasvit_Helpdesk_Model_Config::RULE_EVENT_NEW_STAFF_REPLY => Mage::helper('helpdesk')->__('New reply from staff'),
            Mirasvit_Helpdesk_Model_Config::RULE_EVENT_NEW_THIRD_REPLY => Mage::helper('helpdesk')->__('New reply from third party'),
            Mirasvit_Helpdesk_Model_Config::RULE_EVENT_TICKET_ASSIGNED => Mage::helper('helpdesk')->__('Ticket assigned to staff'),
            Mirasvit_Helpdesk_Model_Config::RULE_EVENT_TICKET_UPDATED => Mage::helper('helpdesk')->__('Ticket was changed'),
            Mirasvit_Helpdesk_Model_Config::RULE_EVENT_CRON_EVERY_HOUR => Mage::helper('helpdesk')->__('Check every hour'),
        );
    }
    public function toOptionArray()
    {
        $result = array();
        foreach($this->toArray() as $k=>$v) {
            $result[] = array('value'=>$k, 'label'=>$v);
        }
        return $result;
    }

    /************************/
}