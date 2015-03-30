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


class Mirasvit_Helpdesk_Adminhtml_SpamController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction ()
    {
        $this->loadLayout()->_setActiveMenu('helpdesk');

        return $this;
    }

    public function indexAction ()
    {
        $this->_title($this->__('Spam Folder'));
        $this->_initAction();
        $this->_addContent($this->getLayout()
            ->createBlock('helpdesk/adminhtml_spam'));
        $this->renderLayout();
    }

    // public function addAction ()
    // {
    //     $this->_title($this->__('New Spam'));

    //     $this->_initModel();

    //     $this->_initAction();
    //     $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Spam  Manager'),
    //             Mage::helper('adminhtml')->__('Spam Manager'), $this->getUrl('*/*/'));
    //     $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Add Spam '), Mage::helper('adminhtml')->__('Add Spam'));

    //     $this->getLayout()
    //         ->getBlock('head')
    //         ->setCanLoadExtJs(true);
    //     $this->_addContent($this->getLayout()->createBlock('helpdesk/adminhtml_spam_edit'));
    //     $this->renderLayout();
    // }

    public function editAction ()
    {
        $model = $this->_initModel();

        if ($model->getId()) {
            $this->_title($this->__("Edit Spam '%s'", $model->getName()));
            $this->_initAction();
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Spam Folder'),
                    Mage::helper('adminhtml')->__('Spam Folder'), $this->getUrl('*/*/'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Edit Spam '),
                    Mage::helper('adminhtml')->__('Edit Spam '));

            $this->getLayout()
                ->getBlock('head')
                ->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('helpdesk/adminhtml_spam_edit'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('The spam does not exist.'));
            $this->_redirect('*/*/');
        }
    }

    // public function saveAction ()
    // {
    //     if ($data = $this->getRequest()->getPost()) {

    //         $model = $this->_initModel();
    //         $model->addData($data);

    //         //format date to standart
    //         // $format = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
    //         // Mage::helper('mstcore/date')->formatDateForSave($model, 'active_from', $format);
    //         // Mage::helper('mstcore/date')->formatDateForSave($model, 'active_to', $format);

    //         try {
    //             $model->save();

    //             Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Spam was successfully saved'));
    //             Mage::getSingleton('adminhtml/session')->setFormData(false);

    //             if ($this->getRequest()->getParam('back')) {
    //                 $this->_redirect('*/*/edit', array('id' => $model->getId()));
    //                 return;
    //             }
    //             $this->_redirect('*/*/');
    //             return;
    //         } catch (Exception $e) {
    //             Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
    //             Mage::getSingleton('adminhtml/session')->setFormData($data);
    //             $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
    //             return;
    //         }
    //     }
    //     Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Unable to find spam to save'));
    //     $this->_redirect('*/*/');
    // }

    public function approveAction ()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $ticket = Mage::getModel('helpdesk/ticket')->load($id);
                $ticket->markAsNotSpam();

                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__('Ticket was successfully moved to the Tickets folder'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()
                    ->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massApproveAction()
    {
        $ids = $this->getRequest()->getParam('spam_id');
        if (!is_array($ids)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select spam(s)'));
        } else {
            try {
                foreach ($ids as $id) {
                    $ticket = Mage::getModel('helpdesk/ticket')->load($id);
                    $ticket->markAsNotSpam();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully moved to the Tickets folder', count($ids)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massDeleteAction()
    {
        if (!Mage::helper('helpdesk/permission')->isTicketRemoveAllowed()) {
            return;
        }
        $ids = $this->getRequest()->getParam('spam_id');
        if (!is_array($ids)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select spam(s)'));
        } else {
            try {
                foreach ($ids as $id) {
                    $ticket = Mage::getModel('helpdesk/ticket')->load($id);
                    $ticket->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($ids)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function _initModel()
    {
        $model = Mage::getModel('helpdesk/ticket');
        if ($this->getRequest()->getParam('id')) {
            $model->load($this->getRequest()->getParam('id'));
        }

        Mage::register('current_ticket', $model);

        return $model;
    }
}