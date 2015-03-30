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


class Mirasvit_Helpdesk_Adminhtml_GatewayController extends Mage_Adminhtml_Controller_Action
{

    public function preDispatch() {
        parent::preDispatch();
        if (!extension_loaded('imap')) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please, ask your hosting provider to enable IMAP extension in PHP configuration of your server. <br> Otherwise, helpdesk will not be able to fetch emails.'));
        }
    }
    protected function _initAction ()
    {
        $this->loadLayout()->_setActiveMenu('helpdesk');

        return $this;
    }

    public function indexAction ()
    {
        $this->_title($this->__('Gateways'));
        $this->_initAction();
        $this->_addContent($this->getLayout()
            ->createBlock('helpdesk/adminhtml_gateway'));
        $this->renderLayout();
    }

    public function addAction ()
    {
        $this->_title($this->__('New Gateway'));

        $this->_initGateway();

        $this->_initAction();
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Gateway  Manager'),
                Mage::helper('adminhtml')->__('Gateway Manager'), $this->getUrl('*/*/'));
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Add Gateway '), Mage::helper('adminhtml')->__('Add Gateway'));

        $this->getLayout()
            ->getBlock('head')
            ->setCanLoadExtJs(true);
        $this->_addContent($this->getLayout()->createBlock('helpdesk/adminhtml_gateway_edit'));
        $this->renderLayout();
    }

    public function editAction ()
    {
        $gateway = $this->_initGateway();

        if ($gateway->getId()) {
            $this->_title($this->__("Edit Gateway '%s'", $gateway->getName()));
            $this->_initAction();
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Gateways'),
                    Mage::helper('adminhtml')->__('Gateways'), $this->getUrl('*/*/'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Edit Gateway '),
                    Mage::helper('adminhtml')->__('Edit Gateway '));

            $this->getLayout()
                ->getBlock('head')
                ->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('helpdesk/adminhtml_gateway_edit'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('The Gateway does not exist.'));
            $this->_redirect('*/*/');
        }
    }

    public function saveAction ()
    {
        if ($data = $this->getRequest()->getPost()) {

            $gateway = $this->_initGateway();
            if ($data['password'] == '*****') {
                unset($data['password']);
            }
            $gateway->addData($data);
            //format date to standart
            // $format = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
            // Mage::helper('mstcore/date')->formatDateForSave($gateway, 'active_from', $format);
            // Mage::helper('mstcore/date')->formatDateForSave($gateway, 'active_to', $format);

            try {
                $gateway->save();
                $fetchHelper = Mage::helper('helpdesk/fetch');
                if ($fetchHelper->connect($gateway)) {
                    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Gateway was successfully saved. Connection is established.'));
                    $fetchHelper->close();
                }
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $gateway->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mirasvit_Ddeboer_Imap_Exception_AuthenticationFailedException $e) {
                $message = $e->getMessage();
                $message .= '<br>'.Mage::helper('helpdesk/checkenv')->checkGateway($gateway);
                Mage::getSingleton('adminhtml/session')->addError($message);
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Unable to find Gateway to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction ()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $gateway = Mage::getModel('helpdesk/gateway');

                $gateway->setId($this->getRequest()
                    ->getParam('id'))
                    ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__('Gateway was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()
                    ->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massChangeAction()
    {
        $ids = $this->getRequest()->getParam('gateway_id');
        if (!is_array($ids)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select Gateway(s)'));
        } else {
            try {
                $isActive = $this->getRequest()->getParam('is_active');
                foreach ($ids as $id) {
                    $gateway = Mage::getModel('helpdesk/gateway')->load($id);
                    $gateway->setIsActive($isActive);
                    $gateway->save();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully updated', count($ids)
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
        $ids = $this->getRequest()->getParam('gateway_id');
        if (!is_array($ids)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select Gateway(s)'));
        } else {
            try {
                foreach ($ids as $id) {
                    $gateway = Mage::getModel('helpdesk/gateway')
                        ->setIsMassDelete(true)
                        ->load($id);
                    $gateway->delete();
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

    public function _initGateway()
    {
        $gateway = Mage::getModel('helpdesk/gateway');
        if ($this->getRequest()->getParam('id')) {
            $gateway->load($this->getRequest()->getParam('id'));
        }

        Mage::register('current_gateway', $gateway);

        return $gateway;
    }

    /************************/

}