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


class Mirasvit_Seo_Helper_Data extends Mage_Core_Helper_Abstract
{
    protected $_product;
    protected $_category;
    protected $_parseObjects = array();
    protected $_additional = array();
    protected $config;

    public function __construct()
    {
        $this->_config = Mage::getModel('seo/config');
    }

    public function getBaseUri()
    {
        $baseStoreUri = parse_url(Mage::getUrl(), PHP_URL_PATH);

        if ($baseStoreUri  == '/') {
            return $_SERVER['REQUEST_URI'];
        } else {
            $requestUri = $_SERVER['REQUEST_URI'];
            $prepareUri = str_replace($baseStoreUri, '', $requestUri);
            if (substr($requestUri, 0, 1) == '/') {
                return $prepareUri;
            } else {
                return DS . $prepareUri;
            }
        }
    }

    protected function checkRewrite()
    {
        $uid = Mage::helper('mstcore/debug')->start();
        $uri = $this->getBaseUri();
        $collection = Mage::getModel('seo/rewrite')->getCollection()
            ->addStoreFilter(Mage::app()->getStore())
            ->addEnableFilter();
        $resultRewrite = false;
        foreach ($collection as $rewrite) {
            if ($this->checkPattern($uri, $rewrite->getUrl())) {
                $resultRewrite = $rewrite;
                break;
            }
        }

        if ($resultRewrite) {
            $this->_product = Mage::registry('current_product');
            if (!$this->_product) {
                $this->_product = Mage::registry('product');
            }
            if ($this->_product) {
                $this->_parseObjects['product'] = $this->_product;
                $this->setAdditionalVariable('product', 'final_price', $this->_product->getFinalPrice());
                $this->setAdditionalVariable('product', 'url', $this->_product->getProductUrl());
            }

            $this->_category = Mage::registry('current_category');

            if ($this->_category) {
                $this->_parseObjects['category'] = $this->_category;
                if($this->_category && $parent = $this->_category->getParentCategory()) {
                    if (Mage::app()->getStore()->getRootCategoryId() != $parent->getId()) {
                        if (($parentParent = $parent->getParentCategory())
                            && (Mage::app()->getStore()->getRootCategoryId() != $parentParent->getId())) {
                            $this->setAdditionalVariable('category', 'parent_parent_name', $parentParent->getName());
                        }
                        $this->setAdditionalVariable('category', 'parent_name', $parent->getName());
                        $this->setAdditionalVariable('category', 'parent_url', $parent->getUrl());
                    }
                    $this->setAdditionalVariable('category', 'url', $this->_category->getUrl());
                    //alias to meta_title
                    $this->setAdditionalVariable('category', 'page_title', $this->_category->getMetaTitle());
                }
            }

            $storeId = Mage::app()->getStore();
            $resultRewrite->setTitle(Mage::helper('seo/parse')->parse($resultRewrite->getTitle(), $this->_parseObjects, $this->_additional, $storeId));
            $resultRewrite->setDescription(Mage::helper('seo/parse')->parse($resultRewrite->getDescription(), $this->_parseObjects, $this->_additional, $storeId));
            $resultRewrite->setMetaTitle(Mage::helper('seo/parse')->parse($resultRewrite->getMetaTitle(), $this->_parseObjects, $this->_additional, $storeId));
            $resultRewrite->setMetaKeywords(Mage::helper('seo/parse')->parse($resultRewrite->getMetaKeywords(), $this->_parseObjects, $this->_additional, $storeId));
            $resultRewrite->setMetaDescription(Mage::helper('seo/parse')->parse($resultRewrite->getMetaDescription(), $this->_parseObjects, $this->_additional, $storeId));
        }

        Mage::helper('mstcore/debug')->end($uid, array(
            'uri'         => $uri,
            'rewrite_id'  => $resultRewrite? $resultRewrite->getId() : false,
            'rewrite_url' => $resultRewrite? $resultRewrite->getUrl() : false,
        ));

        return $resultRewrite;
    }

    protected function setAdditionalVariable($objectName, $variableName, $value)
    {
        $this->_additional[$objectName][$variableName] = $value;
    }

    /**
     * ÐÐ¾Ð·Ð²ÑÐ°ÑÐ°ÐµÑ ÑÐµÐ¾-Ð´Ð°Ð½Ð½ÑÐµ Ð´Ð»Ñ ÑÐµÐºÑÑÐµÐ¹ ÑÑÑÐ°Ð½Ð¸ÑÑ
     *
     * ÐÐ¾Ð·Ð²ÑÐ°ÑÐ°ÐµÑ Ð¾Ð±ÑÐµÐºÑ Ñ Ð¼ÐµÑÐ¾Ð´Ð°Ð¼Ð¸:
     * getTitle() - Ð·Ð°Ð³Ð¾Ð»Ð¾Ð²Ð¾Ðº H1
     * getDescription() - SEO ÑÐµÐºÑÑ
     * getMetaTitle()
     * getMetaKeyword()
     * getMetaDescription()
     *
     * ÐÑÐ»Ð¸ Ð´Ð»Ñ Ð´Ð°Ð½Ð½Ð¾Ð¹ ÑÑÑÐ°Ð½Ð¸ÑÑ Ð½ÐµÑ Ð¡ÐÐ, ÑÐ¾ Ð²Ð¾Ð·Ð²ÑÐ°ÑÐ°ÐµÑ Ð¿ÑÑÑÐ¾Ð¹ Varien_Object
     *
     * @return Varien_Object $result
     */
    public function getCurrentSeo()
    {
        if (Mage::app()->getStore()->getCode() == 'admin') {
            return new Varien_Object();
        }

        $uid = Mage::helper('mstcore/debug')->start();

        $isCategory = Mage::registry('current_category') || Mage::registry('category');

        if ($isCategory) {
            $filters = Mage::getSingleton('catalog/layer')->getState()->getFilters();
            $isFilter = count($filters) > 0;
        }

        if (Mage::registry('current_product') || Mage::registry('product')) {
            $seo = Mage::getSingleton('seo/object_product');
        } elseif ($isCategory && $isFilter) {
            $seo =  Mage::getSingleton('seo/object_filter');
        } elseif ($isCategory) {
            $seo =  Mage::getSingleton('seo/object_category');
        } else {
            $seo = new Varien_Object();
        }

        if ($seoRewrite = $this->checkRewrite()) {
            foreach ($seoRewrite->getData() as $k=>$v) {
                if ($v) {
                   $seo->setData($k, $v);
                }
            }
        }

        if (Mage::registry('current_category')) {
            $page = Mage::app()->getFrontController()->getRequest()->getParam('p');
            if ($page > 1) {
                $seo->setMetaTitle(Mage::helper('seo')->__("Page %s | %s", $page, $seo->getMetaTitle()));
                $seo->setDescription('');
            }
        }

        Mage::helper('mstcore/debug')->end($uid, $seo->getData());

        return $seo;
    }

    public function checkPattern($string, $pattern, $caseSensative = false)
    {
        if (!$caseSensative) {
            $string  = strtolower($string);
            $pattern = strtolower($pattern);
        }

        $parts = explode('*', $pattern);
        $index = 0;

        $shouldBeFirst = true;
        $shouldBeLast  = true;

        foreach ($parts as $part) {
            if ($part == '') {
                $shouldBeFirst = false;
                continue;
            }

            $index = strpos($string, $part, $index);

            if ($index === false) {
                return false;
            }

            if ($shouldBeFirst && $index > 0) {
                return false;
            }

            $shouldBeFirst = false;
            $index += strlen($part);
        }

        if (count($parts) == 1) {
            return $string == $pattern;
        }

        $last = end($parts);
        if ($last == '') {
            return true;
        }

        if (strrpos($string, $last) === false) {
            return false;
        }

        if(strlen($string) - strlen($last) - strrpos($string, $last) > 0) {
          return false;
        }

        return true;
    }

	public function cleanMetaTag($tag) {
        $tag = strip_tags($tag);
        //$tag = html_entity_decode($tag);//for case we have tags like &nbsp; added by some extensions //in some hosting adds unrecognized symbols
        //$tag = preg_replace('/[^a-zA-Z0-9_ \-()\/%-&]/s', '', $tag);
        $tag = preg_replace('/\s{2,}/', ' ', $tag); //remove unnecessary spaces
        $tag = preg_replace('/\"/', ' ', $tag); //remove " because it destroys html
        $tag = trim($tag);

	    return $tag;
	}

    public function getMetaRobotsByCode($code)
    {
        switch ($code) {
            case Mirasvit_Seo_Model_Config::NOINDEX_NOFOLLOW:
               return 'NOINDEX,NOFOLLOW';
            break;
            case Mirasvit_Seo_Model_Config::NOINDEX_FOLLOW:
               return 'NOINDEX,FOLLOW';
            break;
            case Mirasvit_Seo_Model_Config::INDEX_NOFOLLOW:
               return 'INDEX,NOFOLLOW';
            break;
        };
    }

    public function getProductSeoCategory($product)
    {
        $categoryId = $product->getSeoCategory();
        $category = Mage::registry('current_category');

        if ($category && !$categoryId) {
            return $category;
        }

        if (!$categoryId) {
            $categoryIds = $product->getCategoryIds();
            if (count($categoryIds) > 0) {
                //we need this for multi websites configuration
                $categoryRootId = Mage::app()->getStore()->getRootCategoryId();
                $category = Mage::getModel('catalog/category')->getCollection()
                            ->addFieldToFilter('path', array('like' => "%/{$categoryRootId}/%"))
                            ->addFieldToFilter('entity_id', $categoryIds)
                            ->setOrder('level', 'desc')
                            ->setOrder('entity_id', 'desc')
                            ->getFirstItem()
                        ;
                $categoryId = $category->getId();
            }
        }
        //load category with flat data attributes
        $category = Mage::getModel('catalog/category')->load($categoryId);
        return $category;
    }

    public function getInactiveCategories() {
        $inactiveCategories = Mage::getModel('catalog/category')
                            ->getCollection()
                            ->setStoreId(Mage::app()->getStore()->getId())
                            ->addFieldToFilter('is_active', array('neq'=>'1'))
                            ->addAttributeToSelect('*')
                        ;
        $inactiveCat = array();
        foreach($inactiveCategories as $inactiveCategory) {
            $inactiveCat[] = $inactiveCategory->getId();
        }

        return $inactiveCat;
    }
}
