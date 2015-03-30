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


class Mirasvit_SeoAutolink_Adminhtml_LinkController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction ()
    {
        $this->loadLayout()->_setActiveMenu('seo');

        return $this;
    }

    public function indexAction ()
    {
        $this->_title($this->__('Link Manager'));
        $this->_initAction();
        $this->_addContent($this->getLayout()
            ->createBlock('seoautolink/adminhtml_link'));
        $this->renderLayout();
    }

    public function addAction ()
    {
        $this->_title($this->__('New Link'));

        $this->_initModel();

        $this->_initAction();
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Link  Manager'),
                Mage::helper('adminhtml')->__('Link Manager'), $this->getUrl('*/*/'));
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Add Link '), Mage::helper('adminhtml')->__('Add Link'));

        $this->getLayout()
            ->getBlock('head')
            ->setCanLoadExtJs(true);

        $this->_addContent($this->getLayout()->createBlock('seoautolink/adminhtml_link_edit'));

        $this->renderLayout();
    }

    public function editAction ()
    {
        $model = $this->_initModel();

        if ($model->getId()) {
            $this->_initAction();
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Link Manager'),
                    Mage::helper('adminhtml')->__('Link Manager'), $this->getUrl('*/*/'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Edit Link '),
                    Mage::helper('adminhtml')->__('Edit Link '));

            $this->getLayout()
                ->getBlock('head')
                ->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('seoautolink/adminhtml_link_edit'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('The item does not exist.'));
            $this->_redirect('*/*/');
        }
    }

    public function saveAction ()
    {
        if ($data = $this->getRequest()->getPost()) {
            $data['keyword'] = trim($data['keyword']);
            $model = $this->_initModel();
            $model->setData($data);
            //format date to standart
            $format = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
            Mage::helper('mstcore/date')->formatDateForSave($model, 'active_from', $format);
            Mage::helper('mstcore/date')->formatDateForSave($model, 'active_to', $format);

            try {
                $model->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction ()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('seoautolink/link');

                $model->setId($this->getRequest()
                    ->getParam('id'))
                    ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__('Item was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()
                    ->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $ids = $this->getRequest()->getParam('link_id');
        if (!is_array($ids)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($ids as $id) {
                    $model = Mage::getModel('seoautolink/link')
                        ->setIsMassDelete(true)
                        ->load($id);
                    $model->delete();
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

    public function massEnableAction()
    {
        $ids = $this->getRequest()->getParam('link_id');
        if (!is_array($ids)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($ids as $id) {
                    $model = Mage::getModel('seoautolink/link')
                        ->load($id)
                        ->setIsActive(true);
                    $model->save();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully enabled', count($ids)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massDisableAction()
    {
        $ids = $this->getRequest()->getParam('link_id');
        if (!is_array($ids)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($ids as $id) {
                    $model = Mage::getModel('seoautolink/link')
                        ->load($id)
                        ->setIsActive(false);
                    $model->save();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully enabled', count($ids)
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
        $model = Mage::getModel('seoautolink/link');
        if ($this->getRequest()->getParam('id')) {
            $model->load($this->getRequest()->getParam('id'));
        }

        Mage::register('current_model', $model);

        return $model;
    }
}
