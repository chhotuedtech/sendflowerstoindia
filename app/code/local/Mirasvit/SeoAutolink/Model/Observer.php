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


class Mirasvit_SeoAutolink_Model_Observer extends Varien_Object
{
    public function getConfig() {
        return Mage::getSingleton('seoautolink/config');
    }

    public function addCustomAttributeOutputHandler(Varien_Event_Observer $observer)
    {
        $outputHelper = $observer->getEvent()->getHelper();
        $outputHelper->addHandler('productAttribute', $this);
        $outputHelper->addHandler('categoryAttribute', $this);
    }

    public function categoryAttribute(Mage_Catalog_Helper_Output $outputHelper, $outputHtml, $params)
    {
        if (!Mage::registry('current_category')) {
            return $outputHtml;
        }
        if ($params['attribute'] == 'description' &&
               in_array(Mirasvit_SeoAutolink_Model_Config_Source_Target::CATEGORY_DESCRIPTION, $this->getConfig()->getTarget())) {
            $outputHtml = Mage::helper('seoautolink')->addLinks($outputHtml);
        }
        return $outputHtml;
    }

    public function productAttribute(Mage_Catalog_Helper_Output $outputHelper, $outputHtml, $params)
    {
        if (!Mage::registry('current_product')) {
            return $outputHtml;
        }
        if ($params['attribute'] == 'short_description' &&
               in_array(Mirasvit_SeoAutolink_Model_Config_Source_Target::PRODUCT_SHORT_DESCRIPTION, $this->getConfig()->getTarget())) {
            $outputHtml = Mage::helper('seoautolink')->addLinks($outputHtml);
        }
        if ($params['attribute'] == 'description' &&
               in_array(Mirasvit_SeoAutolink_Model_Config_Source_Target::PRODUCT_FULL_DESCRIPTION, $this->getConfig()->getTarget())) {
            $outputHtml = Mage::helper('seoautolink')->addLinks($outputHtml);
        }
        return $outputHtml;
    }

    public function cmsPageOutputHandler($e) {
        if (!in_array(Mirasvit_SeoAutolink_Model_Config_Source_Target::CMS_PAGE, $this->getConfig()->getTarget())) {
            return;
        }

        $page = $e->getPage();
        $outputHtml = Mage::helper('seoautolink')->addLinks($page->getContent());
        $page->setContent($outputHtml);
    }

//can't add link to reviews, because they use htmlspecialchars
//    public function reviewLoadAfterEvent($e) {
//        if (true || in_array(Mirasvit_SeoAutolink_Model_Config_Source_Target::CATEGORY_DESCRIPTION, $this->getConfig()->getTarget())) {
//            $review = $e->getDataObject();
//
//            $outputHtml = $review->getDetail();
//            $outputHtml = Mage::helper('seoautolink')->addLinks($outputHtml);
//            $review->setDetail($outputHtml);
//        }
//        return $this;
//    }
}