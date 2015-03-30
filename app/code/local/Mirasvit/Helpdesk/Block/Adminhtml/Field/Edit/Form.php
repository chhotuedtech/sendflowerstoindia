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


class Mirasvit_Helpdesk_Block_Adminhtml_Field_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
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

        $field = Mage::registry('current_field');

        $fieldset = $form->addFieldset('edit_fieldset', array('legend'=> Mage::helper('helpdesk')->__('General Information')));
        if ($field->getId()) {
            $fieldset->addField('field_id', 'hidden', array(
                'name'      => 'field_id',
                'value'     => $field->getId(),
            ));
        }
        $fieldset->addField('store_id', 'hidden', array(
            'name'  => 'store_id',
            'value' => (int)$this->getRequest()->getParam('store')
        ));

        $fieldset->addField('name', 'text', array(
            'label'     => Mage::helper('helpdesk')->__('Title'),
            'required'  => true,
            'name'      => 'name',
            'value'     => $field->getName(),
            'after_element_html' => ' [STORE VIEW]',
        ));
        $fieldset->addField('code', 'text', array(
            'label'     => Mage::helper('helpdesk')->__('Code'),
            'required'  => true,
            'name'      => 'code',
            'value'     => $field->getCode(),
            'note'      => 'Internal field. Can contain only letters, digits and underscore.',
            'disabled'  => $field->getId(), '', 'disabled'
        ));
        $fieldset->addField('type', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Type'),
            'required'  => true,
            'name'      => 'type',
            'value'     => $field->getType(),
            'values'    => Mage::getSingleton('helpdesk/config_source_field_type')->toOptionArray()
        ));
        $fieldset->addField('description', 'textarea', array(
            'label'     => Mage::helper('helpdesk')->__('Description'),
            'name'      => 'description',
            'value'     => $field->getDescription(),
            'after_element_html' => ' [STORE VIEW]',
        ));
        $fieldset->addField('values', 'textarea', array(
            'label'     => Mage::helper('helpdesk')->__('Options list'),
            'name'      => 'values',
            'value'     => Mage::helper('helpdesk/storeview')->getStoreViewValue($field, 'values'),
            'note'      => Mage::helper('helpdesk')->__('Only for drop-down list. <br>Enter each value from the new line using format: <br>value1 | label1<br>value2 | label2'),
            'after_element_html' => ' [STORE VIEW]',
        ));
        $fieldset->addField('is_active', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Active'),
            'name'      => 'is_active',
            'value'     => $field->getIsActive(),
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
        ));
        $fieldset->addField('sort_order', 'text', array(
            'label'     => Mage::helper('helpdesk')->__('Sort Order'),
            'name'      => 'sort_order',
            'value'     => $field->getSortOrder(),
        ));
        $fieldset->addField('is_visible_customer', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Show value in customer account'),
            'name'      => 'is_visible_customer',
            'value'     => $field->getIsVisibleCustomer(),
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
        ));
        $fieldset->addField('is_editable_customer', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Show in create ticket form'),
            'name'      => 'is_editable_customer',
            'value'     => $field->getIsEditableCustomer(),
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
        ));
        $fieldset->addField('is_visible_contact_form', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Show in contact us form'),
            'name'      => 'is_visible_contact_form',
            'value'     => $field->getIsVisibleContactForm(),
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
        ));
        $fieldset->addField('is_required_customer', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Required for customers'),
            'name'      => 'is_required_customer',
            'value'     => $field->getIsRequiredCustomer(),
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
        ));
        $fieldset->addField('is_required_staff', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Required for staff'),
            'name'      => 'is_required_staff',
            'value'     => $field->getIsRequiredStaff(),
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
        ));
        $fieldset->addField('store_ids', 'multiselect', array(
            'label'     => Mage::helper('helpdesk')->__('Stores'),
            'required'  => true,
            'name'      => 'store_ids[]',
            'value'     => $field->getStoreIds(),
            'values'    => Mage::getModel('core/store')->getCollection()->toOptionArray()
        ));

        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
    /************************/

}