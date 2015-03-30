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


class Mirasvit_Helpdesk_Adminhtml_PatternController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction ()
    {
        $this->loadLayout()->_setActiveMenu('helpdesk');

        return $this;
    }

    public function indexAction ()
    {
        $this->_title($this->__('Spam Filter Patterns'));
        $this->_initAction();
        $this->_addContent($this->getLayout()
            ->createBlock('helpdesk/adminhtml_pattern'));
        $this->renderLayout();
    }

    public function addAction ()
    {
        $this->_title($this->__('New Pattern'));

        $this->_initPattern();

        $this->_initAction();
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Pattern  Manager'),
                Mage::helper('adminhtml')->__('Pattern Manager'), $this->getUrl('*/*/'));
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Add Pattern '), Mage::helper('adminhtml')->__('Add Pattern'));

        $this->getLayout()
            ->getBlock('head')
            ->setCanLoadExtJs(true);
        $this->_addContent($this->getLayout()->createBlock('helpdesk/adminhtml_pattern_edit'));
        $this->renderLayout();
    }

    public function editAction ()
    {
        $pattern = $this->_initPattern();

        if ($pattern->getId()) {
            $this->_title($this->__("Edit Pattern '%s'", $pattern->getName()));
            $this->_initAction();
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Spam Filter Patterns'),
                    Mage::helper('adminhtml')->__('Spam Filter Patterns'), $this->getUrl('*/*/'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Edit Pattern '),
                    Mage::helper('adminhtml')->__('Edit Pattern '));

            $this->getLayout()
                ->getBlock('head')
                ->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('helpdesk/adminhtml_pattern_edit'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('The Pattern does not exist.'));
            $this->_redirect('*/*/');
        }
    }

    public function saveAction ()
    {
        if ($data = $this->getRequest()->getPost()) {

            $pattern = $this->_initPattern();
            $pattern->addData($data);
            //format date to standart
            // $format = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
            // Mage::helper('mstcore/date')->formatDateForSave($pattern, 'active_from', $format);
            // Mage::helper('mstcore/date')->formatDateForSave($pattern, 'active_to', $format);

            try {
                $pattern->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Pattern was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $pattern->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Unable to find Pattern to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction ()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $pattern = Mage::getModel('helpdesk/pattern');

                $pattern->setId($this->getRequest()
                    ->getParam('id'))
                    ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__('Pattern was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()
                    ->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massChangeAction()
    {
        $ids = $this->getRequest()->getParam('pattern_id');
        if (!is_array($ids)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select Pattern(s)'));
        } else {
            try {
                $isActive = $this->getRequest()->getParam('is_active');
                foreach ($ids as $id) {
                    $pattern = Mage::getModel('helpdesk/pattern')->load($id);
                    $pattern->setIsActive($isActive);
                    $pattern->save();
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
        $ids = $this->getRequest()->getParam('pattern_id');
        if (!is_array($ids)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select Pattern(s)'));
        } else {
            try {
                foreach ($ids as $id) {
                    $pattern = Mage::getModel('helpdesk/pattern')
                        ->setIsMassDelete(true)
                        ->load($id);
                    $pattern->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($ids)
                    )
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function _initPattern()
    {
        $pattern = Mage::getModel('helpdesk/pattern');
        if ($this->getRequest()->getParam('id')) {
            $pattern->load($this->getRequest()->getParam('id'));
        }

        Mage::register('current_pattern', $pattern);

        return $pattern;
    }

    /************************/

}