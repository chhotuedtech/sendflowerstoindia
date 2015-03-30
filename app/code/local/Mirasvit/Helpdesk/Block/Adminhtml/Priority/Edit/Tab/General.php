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


class Mirasvit_Helpdesk_Block_Adminhtml_Priority_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form  = new Varien_Data_Form();
        $this->setForm($form);
        $priority = Mage::registry('current_priority');

        $fieldset = $form->addFieldset('edit_fieldset', array('legend'=> Mage::helper('helpdesk')->__('General Information')));
        if ($priority->getId()) {
            $fieldset->addField('priority_id', 'hidden', array(
                'name'      => 'priority_id',
                'value'     => $priority->getId(),
            ));
        }
        $fieldset->addField('name', 'text', array(
            'label'     => Mage::helper('helpdesk')->__('Title'),
            'name'      => 'name',
            'value'     => $priority->getName(),
        ));
        $fieldset->addField('sort_order', 'text', array(
            'label'     => Mage::helper('helpdesk')->__('Sort Order'),
            'name'      => 'sort_order',
            'value'     => $priority->getSortOrder(),
        ));
        return parent::_prepareForm();
    }
}
