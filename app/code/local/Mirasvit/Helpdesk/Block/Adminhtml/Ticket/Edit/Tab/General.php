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


class Mirasvit_Helpdesk_Block_Adminhtml_Ticket_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('mst_helpdesk/ticket/edit/tab/general.phtml');
    }

    protected function getConfig()
    {
        return Mage::getSingleton('helpdesk/config');
    }

    protected function getGeneralForm()
    {
        $form  = new Varien_Data_Form();
        $this->setForm($form);
        $ticket = Mage::registry('current_ticket');

        $fieldset = $form->addFieldset('edit_fieldset', array('legend'=> Mage::helper('helpdesk')->__('Ticket Summary')));
        if ($ticket->getId()) {
            $fieldset->addField('ticket_id', 'hidden', array(
                'name'      => 'ticket_id',
                'value'     => $ticket->getId(),
            ));
        }
        if (!$ticket->getId()) {
            $element = $fieldset->addField('name', 'text', array(
                'label'     => Mage::helper('helpdesk')->__('Subject'),
                'name'      => 'name',
                'value'     => $ticket->getName(),
                'required'  => true
            ));
        }
        $fieldset->addField('status_id', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Status'),
            'name'      => 'status_id',
            'value'     => $ticket->getStatusId(),
            'values'    => Mage::getModel('helpdesk/status')->getCollection()->toOptionArray()
        ));
        $fieldset->addField('priority_id', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Priority'),
            'name'      => 'priority_id',
            'value'     => $ticket->getPriorityId(),
            'values'    => Mage::getModel('helpdesk/priority')->getCollection()->toOptionArray()
        ));
        $fieldset->addField('owner', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Ticket Owner'),
            'name'      => 'owner',
            'value'     => (int)$ticket->getDepartmentId().'_'.(int)$ticket->getUserId(),
            'values'    => Mage::helper('helpdesk')->getAdminOwnerOptionArray()
        ));

        $collection = Mage::helper('helpdesk/field')->getStaffCollection();
        if ($ticket->getStoreId()) {
            $collection->addStoreFilter($ticket->getStoreId());
        }
        foreach ($collection as $field) {
            if ($field->getType() == 'checkbox') {
                $fieldset->addField($field->getCode().'1', 'hidden', array('name'=>$field->getCode(), 'value' => 0));
            }
            $fieldset->addField($field->getCode(), $field->getType(), Mage::helper('helpdesk/field')->getInputParams($field, true, $ticket));
        }
        return $form;
    }

    protected function getCustomerForm()
    {
        $form  = new Varien_Data_Form();
        $this->setForm($form);
        $ticket = Mage::registry('current_ticket');
        $fieldset = $form->addFieldset('edit_fieldset', array('legend'=> Mage::helper('helpdesk')->__('Customer Summary')));
        $element = $fieldset->addField('customer_email', 'text', array(
            'label'     => Mage::helper('helpdesk')->__('Customer Email'),
            'name'      => 'customer_email',
            'value'     => $ticket->getCustomerEmail(),
            'required'  => true
        ));

        $element = $fieldset->addField('customer_query', 'text', array(
            'label'     => Mage::helper('helpdesk')->__('Assigned To Customer'),
            'name'      => 'customer_query',
        ));

        $element->setAfterElementHtml("
<button type='button' id='find-customer-btn' data-url='".Mage::helper("adminhtml")->getUrl('helpdeskadmin/adminhtml_ticket/customerfind')."'><span>".Mage::helper('helpdesk')->__('Find Customer')."</span></button>
            ");

        $customersOptions = array();
        $ordersOptions = array(array('label' => $this->__('Unassigned'), 'value' => 0 ));
        if ($ticket->getCustomerId() || $ticket->getQuoteAddressId()) {
            $customers = Mage::helper('helpdesk')->getCustomerArray(false, $ticket->getCustomerId(), $ticket->getQuoteAddressId());
            $email = false;
            foreach ($customers as $value) {
                $customersOptions[]  = array('label'=>$value['name'], 'value'=>$value['id']);
                $email = $value['email'];
            }

            $orders = Mage::helper('helpdesk')->getOrderArray($email);
            foreach ($orders as $value) {
                $ordersOptions[]  = array('label'=>$value['name'], 'value'=>$value['id']);
            }
        }
        $element = $fieldset->addField('customer_id', 'select', array(
            'name'      => 'customer_id',
            'value'     => $ticket->getCustomerId()?$ticket->getCustomerId():'address_'.$ticket->getQuoteAddressId(),
            'values'    => $customersOptions,
            'style'    => $ticket->hasCustomer()?'': 'display: none'
        ));
        if ($ticket->getCustomerId()) {
            $element->setAfterElementHtml("<a id='view_customer_link' href='".Mage::helper("helpdesk/mage")->getBackendCustomerUrl($ticket->getCustomerId())."' target='_blank'>".Mage::helper('helpdesk')->__('view')."</a>");
        }
        $element = $fieldset->addField('order_id', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Assigned to Order'),
            'name'      => 'order_id',
            'value'     => $ticket->getOrderId(),
            'values'    => $ordersOptions,
        ));
        if ($ticket->hasCustomer() && $ticket->getOrderId()) {
            $element->setAfterElementHtml("<a id='view_order_link' href='".Mage::helper("helpdesk/mage")->getBackendOrderUrl($ticket->getOrderId())."' target='_blank'>".Mage::helper('helpdesk')->__('view')."</a>");
        }
        return $form;
    }

    protected function getMessageForm()
    {
        $form  = new Varien_Data_Form();
        $this->setForm($form);
        $ticket = Mage::registry('current_ticket');

        $fieldset = $form->addFieldset('edit_fieldset', array('legend'=> Mage::helper('helpdesk')->__('Reply')));

        $element = $fieldset->addField('reply_type', 'select', array(
            'label'     => Mage::helper('helpdesk')->__('Message Type'),
            'name'      => 'reply_type',
            'value'     => Mirasvit_Helpdesk_Model_Config::MESSAGE_PUBLIC,
            'values'    => array(
                Mirasvit_Helpdesk_Model_Config::MESSAGE_PUBLIC => 'Message to Customer',
                Mirasvit_Helpdesk_Model_Config::MESSAGE_INTERNAL => 'Internal Note',
                Mirasvit_Helpdesk_Model_Config::MESSAGE_PUBLIC_THIRD => 'Message to Third Party',
                Mirasvit_Helpdesk_Model_Config::MESSAGE_INTERNAL_THIRD => 'Private Message to Third Party',
            )
        ));

        $element->setAfterElementHtml("
<div id='reply_note'></div>
        ");

        $element = $fieldset->addField('third_party_email', 'text', array(
            'label'     => Mage::helper('helpdesk')->__('Third Party Email'),
            'name'      => 'third_party_email',
            'value'     => $ticket->getThirdPartyEmail(),
            'required'  => true,
            // 'note'      => 'comma separated list'
        ));

        $collection = Mage::getModel('helpdesk/template')->getCollection()
                ->addFieldToFilter('is_active', 1)
                ->setOrder('name', 'asc');
        // if ($ticket->getStoreId()) {
            $collection->addStoreFilter($ticket->getStoreId());
        // }
        if ($collection->count()) {
            $element = $fieldset->addField('template_id', 'select', array(
                'label'     => Mage::helper('helpdesk')->__('Insert Quick Response'),
                'name'      => 'template_id',
                'value'     => $ticket->getTemplateId(),
                'values'    => $collection->toOptionArray(true)
            ));

            $values = array();
            foreach ($collection as $template) {
                $text = trim($template->getParsedTemplate($ticket));
                $values[] = "<div id='htmltemplate-{$template->getId()}' style='display:none;'>{$text}</div>";
            }
            $element->setAfterElementHtml(implode("\n", $values));
        }

        if ($this->getConfig()->getGeneralIsWysiwyg()) {
            $fieldset->addField('reply', 'editor', array(
                'label'     => Mage::helper('helpdesk')->__('Message'),
                'required'  => false,
                'name'      => 'reply',
                'value'     => $ticket->getReply(),
                'config'    => Mage::getSingleton('mstcore/wysiwyg_config')->getConfig(),
                'wysiwyg'   => true,
                'style'     => 'height:15em',
            ));
        } else {
            $fieldset->addField('reply', 'textarea', array(
                'label'     => Mage::helper('helpdesk')->__('Message'),
                'required'  => false,
                'name'      => 'reply',
                'value'     => $ticket->getReply(),
            ));
        }
        $fieldset->addField('attachment', 'file', array(
            'name'      => 'attachment[]',
            'class'     => 'multi'
        ));
        return $form;
    }

    /************************/

    protected function _toHtml()
    {
        $messages = $this->getLayout()->createBlock('helpdesk/adminhtml_ticket_edit_tab_messages')->toHtml();
        return parent::_toHtml().$messages;
    }
}