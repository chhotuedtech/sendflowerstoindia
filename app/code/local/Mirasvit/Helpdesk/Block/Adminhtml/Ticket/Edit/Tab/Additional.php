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


class Mirasvit_Helpdesk_Block_Adminhtml_Ticket_Edit_Tab_Additional extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form  = new Varien_Data_Form();
        $this->setForm($form);
        $ticket = Mage::registry('current_ticket');

        $fieldset = $form->addFieldset('edit_fieldset', array('legend'=> Mage::helper('helpdesk')->__('Additional Information')));
        if ($ticket->getId()) {
            $fieldset->addField('ticket_id', 'hidden', array(
                'name'      => 'ticket_id',
                'value'     => $ticket->getId(),
            ));
        }
        if ($ticket->getId()) {
            $fieldset->addField('name', 'text', array(
                'label'     => Mage::helper('helpdesk')->__('Subject'),
                'name'      => 'name',
                'value'     => $ticket->getName(),
            ));
        }
        if (!Mage::app()->isSingleStoreMode()) {
            $field = $fieldset->addField('store_id', 'select', array(
                'name'      => 'store_id',
                'label'     => Mage::helper('helpdesk')->__('Store View'),
                'title'     => Mage::helper('helpdesk')->__('Store View'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, false),
                'value'     => $ticket->getStoreId()
            ));
        } else {
            $fieldset->addField('store_id', 'hidden', array(
                'name'      => 'store_id',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
            $ticket->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $element = $fieldset->addField('channel', 'label', array(
            'label'     => Mage::helper('helpdesk')->__('Channel'),
            'name'      => 'channel',
            'value'     => Mage::helper('helpdesk/channel')->getLabel($ticket->getChannel()),
        ));
        $data = $ticket->getChannelData();
        if (isset($data['url'])) {
            $element->setAfterElementHtml("&nbsp;<a id='view_customer_link' href='".$data['url']."' target='_blank'>".Mage::helper('helpdesk')->__('open page')."</a>");
        }
        if ($ticket->getId()) {
            $fieldset->addField('external_link', 'link', array(
                'label'    => Mage::helper('helpdesk')->__('External Link'),
                'name'     => 'external_link',
                'class'    => 'external-link',
                'value'    => $ticket->getExternalUrl(),
                'href'    => $ticket->getExternalUrl(),
                'target'    => '_blank',
            ));
        }
        $tags = array();
        foreach ($ticket->getTags() as $tag) {
            $tags[] = $tag->getName();
        }
        $fieldset->addField('tags', 'text', array(
            'label'     => Mage::helper('helpdesk')->__('Tags'),
            'name'      => 'tags',
            'value'     => implode(', ', $tags),
            'note'      => Mage::helper('helpdesk')->__('comma-separated list'),
        ));
        return parent::_prepareForm();
    }

    /************************/

    protected function _toHtml()
    {
        $history = $this->getLayout()->createBlock('helpdesk/adminhtml_ticket_edit_tab_history')->toHtml();
        return parent::_toHtml().$history;
    }
}