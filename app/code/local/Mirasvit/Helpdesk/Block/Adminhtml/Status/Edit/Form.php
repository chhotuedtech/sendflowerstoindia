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


class Mirasvit_Helpdesk_Block_Adminhtml_Status_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
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

        $status = Mage::registry('current_status');

        $fieldset = $form->addFieldset('edit_fieldset', array('legend'=> Mage::helper('helpdesk')->__('General Information')));
        if ($status->getId()) {
            $fieldset->addField('status_id', 'hidden', array(
                'name'      => 'status_id',
                'value'     => $status->getId(),
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
            'value'     => $status->getName(),
            'after_element_html' => ' [STORE VIEW]',
        ));
        $field = $fieldset->addField('code', 'text', array(
            'label'     => Mage::helper('helpdesk')->__('Code'),
            'required'  => true,
            'name'      => 'code',
            'value'     => $status->getCode(),
        ));
        if ($status->getId()) {
            $field->setReadonly(true);
        }
        $fieldset->addField('sort_order', 'text', array(
            'label'     => Mage::helper('helpdesk')->__('Sort Order'),
            'name'      => 'sort_order',
            'value'     => $status->getSortOrder(),
        ));
        $element = $fieldset->addField('color', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Color'),
            'name'      => 'color',
            'value'     => $status->getColor(),
            'values'    => Mage::getSingleton('helpdesk/config_source_color')->toOptionArray(),
            'onchange' => "removeAllClasses(); $('example').addClassName(this.value)"
        ));
        $element->setAfterElementHtml(
            '<br><br><div class="status_id "><span id="example" class=" '.$status->getColor().'">Label example</span></div>
            <script>
            function removeAllClasses() {
                var classArray = $("example").classNames().toArray();
                for (var index = 0, len = classArray.size(); index < len; ++index) {
                    $("example").removeClassName(classArray[index]);
                }
            }

            </script>
            '
        );

        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
    /************************/

}