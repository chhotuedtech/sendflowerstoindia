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


class Mirasvit_Helpdesk_Block_Adminhtml_Department_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm() {
        $form = new Varien_Data_Form(
            array(
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'),'store' => (int)$this->getRequest()->getParam('store'))),
                'method' => 'post',
                'enctype' => 'multipart/form-data'
            )
        );

        $department = Mage::registry('current_department');

        $fieldset = $form->addFieldset('edit_fieldset', array('legend'=> Mage::helper('helpdesk')->__('General Information')));
        if ($department->getId()) {
            $fieldset->addField('department_id', 'hidden', array(
                'name'      => 'department_id',
                'value'     => $department->getId(),
            ));
        }
        $fieldset->addField('store_id', 'hidden', array(
            'name'  => 'store_id',
            'value' => (int)$this->getRequest()->getParam('store')
        ));

        $fieldset->addField('name', 'text', array(
            'label'     => Mage::helper('helpdesk')->__('Title'),
            'name'      => 'name',
            'value'     => $department->getName(),
            'after_element_html' => ' [STORE VIEW]',
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
        $fieldset->addField('sender_email', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Sender Email'),
            'name'      => 'sender_email',
            'value'     => $department->getSenderEmail(),
            'values'    => Mage::getSingleton('adminhtml/system_config_source_email_identity')->toOptionArray()

        ));
        $fieldset->addField('user_ids', 'multiselect', array(
            'label'     => Mage::helper('helpdesk')->__('Members of Department'),
            'required'  => true,
            'name'      => 'user_ids[]',
            'value'     => $department->getUserIds(),
            'values'    => Mage::helper('helpdesk')->toAdminUserOptionArray()

        ));
        // $fieldset->addField('signature', 'textarea', array(
        //     'label'     => Mage::helper('helpdesk')->__('Signature'),
        //     'name'      => 'signature',
        //     'value'     => $department->getSignature(),
        // ));
        $fieldset = $form->addFieldset('notification_fieldset', array('legend'=> Mage::helper('helpdesk')->__('Notification')));
        $fieldset->addField('is_members_notification_enabled', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('If ticket is unassigned, send notifications to all department members'),
            'name'      => 'is_members_notification_enabled',
            'value'     => $department->getIsMembersNotificationEnabled(),
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()

        ));
        $fieldset->addField('notification_email', 'text', array(
            'label'     => Mage::helper('helpdesk')->__('If ticket is unassigned, send notifications to email'),
            'name'      => 'notification_email',
            'value'     => $department->getNotificationEmail(),
        ));

        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
    /************************/

}