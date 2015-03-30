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


class Mirasvit_Helpdesk_Adminhtml_RuleController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction ()
    {
        $this->loadLayout()->_setActiveMenu('helpdesk');

        return $this;
    }

    public function indexAction ()
    {
        $this->_title($this->__('Workflow Rules'));
        $this->_initAction();
        $this->_addContent($this->getLayout()
            ->createBlock('helpdesk/adminhtml_rule'));
        $this->renderLayout();
    }

    public function addAction ()
    {
        $this->_title($this->__('New Rule'));

        $this->_initRule();

        $this->_initAction();
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Rule  Manager'),
                Mage::helper('adminhtml')->__('Rule Manager'), $this->getUrl('*/*/'));
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Add Rule '), Mage::helper('adminhtml')->__('Add Rule'));

        $this->getLayout()
            ->getBlock('head')
            ->setCanLoadExtJs(true);
        $this->_addContent($this->getLayout()->createBlock('helpdesk/adminhtml_rule_edit'))
                ->_addLeft($this->getLayout()->createBlock('helpdesk/adminhtml_rule_edit_tabs'));
        $this->getLayout()->getBlock('head')->setCanLoadRulesJs(true);
        $this->renderLayout();
    }

    public function editAction ()
    {
        $rule = $this->_initRule();

        if ($rule->getId()) {
            $this->_title($this->__("Edit Rule '%s'", $rule->getName()));
            $this->_initAction();
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Workflow Rules'),
                    Mage::helper('adminhtml')->__('Workflow Rules'), $this->getUrl('*/*/'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Edit Rule '),
                    Mage::helper('adminhtml')->__('Edit Rule '));

            $this->getLayout()
                ->getBlock('head')
                ->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('helpdesk/adminhtml_rule_edit'))
                    ->_addLeft($this->getLayout()->createBlock('helpdesk/adminhtml_rule_edit_tabs'));
            $this->getLayout()->getBlock('head')->setCanLoadRulesJs(true);

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('The Rule does not exist.'));
            $this->_redirect('*/*/');
        }
    }

    public function saveAction ()
    {
        if ($data = $this->getRequest()->getPost()) {

            $rule = $this->_initRule();
            if (!$data['priority_id']) {
                $data['priority_id'] = null;
            }
            if (!$data['department_id']) {
                $data['department_id'] = null;
            }
            if (!$data['status_id']) {
                $data['status_id'] = null;
            }
            if (!$data['user_id']) {
                $data['user_id'] = null;
            }

            $rule->addData($data);
            if (isset($data['rule'])) {
                $rule->loadPost($data['rule']);
            }
            //format date to standart
            // $format = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
            // Mage::helper('mstcore/date')->formatDateForSave($rule, 'active_from', $format);
            // Mage::helper('mstcore/date')->formatDateForSave($rule, 'active_to', $format);

            try {
                $rule->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Rule was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $rule->getId()));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Unable to find Rule to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction ()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $rule = Mage::getModel('helpdesk/rule');

                $rule->setId($this->getRequest()
                    ->getParam('id'))
                    ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__('Rule was successfully deleted'));
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
        $ids = $this->getRequest()->getParam('rule_id');
        if (!is_array($ids)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select Rule(s)'));
        } else {
            try {
                $isActive = $this->getRequest()->getParam('is_active');
                foreach ($ids as $id) {
                    $rule = Mage::getModel('helpdesk/rule')->load($id);
                    $rule->setIsActive($isActive);
                    $rule->save();
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
        $ids = $this->getRequest()->getParam('rule_id');
        if (!is_array($ids)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select Rule(s)'));
        } else {
            try {
                foreach ($ids as $id) {
                    $rule = Mage::getModel('helpdesk/rule')
                        ->setIsMassDelete(true)
                        ->load($id);
                    $rule->delete();
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

    public function _initRule()
    {
        $rule = Mage::getModel('helpdesk/rule');
        if ($this->getRequest()->getParam('id')) {
            $rule->load($this->getRequest()->getParam('id'));
        }

        Mage::register('current_rule', $rule);

        return $rule;
    }
    public function newConditionHtmlAction()
    {
        $id      = $this->getRequest()->getParam('id');
        $typeArr = explode('|', str_replace('-', '/', $this->getRequest()->getParam('type')));
        $type    = $typeArr[0];

        $model = Mage::getModel($type)
            ->setId($id)
            ->setType($type)
            ->setRule(Mage::getModel('helpdesk/rule'))
            ->setPrefix('conditions');

        if (!empty($typeArr[1])) {
            $model->setAttribute($typeArr[1]);
        }

        if ($model instanceof Mage_Rule_Model_Condition_Abstract) {
            $model->setJsFormObject($this->getRequest()->getParam('form'));
            $html = $model->asHtmlRecursive();
        } else {
            $html = '';
        }
        $this->getResponse()->setBody($html);
    }

    /************************/

}