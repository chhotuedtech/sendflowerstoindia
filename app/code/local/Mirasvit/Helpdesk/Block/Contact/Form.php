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



class Mirasvit_Helpdesk_Block_Contact_Form extends Mage_Core_Block_Template
{
    public function getConfig()
    {
        return Mage::getSingleton('helpdesk/config');
    }

    public function isKbEnabled()
    {
        return Mage::helper('mstcore')->isModuleInstalled('Mirasvit_Kb') && $this->getConfig()->getContactFormIsActiveKb();
    }

    protected function getCustomer()
    {
        $customer =  Mage::getSingleton('customer/session')->getCustomer();
        if ($customer->getId() > 0) {
            return $customer;
        }
        return false;
    }

    public function isSecure()
    {
        $isHTTPS = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443);
        return $isHTTPS;
    }

    public function getContactUrl()
    {
        Mage::getSingleton('customer/session')->setFeedbackUrl($this->helper('core/url')->getCurrentUrl());
        if ($this->isKbEnabled()) {
            return $this->getKbUrl();
        } else {
            return $this->getFormUrl();
        }
    }

    public function getFormUrl()
    {
        return Mage::getUrl('helpdesk/contact/form', array('_secure' => $this->isSecure(), 's' => $this->getSearchQuery()));
    }

    public function getPostUrl()
    {
        return Mage::getUrl('helpdesk/contact/postmessage', array('_secure' => $this->isSecure()));
    }

    public function getKbResultUrl()
    {
        return Mage::getUrl('helpdesk/contact/kbresult', array('_secure' => $this->isSecure()));
    }

    public function getKbUrl()
    {
        return Mage::getUrl('helpdesk/contact/kb', array('_secure' => $this->isSecure(), 's' => $this->getSearchQuery()));
    }

    public function getAllResultsUrl()
    {
        return Mage::getUrl('kb/article/s', array('_secure' => $this->isSecure(), 's' => $this->getSearchQuery()));
    }

    public function getSearchQuery()
    {
        return Mage::registry('search_query');
    }

    public function getArticleCollection() {
        return Mage::registry('search_result');
    }

    public function isRatingEnabled()
    {
        return Mage::getSingleton('kb/config')->getGeneralIsRatingEnabled();
    }

    public function getCustomFields()
    {
        $collection = Mage::helper('helpdesk/field')->getContactFormCollection();
        return $collection;
    }

    public function getInputHtml($field)
    {
        return Mage::helper('helpdesk/field')->getInputHtml($field);
    }
}

