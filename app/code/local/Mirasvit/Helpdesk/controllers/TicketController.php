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


class Mirasvit_Helpdesk_TicketController extends Mage_Core_Controller_Front_Action
{

    /**
     * Retrieve customer session object
     *
     * @return Mage_Customer_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }

    public function preDispatch()
    {
        parent::preDispatch();
        $action = $this->getRequest()->getActionName();
        if ($action != 'external' && $action != 'stopremind' && $action != 'postexternal' && $action != 'attachment') {
            if (!Mage::getSingleton('customer/session')->authenticate($this)) {
                $this->setFlag('', 'no-dispatch', true);
            }
        }
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->renderLayout();
    }

    protected function _initTicket()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $customer = $this->_getSession()->getCustomer();
            $ticket = Mage::getModel('helpdesk/ticket')->getCollection()
              ->joinFields()
              ->addFieldToFilter('main_table.ticket_id', $id)
              ->addFieldToFilter('main_table.customer_id', $customer->getId())
              ->getFirstItem();
            if ($ticket->getId() > 0) {
                Mage::register('current_ticket', $ticket);
                return $ticket;
            }
        }
    }

    protected function _initExternalTicket()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $ticket = Mage::getModel('helpdesk/ticket')->getCollection()
              ->joinFields()
              ->addFieldToFilter('main_table.external_id', $id)
              ->getFirstItem();

            if ($ticket->getId() > 0) {
                Mage::register('current_ticket', $ticket);
                Mage::register('external_ticket', true);
                return $ticket;
            }
        }
    }

    protected function markAsRead($ticket) {
        $message = $ticket->getLastMessage();
        $message->setIsRead(true)->save();
    }
    // public function stopremindAction()
    // {
    //     if ($ticket = $this->_initExternalTicket()) {
    //         $this->loadLayout();
    //         $this->_initLayoutMessages('customer/session');
    //         $session  = $this->_getSession();
    //         $session->addSuccess($this->__('Ticket was successfuly closed'));
    //         $this->renderLayout();
    //     } else {
    //         $this->_forward('no_rote');
    //     }
    // }

    public function externalAction()
    {
        if ($ticket = $this->_initExternalTicket()) {
            $this->markAsRead($ticket);
            $this->loadLayout();
            $this->_initLayoutMessages('customer/session');
            $this->renderLayout();
        } else {
            $this->_forward('no_rote');
        }
    }

    public function viewAction()
    {
        if ($ticket = $this->_initTicket()) {
            $this->markAsRead($ticket);
            $this->loadLayout();
            $this->_initLayoutMessages('customer/session');
            if ($navigationBlock = $this->getLayout()->getBlock('customer_account_navigation')) {
                $navigationBlock->setActive('helpdesk/ticket/index');
            }
            $this->renderLayout();
        } else {
            $this->_forward('no_rote');
        }
    }

    /************************/

    public function postexternalAction()
    {
        $session  = $this->_getSession();
        $customer = $session->getCustomer();
        $ticket = $this->_initExternalTicket();
        if (!$ticket) {
            $this->_forward('no_route');
            return;
        }
        if ($customer->getId() == 0) {
            $customer = new Varien_Object();
            $customer->setName($ticket->getCustomerName());
            $customer->setEmail($ticket->getCustomerEmail());
        }

        if ($this->postTicket($ticket, $customer)) {
            $this->_redirectUrl($ticket->getExternalUrl());
        } else {
            $this->_forward('no_route');
        }
    }

    public function postmessageAction()
    {
        $customer = $this->_getSession()->getCustomer();
        $ticket = $ticket = $this->_initTicket();
        if ($this->postTicket($ticket, $customer)) {
            $this->_redirect('*/*/');
        } else {
            $this->_forward('no_route');
        }
    }

    private function postTicket($ticket, $customer) {
        $message  = $this->getRequest()->getParam('message');
        $close  = $this->getRequest()->getParam('close');
        $session  = $this->_getSession();
        try {
            if ($ticket && $close) {
                $ticket->close();
                $session->addSuccess($this->__('Ticket was successfuly closed'));
            } elseif ($ticket && ($message || $_FILES['attachment']['name'][0] != '')) {
                $message = $ticket->addMessage($message, $customer, false, Mirasvit_Helpdesk_Model_Config::CUSTOMER);
                $session->addSuccess($this->__('Your message was successfuly posted'));
            } elseif ($message || $_FILES['attachment']['name'][0] != '') {
                Mage::helper('helpdesk/process')->createFromPost($this->getRequest()->getParams(), Mirasvit_Helpdesk_Model_Config::CHANNEL_CUSTOMER_ACCOUNT);
                $session->addSuccess($this->__('Your ticket was successfuly posted'));
            } else {
                return false;
            }
        } catch (Mage_Core_Exception $e) {
            $session->addError($e->getMessage());
            return true;
        }

        return true;
    }

    public function attachmentAction()
    {
        $id         = $this->getRequest()->getParam('id');
        $collection = Mage::getModel('helpdesk/attachment')->getCollection()
            ->addFieldToFilter('external_id', $id);
        if (!$collection->count()) {
            die('wrong URL');
        }
        $attachment = $collection->getFirstItem();

        // give our picture the proper headers...otherwise our page will be confused
        header("Content-Disposition: attachment; filename=\"{$attachment->getName()}\"");
        header("Content-length: {$attachment->getSize()}");
        header("Content-type: {$attachment->getType()}");
        echo $attachment->getBody();
        die;
    }
}
