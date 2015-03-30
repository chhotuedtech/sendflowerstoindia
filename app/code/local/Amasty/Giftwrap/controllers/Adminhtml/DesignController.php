<?php

class Amasty_Giftwrap_Adminhtml_DesignController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('sales/amgiftwrap/design');
        $this->_addContent($this->getLayout()->createBlock('amgiftwrap/adminhtml_design'));
        $this->renderLayout();
    }

    protected function _setActiveMenu($menuPath)
    {
        $this->getLayout()->getBlock('menu')->setActive($menuPath);
        $this->_title($this->__('Sales'))->_title($this->__('Gift Wrap Design'));

        return $this;
    }

    public function newAction()
    {
        $this->editAction();
    }

    public function editAction()
    {
        $id = (int)$this->getRequest()->getParam('id');
        $model = Mage::getModel('amgiftwrap/design')->load($id);

        if ($id && !$model->getId()) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('amgiftwrap')->__('Item does not exist'));
            $this->_redirect('*/*/');

            return;
        }

        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('amgiftwrap_design', $model);

        $this->loadLayout();

        $this->_setActiveMenu('sales/amgiftwrap');
        $this->_addContent($this->getLayout()->createBlock('amgiftwrap/adminhtml_design_edit'));

        $this->renderLayout();
    }

    public function saveAction()
    {
        $id   = $this->getRequest()->getParam('id');
        $model = Mage::getModel('amgiftwrap/design');
        $data = $this->getRequest()->getPost();
        if ($id !== null && $id <= 0) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('amgiftwrap')->__('Unable to find an item to save'));
            $this->_redirect('*/*/');
        } elseif ($data) {
            try {
                $data = Mage::helper('amgiftwrap')->prepareToSaveDataArray($data, 'design');
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
            Mage::getModel('amgiftwrap/design')->massDelete($ids);
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
        if (!is_array($ids)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('amgiftwrap')->__('Please select records'));
            $this->_redirect('*/*/');

            return;
        }

        try {
            Mage::getModel('amgiftwrap/design')->massDelete(array($ids));
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


    public function massEnableAction()
    {
        $ids = $this->getRequest()->getParam('ids');
        if (!(is_numeric($ids) || is_array($ids))) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('amgiftwrap')->__('Please select records'));
            $this->_redirect('*/*/');

            return;
        }

        try {
            Mage::getModel('amgiftwrap/design')->massEnable($ids);
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
            Mage::getModel('amgiftwrap/design')->massDisable($ids);
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