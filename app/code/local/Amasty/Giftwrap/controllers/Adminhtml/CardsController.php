<?php

class Amasty_Giftwrap_Adminhtml_CardsController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        if (!Mage::getStoreConfig('sales/gift_options/allow_order')) {
            Mage::getSingleton('adminhtml/session')->addNotice(Mage::helper('amgiftwrap')->__(
                    'If you want to enable Gift Message form, you need to turn on "Gift Options" <a href="' . Mage::helper("adminhtml")->getUrl("adminhtml/system_config/edit/section/sales") . '">on this page.</a>'
                )
            );
        }
        $this->loadLayout();
        $this->_setActiveMenu('sales/amgiftwrap/cards');
        $this->_addContent($this->getLayout()->createBlock('amgiftwrap/adminhtml_cards'));
        $this->renderLayout();
    }

    protected function _setActiveMenu($menuPath)
    {
        $this->getLayout()->getBlock('menu')->setActive($menuPath);
        $this->_title($this->__('Sales'))->_title($this->__('Message Cards'));

        return $this;
    }

    public function newAction()
    {
        $this->editAction();
    }

    public function editAction()
    {
        $id = (int)$this->getRequest()->getParam('id');
        $model = Mage::getModel('amgiftwrap/cards')->load($id);

        if ($id && !$model->getId()) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('amgiftwrap')->__('Item does not exist'));
            $this->_redirect('*/*/');

            return;
        }

        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('amgiftwrap_cards', $model);

        $this->loadLayout();

        $this->_setActiveMenu('sales/amgiftwrap');
        $this->_addContent($this->getLayout()->createBlock('amgiftwrap/adminhtml_cards_edit'));

        $this->renderLayout();
    }

    public function saveAction()
    {
        $id   = $this->getRequest()->getParam('id');
        $path = Mage::getBaseDir('media') . DS . 'amgiftwrap' . DS . 'cards' . DS;
        $model = Mage::getModel('amgiftwrap/cards');
        $data = $this->getRequest()->getPost();
        if ($id !== null && $id <= 0) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('amgiftwrap')->__('Unable to find an item to save'));
            $this->_redirect('*/*/');
        } elseif ($data) {
            try {
                $data = Mage::helper('amgiftwrap')->prepareToSaveDataArray($data, 'cards');
                $model->setData($data)->setId($id);
                $model->save();
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('amgiftwrap')->__('Item has been successfully saved'));

                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $id));
            }
        }
    }


    public function massDeleteAction()
    {
        $ids = $this->getRequest()->getParam('ids');
        if (!(is_numeric($ids) || is_array($ids))) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('amgiftwrap')->__('Please select records'));
            $this->_redirect('*/*/');

            return;
        }

        try {
            Mage::getModel('amgiftwrap/cards')->massDelete($ids);
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('adminhtml')->__(
                    'Total of %d record(s) were successfully deleted', count($ids)
                )
            );
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        $this->_redirect('*/*/');

        return;
    }

    public function deleteAction()
    {
        $ids = $this->getRequest()->getParam('id');
        if (!is_numeric($ids)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('amgiftwrap')->__('Please select records'));
            $this->_redirect('*/*/');

            return;
        }

        try {
            Mage::getModel('amgiftwrap/cards')->massDelete(array($ids));
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('adminhtml')->__(
                    'Total of %d record(s) were successfully deleted', count(array($ids))
                )
            );
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        $this->_redirect('*/*/');

        return;
    }


    public function massEnableAction()
    {
        $ids = $this->getRequest()->getParam('ids');
        if (!(is_numeric($ids) || is_array($ids))) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('amgiftwrap')->__('Please select records'));
            $this->_redirect('*/*/');

            return;
        }

        try {
            Mage::getModel('amgiftwrap/cards')->massEnable($ids);
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('adminhtml')->__(
                    'Total of %d record(s) were successfully enabled', count($ids)
                )
            );
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        $this->_redirect('*/*/');

        return;
    }


    public function massDisableAction()
    {
        $ids = $this->getRequest()->getParam('ids');
        if (!(is_numeric($ids) || is_array($ids))) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('amgiftwrap')->__('Please select records'));
            $this->_redirect('*/*/');

            return;
        }

        try {
            Mage::getModel('amgiftwrap/cards')->massDisable($ids);
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('adminhtml')->__(
                    'Total of %d record(s) were successfully disabled', count($ids)
                )
            );
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        $this->_redirect('*/*/');

        return;
    }

}