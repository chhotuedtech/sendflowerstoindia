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


class Mirasvit_Helpdesk_Block_Adminhtml_Ticket_Edit_Tab_Other extends Mage_Adminhtml_Block_Widget_Form
{
    public function getTicket() {
        return Mage::registry('current_ticket');
    }

    protected function _toHtml()
    {
    	$ticket = $this->getTicket();

        $grid = $this->getLayout()->createBlock('helpdesk/adminhtml_ticket_grid');

		$grid->addCustomFilter('(customer_email = "'.addslashes($ticket->getCustomerEmail()).'" OR customer_id='.(int)$ticket->getCustomerId().')
            AND ticket_id <> '.$ticket->getId());

        $grid->removeFilter('is_archived');
        $grid->setFilterVisibility(false);
        $grid->setPagerVisibility(0);
        $grid->setTabMode(true);
        return '<div>' . $grid->toHtml().'</div>' ;
   }

}