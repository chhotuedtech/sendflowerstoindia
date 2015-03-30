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


class Mirasvit_Helpdesk_Block_Adminhtml_Ticket_Edit_Tab_Followup extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form  = new Varien_Data_Form();
        $this->setForm($form);
        $ticket = Mage::registry('current_ticket');

        $fieldset = $form->addFieldset('followup_fieldset', array('legend'=> Mage::helper('helpdesk')->__('Follow Up')));
        if ($ticket->getId()) {
            $fieldset->addField('ticket_id', 'hidden', array(
                'name'      => 'ticket_id',
                'value'     => $ticket->getId(),
            ));
        }
        $fieldset->addField('fp_period_unit', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Period'),
            'name'      => 'fp_period_unit',
            'value'     => $ticket->getFpPeriodUnit(),
            'values'    => Mage::getSingleton('helpdesk/config_source_followupperiod')->toOptionArray()
        ));
        $fieldset->addField('fp_period_value', 'text', array(
            'label'     => Mage::helper('helpdesk')->__(''),
            'name'      => 'fp_period_value',
            'value'     => $ticket->getFpPeriodValue()?$ticket->getFpPeriodValue():'',
        ));
        $fieldset->addField('fp_execute_at', 'date', array(
            'label'     => Mage::helper('helpdesk')->__(''),
            'name'      => 'fp_execute_at',
            'value'     => $ticket->getFpExecuteAt(),
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            'format'    => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
        ));
        $fieldset->addField('fp_is_remind_hidden', 'hidden', array(
            'name'      => 'fp_is_remind',
            'value'     => 0,
        ));
        $fieldset->addField('fp_is_remind', 'checkbox', array(
            'label'     => Mage::helper('helpdesk')->__('Send Remind'),
            'name'      => 'fp_is_remind',
            'value'     => 1,
            'checked'   => $ticket->getFpIsRemind(),
        ));
        $email = $ticket->getFpRemindEmail();
        if (!$ticket->getFpIsRemind()) {
            $email = Mage::getSingleton('admin/session')->getUser()->getEmail();
        }
        $fieldset->addField('fp_remind_email', 'text', array(
            'label'     => Mage::helper('helpdesk')->__(''),
            'name'      => 'fp_remind_email',
            'value'     => $email,
            'note'      => 'Emails to send reminder (comma-separated).',
        ));
        $fieldset->addField('fp_status_id', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Set Status To'),
            'name'      => 'fp_status_id',
            'value'     => $ticket->getFpStatusId(),
            'values'    => Mage::getModel('helpdesk/status')->getCollection()->toOptionArray(true)
        ));
        $fieldset->addField('fp_priority_id', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Set Priority To'),
            'name'      => 'fp_priority_id',
            'value'     => $ticket->getFpPriorityId(),
            'values'    => Mage::getModel('helpdesk/priority')->getCollection()->toOptionArray(true)
        ));
        $fieldset->addField('fp_owner', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Set Owner To'),
            'name'      => 'fp_owner',
            'value'     => $ticket->getFpDepartmentId().'_'.$ticket->getFpUserId(),
            'values'    => Mage::helper('helpdesk')->getAdminOwnerOptionArray(true)
        ));
        return parent::_prepareForm();
    }
    /************************/

}