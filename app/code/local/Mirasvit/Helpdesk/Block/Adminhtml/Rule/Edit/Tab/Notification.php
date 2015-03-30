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


class Mirasvit_Helpdesk_Block_Adminhtml_Rule_Edit_Tab_Notification extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form  = new Varien_Data_Form();
        $this->setForm($form);
        $rule = Mage::registry('current_rule');

        $fieldset = $form->addFieldset('notification_fieldset', array('legend'=> Mage::helper('helpdesk')->__('Notifications')));
        if ($rule->getId()) {
            $fieldset->addField('rule_id', 'hidden', array(
                'name'      => 'rule_id',
                'value'     => $rule->getId(),
            ));
        }
        $fieldset->addField('is_send_owner', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Send email to ticket owner'),
            'name'      => 'is_send_owner',
            'value'     => $rule->getIsSendOwner(),
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
        ));
        $fieldset->addField('is_send_department', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Send email to all department users'),
            'name'      => 'is_send_department',
            'value'     => $rule->getIsSendDepartment(),
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
        ));
        $fieldset->addField('is_send_user', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Send email to customer'),
            'name'      => 'is_send_user',
            'value'     => $rule->getIsSendUser(),
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
        ));
        $fieldset->addField('other_email', 'text', array(
            'label'     => Mage::helper('helpdesk')->__('Send email to other email addresses'),
            'name'      => 'other_email',
            'value'     => $rule->getOtherEmail(),
        ));
        $fieldset->addField('email_subject', 'text', array(
            'label'     => Mage::helper('helpdesk')->__('Email subject'),
            'name'      => 'email_subject',
            'value'     => $rule->getEmailSubject(),
        ));
        $fieldset->addField('email_body', 'textarea', array(
            'label'     => Mage::helper('helpdesk')->__('Email body'),
            'name'      => 'email_body',
            'value'     => $rule->getEmailBody(),
            'style' => 'width:600px;height:400px;'
        ));
        $fieldset->addField('is_send_attachment', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Attach files which were attached to the last message'),
            'name'      => 'is_send_attachment',
            'value'     => $rule->getIsSendAttachment(),
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
        ));
        return parent::_prepareForm();
    }

    /************************/

}