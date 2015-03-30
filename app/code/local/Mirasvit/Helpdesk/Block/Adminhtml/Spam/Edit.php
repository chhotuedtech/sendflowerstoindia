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


class Mirasvit_Helpdesk_Block_Adminhtml_Spam_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct ()
    {
        parent::__construct();
        $this->_objectId = 'ticket_id';
        $this->_controller = 'adminhtml_spam';
        $this->_blockGroup = 'helpdesk';


        $this->_removeButton('save');
        $this->_removeButton('delete');
        $this->_removeButton('reset');

        $this->_addButton('approve', array(
            'label'     => Mage::helper('helpdesk')->__('Approve'),
            'onclick'   => 'setLocation(\'' . $this->getApproveUrl() . '\')',
        ));
        return $this;
    }


    public function getApproveUrl()
    {
        return $this->getUrl('*/*/approve', array('id' => $this->getModel()->getId()));
    }


    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }

    public function getModel()
    {
        if (Mage::registry('current_ticket') && Mage::registry('current_ticket')->getId()) {
            return Mage::registry('current_ticket');
        }
    }

    public function getHeaderText ()
    {
        return Mage::helper('helpdesk')->__($this->htmlEscape(Mage::registry('current_ticket')->getName()));
    }

    public function _toHtml()
    {
        $messages = $this->getLayout()->createBlock('helpdesk/adminhtml_ticket_edit_tab_messages')->toHtml();
        return parent::_toHtml().$messages;
    }
}