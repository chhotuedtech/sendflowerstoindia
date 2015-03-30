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
 * @package   Advanced SEO Suite
 * @version   1.1.1
 * @build     803
 * @copyright Copyright (C) 2014 Mirasvit (http://mirasvit.com/)
 */


class Mirasvit_Seo_Adminhtml_RewriteController extends Mage_Adminhtml_Controller_Action
{
    public function preDispatch()
    {
        parent::preDispatch();
        Mage::getDesign()->setTheme('mirasvit');
        return $this;
    }

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('seo');
		$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        return $this;
    }

    public function indexAction()
    {
        $this->_title($this->__('Rewrite Manager'));
        $this->_initAction();
        $this->_addContent($this->getLayout()->createBlock('seo/adminhtml_rewrite'));
        $this->renderLayout();
    }

    public function addAction()
    {
        $this->_title($this->__('New Rewrite'));

        $_model = Mage::getModel('seo/rewrite');
        Mage::register('rewrite_data', $_model);
        Mage::register('current_rewrite', $_model);

        $this->_initAction();
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('SEO Rewrite Manager'), Mage::helper('adminhtml')->__('I-Rewrite Manager'), $this->getUrl('*/*/'));
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Add Rewrite'), Mage::helper('adminhtml')->__('Add Rewrite'));

        $this->_addContent($this->getLayout()->createBlock('seo/adminhtml_rewrite_edit'))
            ->_addLeft($this->getLayout()->createBlock('seo/adminhtml_rewrite_edit_tabs'));

        $this->renderLayout();
    }

    public function editAction()
    {
        $rewriteId = $this->getRequest()->getParam('id');
        $_model = Mage::getModel('seo/rewrite')->load($rewriteId);

        if ($_model->getId()) {
            $this->_title($_model->getId() ? $_model->getName() : $this->__('New Rewrite'));

            Mage::register('rewrite_data', $_model);
            Mage::register('current_rewrite', $_model);

            $this->_initAction();
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('SEO Rewrite Manager'), Mage::helper('adminhtml')->__('I-Rewrite Manager'), $this->getUrl('*/*/'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Edit Rewrite'), Mage::helper('adminhtml')->__('Edit Rewrite'));

            $this->_addContent($this->getLayout()->createBlock('seo/adminhtml_rewrite_edit'))
                ->_addLeft($this->getLayout()->createBlock('seo/adminhtml_rewrite_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('seo')->__('The rewrite does not exist.'));
            $this->_redirect('*/*/');
        }
    }

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $_model = Mage::getModel('seo/rewrite');

            $_model->setData($data)
                ->setId($this->getRequest()->getParam('id'));

            try {
                $_model->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('seo')->__('Rewrite was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $_model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('seo')->__('Unable to find rewrite to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('seo/rewrite');

                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Rewrite was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $IDList = $this->getRequest()->getParam('rewrite');
        if (!is_array($IDList)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select rewrite(s)'));
        } else {
            try {
                foreach ($IDList as $itemId) {
                    $_model = Mage::getModel('seo/rewrite')
                        ->setIsMassDelete(true)->load($itemId);
                    $_model->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($IDList)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction()
    {
        $IDList = $this->getRequest()->getParam('rewrite');
        if (!is_array($IDList)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select rewrite(s)'));
        } else {
            try {
                foreach ($IDList as $itemId) {
                    $_model = Mage::getSingleton('seo/rewrite')
                        ->setIsMassStatus(true)
                        ->load($itemId)
                        ->setIsActive($this->getRequest()->getParam('status'))
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($IDList))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

}