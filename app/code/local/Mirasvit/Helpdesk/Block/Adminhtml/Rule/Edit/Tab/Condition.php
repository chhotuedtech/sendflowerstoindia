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


class Mirasvit_Helpdesk_Block_Adminhtml_Rule_Edit_Tab_Condition extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form  = new Varien_Data_Form();
        $this->setForm($form);
        $rule = Mage::registry('current_rule');

        $fieldset = $form->addFieldset('event_fieldset', array('legend'=> Mage::helper('helpdesk')->__('Event')));
        if ($rule->getId()) {
            $fieldset->addField('rule_id', 'hidden', array(
                'name'      => 'rule_id',
                'value'     => $rule->getId(),
            ));
        }
        $fieldset->addField('event', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Event'),
            'required'  => true,
            'name'      => 'event',
            'value'     => $rule->getEvent(),
            'values'    => Mage::getSingleton('helpdesk/config_source_rule_event')->toOptionArray()
        ));
        $fieldset = $form->addFieldset('condition_fieldset', array('legend'=> Mage::helper('helpdesk')->__('Conditions')));
        $renderer = Mage::getBlockSingleton('adminhtml/widget_form_renderer_fieldset')
            ->setTemplate('promo/fieldset.phtml')
            ->setNewChildUrl(Mage::getModel('adminhtml/url')->getUrl('*/adminhtml_rule/newConditionHtml/form/rule_conditions_fieldset', array('rule_type' => $rule->getType())));
        $fieldset->setRenderer($renderer);

        $fieldset->addField('condition', 'text', array(
            'name'     => 'condition',
            'label'     => Mage::helper('helpdesk')->__('Filters'),
            'title'     => Mage::helper('helpdesk')->__('Filters'),
            'required' => true,
        ))->setRule($rule)
            ->setRenderer(Mage::getBlockSingleton('rule/conditions'));
        return parent::_prepareForm();
    }

    /************************/

}