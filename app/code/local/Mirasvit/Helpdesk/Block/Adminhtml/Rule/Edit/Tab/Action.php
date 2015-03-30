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


class Mirasvit_Helpdesk_Block_Adminhtml_Rule_Edit_Tab_Action extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form  = new Varien_Data_Form();
        $this->setForm($form);
        $rule = Mage::registry('current_rule');

        $fieldset = $form->addFieldset('action_fieldset', array('legend'=> Mage::helper('helpdesk')->__('Actions')));
        if ($rule->getId()) {
            $fieldset->addField('rule_id', 'hidden', array(
                'name'      => 'rule_id',
                'value'     => $rule->getId(),
            ));
        }
        $fieldset->addField('status_id', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Set Status'),
            'name'      => 'status_id',
            'value'     => $rule->getStatusId(),
            'values'    => Mage::getModel('helpdesk/status')->getCollection()->toOptionArray(true)
        ));
        $fieldset->addField('priority_id', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Set Priority'),
            'name'      => 'priority_id',
            'value'     => $rule->getPriorityId(),
            'values'    => Mage::getModel('helpdesk/priority')->getCollection()->toOptionArray(true)
        ));
        $fieldset->addField('department_id', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Set Department'),
            'name'      => 'department_id',
            'value'     => $rule->getDepartmentId(),
            'values'    => Mage::getModel('helpdesk/department')->getCollection()->toOptionArray(true)
        ));
        $fieldset->addField('user_id', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Set Owner'),
            'name'      => 'user_id',
            'value'     => $rule->getUserId(),
            'values'    => Mage::helper('helpdesk')->toAdminUserOptionArray(true)
        ));
        $fieldset->addField('is_archive', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Archive'),
            'name'      => 'is_archive',
            'value'     => $rule->getIsArchive(),
            'values'    => Mage::getSingleton('helpdesk/config_source_is_archive')->toOptionArray(true)
        ));
        $fieldset->addField('add_tags', 'text', array(
            'label'     => Mage::helper('helpdesk')->__('Add Tags'),
            'name'      => 'add_tags',
            'value'     => $rule->getAddTags(),
            'note'      => Mage::helper('helpdesk')->__('comma-separated list'),
        ));
        $fieldset->addField('remove_tags', 'text', array(
            'label'     => Mage::helper('helpdesk')->__('Remove Tags'),
            'name'      => 'remove_tags',
            'value'     => $rule->getRemoveTags(),
            'note'      => Mage::helper('helpdesk')->__('comma-separated list'),
        ));
        return parent::_prepareForm();
    }

    /************************/

}