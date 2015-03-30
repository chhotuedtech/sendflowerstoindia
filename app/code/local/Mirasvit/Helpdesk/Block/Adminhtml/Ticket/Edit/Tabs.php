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


class Mirasvit_Helpdesk_Block_Adminhtml_Ticket_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('ticket_tabs');
        $this->setDestElementId('edit_form');
    }

    protected function _beforeToHtml()
    {
        $this->addTab('general_section', array(
            'label'     => Mage::helper('helpdesk')->__('General'),
            'title'     => Mage::helper('helpdesk')->__('General'),
            'content'   => $this->getLayout()->createBlock('helpdesk/adminhtml_ticket_edit_tab_general')->toHtml(),
        ));
        $this->addTab('additional_section', array(
            'label'     => Mage::helper('helpdesk')->__('Additional'),
            'title'     => Mage::helper('helpdesk')->__('Additional'),
            'content'   => $this->getLayout()->createBlock('helpdesk/adminhtml_ticket_edit_tab_additional')->toHtml(),
        ));
        $this->addTab('followup_section', array(
            'label'     => Mage::helper('helpdesk')->__('Follow Up'),
            'title'     => Mage::helper('helpdesk')->__('Follow Up'),
            'content'   => $this->getLayout()->createBlock('helpdesk/adminhtml_ticket_edit_tab_followup')->toHtml(),
        ));
        $ticket = Mage::registry('current_ticket');
        if ($ticket && $ticket->getId()) {
            $this->addTab('other', array(
                'label'     => Mage::helper('helpdesk')->__('Other tickets'),
                'title'     => Mage::helper('helpdesk')->__('Other tickets'),
                'content'   => $this->getLayout()->createBlock('helpdesk/adminhtml_ticket_edit_tab_other')->toHtml(),
            ));
        }
        return parent::_beforeToHtml();
    }

    /************************/

}