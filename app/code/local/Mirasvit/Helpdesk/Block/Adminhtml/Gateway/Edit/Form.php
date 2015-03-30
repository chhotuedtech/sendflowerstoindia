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


class Mirasvit_Helpdesk_Block_Adminhtml_Gateway_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm() {
        $form = new Varien_Data_Form(
            array(
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
                'method' => 'post',
                'enctype' => 'multipart/form-data'
            )
        );

        $gateway = Mage::registry('current_gateway');

        $fieldset = $form->addFieldset('edit_fieldset', array('legend'=> Mage::helper('helpdesk')->__('General Information')));
        if ($gateway->getId()) {
            $fieldset->addField('gateway_id', 'hidden', array(
                'name'      => 'gateway_id',
                'value'     => $gateway->getId(),
            ));
        }
        $fieldset->addField('name', 'text', array(
            'label'     => Mage::helper('helpdesk')->__('Title'),
            'name'      => 'name',
            'value'     => $gateway->getName(),
        ));
        $fieldset->addField('email', 'text', array(
            'label'     => Mage::helper('helpdesk')->__('Email'),
            'name'      => 'email',
            'value'     => $gateway->getEmail(),
        ));
        $fieldset->addField('login', 'text', array(
            'label'     => Mage::helper('helpdesk')->__('Login'),
            'name'      => 'login',
            'value'     => $gateway->getLogin(),
        ));
        $fieldset->addField('password', 'password', array(
            'label'     => Mage::helper('helpdesk')->__('Password'),
            'name'      => 'password',
            'value'     => '*****',

        ));
        $fieldset->addField('is_active', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Is Active'),
            'name'      => 'is_active',
            'value'     => $gateway->getIsActive(),
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()

        ));
        $fieldset->addField('host', 'text', array(
            'label'     => Mage::helper('helpdesk')->__('Host'),
            'name'      => 'host',
            'value'     => $gateway->getHost(),
        ));
        $fieldset->addField('protocol', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Prolocol'),
            'name'      => 'protocol',
            'value'     => $gateway->getProtocol(),
            'values'    => Mage::getSingleton('helpdesk/config_source_protocol')->toOptionArray()

        ));
        $fieldset->addField('encryption', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Encryption'),
            'name'      => 'encryption',
            'value'     => $gateway->getEncryption(),
            'values'    => Mage::getSingleton('helpdesk/config_source_encryption')->toOptionArray()

        ));
        $fieldset->addField('port', 'text', array(
            'label'     => Mage::helper('helpdesk')->__('Port'),
            'name'      => 'port',
            'value'     => $gateway->getPort(),
        ));
        $fieldset->addField('fetch_frequency', 'text', array(
            'label'     => Mage::helper('helpdesk')->__('Fetch Frequency (minutes)'),
            'name'      => 'fetch_frequency',
            'value'     => $gateway->getFetchFrequency()?$gateway->getFetchFrequency():5,
        ));
        $fieldset->addField('fetch_max', 'text', array(
            'label'     => Mage::helper('helpdesk')->__('Fetch Max'),
            'name'      => 'fetch_max',
            'value'     => $gateway->getFetchMax()?$gateway->getFetchMax():10,
        ));
        $fieldset->addField('fetch_limit', 'text', array(
            'label'     => Mage::helper('helpdesk')->__('Fetch Only X Last Emails'),
            'name'      => 'fetch_limit',
            'value'     => $gateway->getFetchLimit()?$gateway->getFetchLimit():'',
            'note'      => Mage::helper('helpdesk')->__('Can be useful for some mailboxes. Leave empty to disable this feature.'),
        ));
        $fieldset->addField('is_delete_emails', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Remove emails after fetching'),
            'name'      => 'is_delete_emails',
            'value'     => $gateway->getIsDeleteEmails(),
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
        ));
        if (!Mage::app()->isSingleStoreMode()) {
            $field = $fieldset->addField('store_id', 'select', array(
                'name'      => 'store_id',
                'label'     => Mage::helper('helpdesk')->__('Auto assign tickets to Store View'),
                'title'     => Mage::helper('helpdesk')->__('Auto assign tickets to Store View'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, false),
                'value'     => $gateway->getStoreId()
            ));
        } else {
            $fieldset->addField('store_id', 'hidden', array(
                'name'      => 'store_id',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
            $gateway->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $fieldset->addField('department_id', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Auto assign tickets to department'),
            'name'      => 'department_id',
            'value'     => $gateway->getDepartmentId(),
            'values'    => Mage::getModel('helpdesk/department')->getCollection()->toOptionArray()

        ));
        $fieldset->addField('notes', 'textarea', array(
            'label'     => Mage::helper('helpdesk')->__('Notes'),
            'name'      => 'notes',
            'value'     => $gateway->getNotes(),
        ));

        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
    /************************/

}