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


if (Mage::helper('mstcore')->isModuleInstalled('CorlleteLab_Imagezoom') && class_exists('CorlleteLab_Imagezoom_Block_Html_Head')) {
    abstract class Mirasvit_Seo_Block_Html_Head_Abstract extends CorlleteLab_Imagezoom_Block_Html_Head {

    }
} elseif (Mage::helper('core')->isModuleEnabled('Fooman_Speedster') && class_exists('Fooman_Speedster_Block_Page_Html_Head')) {
    abstract class Mirasvit_Seo_Block_Html_Head_Abstract extends Fooman_Speedster_Block_Page_Html_Head {

    }
} elseif (Mage::helper('core')->isModuleEnabled('Fooman_SpeedsterAdvanced') && class_exists('Fooman_SpeedsterAdvanced_Block_Page_Html_Head')) {
    abstract class Mirasvit_Seo_Block_Html_Head_Abstract extends Fooman_SpeedsterAdvanced_Block_Page_Html_Head {

    }
} elseif (Mage::helper('core')->isModuleEnabled('Conekta_Card') && class_exists('Conekta_Card_Block_Page_Html_Head')) {
    abstract class Mirasvit_Seo_Block_Html_Head_Abstract extends Conekta_Card_Block_Page_Html_Head {

    }
} else {
    abstract class Mirasvit_Seo_Block_Html_Head_Abstract extends Mage_Page_Block_Html_Head {

    }
}

class Mirasvit_Seo_Block_Html_Head extends Mirasvit_Seo_Block_Html_Head_Abstract
{
    protected function _construct()
    {
        parent::_construct();
        $this->setupCanonicalUrl();
    }

    public function getConfig()
    {
    	return Mage::getSingleton('seo/config');
    }

    public function getRobots()
    {
        if (!$this->getAction()) {
            return;
        }
        if ($product = Mage::registry('current_product')) {
            if ($robots = Mage::helper('seo')->getMetaRobotsByCode($product->getSeoMetaRobots())) {
                return $robots;
            }
        }
    	$fullAction = $this->getAction()->getFullActionName();
        foreach ($this->getConfig()->getNoindexPages() as $record) {
            //for patterns like filterattribute_(arttribte_code) and filterattribute_(Nlevel)
            if (strpos($record['pattern'], 'filterattribute_(') !== false
                && $fullAction == 'catalog_category_view') {
                    if ($this->_checkFilterPattern($record['pattern'])) {
                         return Mage::helper('seo')->getMetaRobotsByCode($record->getOption());
                    }
            }

            if (Mage::helper('seo')->checkPattern($fullAction, $record->getPattern())
                || Mage::helper('seo')->checkPattern(Mage::helper('seo')->getBaseUri(), $record['pattern'])) {
                return Mage::helper('seo')->getMetaRobotsByCode($record->getOption());
            }
        }

        return parent::getRobots();
    }

    protected function _checkFilterPattern($pattern)
    {
        $urlParams = Mage::app()->getFrontController()->getRequest()->getQuery();
        $currentFilters = Mage::getSingleton('catalog/layer')->getFilterableAttributes()->getData();
        $filterArr = array();
        foreach ($currentFilters as $filterAttr) {
            if (isset($filterAttr['attribute_code'])) {
                $filterArr[] = $filterAttr['attribute_code'];
            }
        }

        $usedFilters = array();
        if (!empty($filterArr)) {
            foreach ($urlParams as $keyParam => $valParam) {
                if (in_array($keyParam, $filterArr)) {
                    $usedFilters[] = $keyParam;
                }
            }
        }

        if (!empty($usedFilters)) {
            $usedFiltersCount = count($usedFilters);
            if (strpos($pattern, 'level)') !== false) {
                preg_match('/filterattribute_\\((\d{1})level/', trim($pattern), $levelNumber);
                if (isset($levelNumber[1])) {
                    if ($levelNumber[1] == $usedFiltersCount) {
                        return true;
                    }
                }
            }

            foreach($usedFilters as $useFilterVal) {
                if (strpos($pattern, '(' . $useFilterVal . ')') !== false) {
                    return true;
                }
            }
        }

        return false;
    }

    public function setupCanonicalUrl()
    {
    	if (!$this->getConfig()->isAddCanonicalUrl()) {
    		return;
    	}

        if (!$this->getAction()) {
            return;
        }

    	$fullAction = $this->getAction()->getFullActionName();
        foreach ($this->getConfig()->getCanonicalUrlIgnorePages() as $page) {
            if (Mage::helper('seo')->checkPattern($fullAction, $page)
                || Mage::helper('seo')->checkPattern(Mage::helper('seo')->getBaseUri(), $page)) {
                return;
            }
        }

        $productActions = array(
            'catalog_product_view',
            'review_product_list',
            'review_product_view',
            'productquestions_show_index',
        );

        $productCanonicalStoreId = false;
        if (in_array($fullAction, $productActions)) {
            $product = Mage::registry('current_product');
            if (!$product) {
                return;
            }
            $productCanonicalStoreId = $product->getSeoCanonicalStoreId(); //canonical store id for current product

            $collection = Mage::getModel('catalog/product')->getCollection()
                ->addFieldToFilter('entity_id', $product->getId())
                ->addStoreFilter()
                ->addUrlRewrite();

            $product      = $collection->getFirstItem();
            $canonicalUrl = $product->getProductUrl();
        } elseif ($fullAction == 'catalog_category_view') {
            $category     = Mage::registry('current_category');
            if (!$category) {
                return;
            }
            $canonicalUrl = $category->getUrl();
        } else {
			$canonicalUrl = Mage::helper('seo')->getBaseUri();
			$canonicalUrl = Mage::getUrl('', array('_direct' => ltrim($canonicalUrl, '/')));
            $canonicalUrl = strtok($canonicalUrl, '?');
        }

        //setup crossdomian URL if this option is enabled
        if (($crossDomainStore = $this->getConfig()->getCrossDomainStore()) || $productCanonicalStoreId) {
            if ($productCanonicalStoreId) {
                $crossDomainStore = $productCanonicalStoreId;
            }
            $mainBaseUrl = Mage::app()->getStore($crossDomainStore)->getBaseUrl();
            $currentBaseUrl = Mage::app()->getStore()->getBaseUrl();
            $canonicalUrl = str_replace($currentBaseUrl, $mainBaseUrl, $canonicalUrl);

            if (Mage::app()->getStore()->isCurrentlySecure()) {
                $canonicalUrl = str_replace('http://', 'https://', $canonicalUrl);
            }
        }

        if (false && isset($product)) { //Ð²Ð¾Ð·Ð¼Ð¾Ð¶Ð½Ð¾ Ð² Ð¿ÐµÑÑÐ¿ÐµÐºÑÐ¸Ð²Ðµ Ð²ÑÐ²ÐµÑÑÐ¸ ÑÑÐ¾ Ð² ÐºÐ¾Ð½ÑÐ¸Ð³ÑÑÐ°ÑÐ¸Ñ. Ñ.Ðº. ÑÑÐ¾ Ð½ÑÐ¶Ð½Ð¾ ÑÐ¾Ð»ÑÐºÐ¾ Ð² Ð½ÐµÐºÐ¾ÑÐ¾ÑÑÑ ÑÐ»ÑÑÐ°ÑÑ.
            // ÐµÑÐ»Ð¸ ÑÑÐµÐ´Ð¸ ÐºÐ°ÑÐµÐ³Ð¾ÑÐ¸Ð¹ Ð¿ÑÐ¾Ð´ÑÐºÑÐ° ÐµÑÑÑ ÐºÐ¾ÑÐ½ÐµÐ²Ð°Ñ ÐºÐ°ÑÐµÐ³Ð¾ÑÐ¸Ñ, ÑÐ¾ ÑÑÑÐ°Ð½Ð°Ð²Ð»Ð¸Ð²Ð°ÐµÐ¼ ÐµÐµ Ð´Ð»Ñ ÐºÐ°Ð½Ð¾Ð½Ð¸ÐºÐ°Ð»
            $categoryIds = $product->getCategoryIds();

            if (Mage::helper('catalog/category_flat')->isEnabled()) {
                $currentStore = Mage::app()->getStore()->getId();
                foreach (Mage::app()->getStores() as $store) {
                    Mage::app()->setCurrentStore($store->getId());
                    $collection = Mage::getModel('catalog/category')->getCollection()
                        ->addFieldToFilter('entity_id', $categoryIds)
                        ->addFieldToFilter('level', 1);
                    if ($collection->count()) {
                        $mainBaseUrl = $store->getBaseUrl();
                        break;
                    }
                }
                Mage::app()->setCurrentStore($currentStore);
                if (isset($mainBaseUrl)) {
                    $currentBaseUrl = Mage::app()->getStore()->getBaseUrl();
                    $canonicalUrl = str_replace($currentBaseUrl, $mainBaseUrl, $canonicalUrl);
                }
            } else {
                $collection = Mage::getModel('catalog/category')->getCollection()
                        ->addFieldToFilter('entity_id', $categoryIds)
                        ->addFieldToFilter('level', 1);
                if ($collection->count()) {
                    $rootCategory = $collection->getFirstItem();
                    foreach (Mage::app()->getStores() as $store) {
                          if ($store->getRootCategoryId() == $rootCategory->getId()) {
                            $mainBaseUrl = $store->getBaseUrl();
                            $currentBaseUrl = Mage::app()->getStore()->getBaseUrl();
                            $canonicalUrl = str_replace($currentBaseUrl, $mainBaseUrl, $canonicalUrl);
                          }
                    }
                }
            }
        }


        $page = (int)Mage::app()->getRequest()->getParam('p');
        if ($page > 1) {
            $canonicalUrl .= "?p=$page";
        }

        $this->addLinkRel('canonical', $canonicalUrl);
    }
}
