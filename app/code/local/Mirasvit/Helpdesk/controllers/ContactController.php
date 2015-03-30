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


class Mirasvit_Helpdesk_ContactController extends Mage_Core_Controller_Front_Action
{
    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }

    public function formAction()
    {
        Mage::register('search_query', $this->getRequest()->getParam('s'));
        $this->loadLayout();
        $this->renderLayout();
    }

    public function kbAction()
    {
        Mage::register('search_query', $this->getRequest()->getParam('s'));
        $this->loadLayout();
        $this->renderLayout();
    }

    public function postmessageAction()
    {
        $session  = $this->_getSession();
        $customer = $session->getCustomer();
        $fakeEmail = $this->getRequest()->getParam('email');
        if ($fakeEmail === '') { //email should not be null and should be empty
            Mage::helper('helpdesk/process')->createFromPost($this->getRequest()->getParams(), Mirasvit_Helpdesk_Model_Config::CHANNEL_FEEDBACK_TAB);
        }
        $session->addSuccess($this->__('Message submitted. Thanks!'));
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->renderLayout();
    }

    //@todo move to core
    protected function isSecure()
    {
        $isHTTPS = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443);
        return $isHTTPS;
    }

    public function kbresultAction()
    {
        $q = $this->getRequest()->getParam('s');
        $collection = $this->getArticleCollection($q);
        if (!$collection->count()) {
            $this->_redirect('helpdesk/contact/form', array('_secure' => $this->isSecure(), 's'=>$q));
            return;
        }
        Mage::register('search_query', $q);
        Mage::register('search_result', $collection);

        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->renderLayout();
    }

    public function getArticleCollection($q)
    {
        $collection = Mage::getModel('kb/article')->getCollection()
            ->addFieldToFilter('main_table.is_active', true)
            ->addStoreIdFilter(Mage::app()->getStore()->getId())
            ;
        Mage::helper('kb')->addSearchFilter($collection, $q);
        $collection->setPageSize(4);
        return $collection;
    }
}
