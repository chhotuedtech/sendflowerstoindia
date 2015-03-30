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


class Mirasvit_Helpdesk_Block_Adminhtml_Department_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $department = Mage::registry('current_department');
        $form  = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('edit_fieldset', array('legend'=> Mage::helper('helpdesk')->__('General Information')));
        if ($department->getId()) {
            $fieldset->addField('department_id', 'hidden', array(
                'name'      => 'department_id',
                'value'     => $department->getId(),
            ));
        }
        $fieldset->addField('name', 'text', array(
            'label'     => Mage::helper('helpdesk')->__('Title'),
            'name'      => 'name',
            'value'     => $department->getName(),
        ));
        $fieldset->addField('is_active', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Is Active'),
            'name'      => 'is_active',
            'value'     => $department->getIsActive(),
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
        ));
        $fieldset->addField('sort_order', 'text', array(
            'label'     => Mage::helper('helpdesk')->__('Sort Order'),
            'name'      => 'sort_order',
            'value'     => $department->getSortOrder(),
        ));
        $fieldset->addField('gateway_ids', 'multiselect', array(
            'label'     => Mage::helper('helpdesk')->__('Auto assign tickets from gateways'),
            'name'      => 'gateway_ids[]',
            'value'     => $department->getGatewayIds(),
            'values'    => Mage::getModel('helpdesk/gateway')->getCollection()->toOptionArray()
        ));
        $fieldset->addField('sender_email', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Sender Email'),
            'name'      => 'sender_email',
            'value'     => $department->getSenderEmail(),
            'values'    => Mage::getSingleton('adminhtml/system_config_source_email_identity')->toOptionArray()
        ));
        $fieldset->addField('user_ids', 'multiselect', array(
            'label'     => Mage::helper('helpdesk')->__('Departments Members'),
            'name'      => 'user_ids[]',
            'value'     => $department->getUserIds(),
            'values'    => Mage::helper('helpdesk')->toAdminUserOptionArray()
        ));
        $fieldset->addField('signature', 'textarea', array(
            'label'     => Mage::helper('helpdesk')->__('Signature'),
            'name'      => 'signature',
            'value'     => $department->getSignature(),
        ));
        $fieldset = $form->addFieldset('notification_fieldset', array('legend'=> Mage::helper('helpdesk')->__('Notification')));
        $fieldset->addField('is_notification_enabled', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Is Email Notification Active'),
            'name'      => 'is_notification_enabled',
            'value'     => $department->getIsNotificationEnabled(),
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
        ));
        $fieldset->addField('notification_email', 'text', array(
            'label'     => Mage::helper('helpdesk')->__('Notification Email'),
            'name'      => 'notification_email',
            'value'     => $department->getNotificationEmail(),
        ));
        return parent::_prepareForm();
    }
}
