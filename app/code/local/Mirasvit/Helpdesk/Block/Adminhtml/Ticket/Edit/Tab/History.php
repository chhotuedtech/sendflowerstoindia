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


class Mirasvit_Helpdesk_Block_Adminhtml_Ticket_Edit_Tab_History extends Mage_Adminhtml_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('mst_helpdesk/ticket/edit/tab/history.phtml');
    }

    public function getTicket() {
        return Mage::registry('current_ticket');
    }

    public function getHistoryCollection()
    {
        return Mage::getModel('helpdesk/history')->getCollection()
                ->addFieldToFilter('main_table.ticket_id', $this->getTicket()->getId())
                ->setOrder('created_at', 'desc');
    }
}
