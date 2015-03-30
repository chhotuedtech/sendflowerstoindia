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


class Mirasvit_Helpdesk_Block_Adminhtml_Ticket_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct ()
    {
        parent::__construct();
        $this->_objectId = 'ticket_id';
        $this->_controller = 'adminhtml_ticket';
        $this->_blockGroup = 'helpdesk';
        $this->_removeButton('back');
        $this->_removeButton('reset');
        $this->_removeButton('save');

        if ($this->isArchive()) {
            $this->_updateButton('delete', 'label', Mage::helper('helpdesk')->__('Delete'));
            $this->_addButton('restore', array(
                    'label'     => Mage::helper('helpdesk')->__('Restore Ticket'),
                    'onclick'   => 'setLocation(\'' . $this->getRestoreUrl() . '\')',
                    'class'     => 'save',
                ));
        } elseif ($this->getTicket()) {
            $this->_removeButton('delete');
            $this->_addButton('archive', array(
                    'label'     => Mage::helper('helpdesk')->__('Archive'),
                    'onclick'   => "deleteConfirm('Are you sure you want to do this?','" . $this->getArchiveUrl() . "')",
                    'class'     => 'delete',
                ));
            $this->_addButton('spam', array(
                'label'     => Mage::helper('helpdesk')->__('Spam'),
                'onclick'   => 'setLocation(\'' . $this->getSpamUrl() . '\')',
                'class'     => 'delete',
                'style' => 'margin-right: 40px'
            ));
        } else {
            $this->_addButton('update', array(
                'label'     => Mage::helper('helpdesk')->__('Create Ticket'),
                'onclick'   => 'saveAndContinueEdit()',
                'class'     => 'save saveTicketBtn',
            ), -100);
        }
        if ($this->getTicket()) {
            if (Mage::getSingleton('helpdesk/config')->isActiveRma()) {
                $this->_addButton('rma', array(
                        'label'     => Mage::helper('helpdesk')->__('Convert To RMA'),
                        'onclick'   => 'var win=window.open(\'' . $this->getRmaUrl() . '\', \'_blank\'); win.focus();',
                        // 'onclick'   => 'setLocation(\'' . $this->getRmaUrl() . '\')',
                        'class'     => 'add',
                    ));
            }
            $this->_addButton('update_continue', array(
                'label'     => Mage::helper('helpdesk')->__('Update And Continue Edit'),
                'onclick'   => 'saveAndContinueEdit()',
                'class'     => 'save saveAndContinueTicketBtn',
            ), -100);
            $this->_addButton('update', array(
                'label'     => Mage::helper('helpdesk')->__('Update'),
                'onclick'   => 'saveEdit()',
                'class'     => 'save saveTicketBtn',
            ), -100);

        }

        $this->_addButton('back', array(
            'label'   => Mage::helper('adminhtml')->__('Back'),
            'onclick' => 'setLocation(\'' . Mage::helper('adminhtml')->getUrl('*/*/') . '\')',
            'class'   => 'back',
            'level'   => -1
        ));

        $this->_formScripts[] = "
            function saveEdit(){
                editForm.submit($('edit_form').action);
            }
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action + 'back/edit/');
            }
        ";
        if (!Mage::helper('helpdesk/permission')->isTicketRemoveAllowed()) {
            $this->_removeButton('delete');
        }
        return $this;
    }

    public function isArchive()
    {
        return Mage::registry('is_archive');
    }

    public function getBackUrl()
    {
        return $this->getUrl('*/*/', array('is_archive' => $this->isArchive()));
    }

    public function getRmaUrl()
    {
        return $this->getUrl('rmaadmin/adminhtml_rma/convertticket', array('id' => $this->getTicket()->getId()));
    }

    public function getSpamUrl()
    {
        return $this->getUrl('*/*/spam', array('id' => $this->getTicket()->getId()));
    }

    public function getArchiveUrl()
    {
        return $this->getUrl('*/*/archive', array('id' => $this->getTicket()->getId()));
    }

    public function getRestoreUrl()
    {
        return $this->getUrl('*/*/restore', array('id' => $this->getTicket()->getId()));
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }

    public function getTicket()
    {
        if (Mage::registry('current_ticket') && Mage::registry('current_ticket')->getId()) {
            return Mage::registry('current_ticket');
        }
    }

    public function getHeaderText ()
    {
        if ($ticket = $this->getTicket()) {
            return Mage::helper('helpdesk')->__("%s", $this->htmlEscape('[#'.$ticket->getCode().'] '.$ticket->getName()));
        } else {
            return Mage::helper('helpdesk')->__('Create New Ticket');
        }
    }

    /************************/

}
