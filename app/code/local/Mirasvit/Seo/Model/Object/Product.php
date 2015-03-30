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


class Mirasvit_Seo_Model_Object_Product extends Mirasvit_Seo_Model_Object_Abstract
{
    protected $_product;
    protected $_category;
    protected $_parseObjects = array();

    public function _construct()
    {
        $uid = Mage::helper('mstcore/debug')->start();

        parent::_construct();
        $this->_product = Mage::registry('current_product');
        if (!$this->_product) {
            $this->_product = Mage::registry('product');
        }
        if (!$this->_product) {
            return;
        }


        $this->_parseObjects['product'] = $this->_product;

        $this->setAdditionalVariable('product', 'url', $this->_product->getProductUrl());
        $this->setAdditionalVariable('product', 'final_price', $this->_product->getFinalPrice());


        $categoryId = $this->_product->getSeoCategory();
        $this->_category = Mage::registry('current_category');

        if ($this->_category && !$categoryId) {
            $this->_parseObjects['category'] = $this->_category;
        } elseif ($this->_product) {
            if (!$categoryId) {
                $categoryIds = $this->_product->getCategoryIds();
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
            $this->_category = $category;
            $this->_parseObjects['category'] = $category;
            if (!Mage::registry('seo_current_category')) {// to be sure that register will not be done twice
                Mage::register('seo_current_category', $category);
            };
        }

        $this->_parseObjects['store'] = Mage::getModel('seo/object_store');

        $this->init();

        Mage::helper('mstcore/debug')->end($uid, array(
            'product_id'  => $this->_parseObjects['product']->getId(), //id Ð¿ÑÐ¾Ð´ÑÐºÑÐ°, Ð´Ð°Ð½Ð½ÑÐµ Ð¿Ð¾ ÐºÐ¾ÑÐ¾ÑÐ¾Ð¼Ñ Ð±ÑÐ´ÑÑ Ð¸ÑÐ¿Ð¾Ð»ÑÐ·Ð¾Ð²Ð°ÑÑÑÑ Ð² ÑÐ°Ð±Ð»Ð¾Ð½Ð°Ñ
            'category_id' => isset($this->_parseObjects['category'])?$this->_parseObjects['category']->getId():false,//id ÐºÐ°ÑÐµÐ³Ð¾ÑÐ¸Ð¸, Ð´Ð°Ð½Ð½ÑÐµ Ð¿Ð¾ ÐºÐ¾ÑÐ¾ÑÐ¾Ð¹ Ð±ÑÐ´ÑÑ Ð¸ÑÐ¿Ð¾Ð»ÑÐ·Ð¾Ð²Ð°ÑÑÑÑ Ð² ÑÐ°Ð±Ð»Ð¾Ð½Ð°Ñ
            'store_id'    => isset($this->_parseObjects['store'])?$this->_parseObjects['store']->getId():false//id ÑÑÐ¾ÑÐ°, Ð´Ð°Ð½Ð½ÑÐµ Ð¿Ð¾ ÐºÐ¾ÑÐ¾ÑÐ¾Ð¼Ñ Ð±ÑÐ´ÑÑ Ð¸ÑÐ¿Ð¾Ð»ÑÐ·Ð¾Ð²Ð°ÑÑÑÑ Ð² ÑÐ°Ð±Ð»Ð¾Ð½Ð°Ñ
        ));
    }

    public function getConfig()
    {
        return Mage::getSingleton('seo/config');
    }

    protected function init()
    {
        $uid = Mage::helper('mstcore/debug')->start();

        if ($this->_category) {
            $categorySeo = Mage::getSingleton('seo/object_category');
            $this->setMetaTitle($categorySeo->getProductMetaTitle());
            $this->setMetaKeywords($categorySeo->getProductMetaKeywords());
            $this->setMetaDescription($categorySeo->getProductMetaDescription());

            $this->setTitle($categorySeo->getProductTitle());
            $this->setShortDescription($categorySeo->getProductShortDescription());
            $this->setFullDescription($categorySeo->getProductFullDescription());
            $this->setDescription($categorySeo->getProductDescription());

            $this->addAdditionalVariables($categorySeo->getAdditionalVariables());
        }

        if ($this->getConfig()->isProductMetaTagsUsed()) {
            if ($this->_product->getMetaTitle()) {
                $this->setMetaTitle($this->parse($this->_product->getMetaTitle()));
            }

            if ($this->_product->getMetaKeyword()) {
                $this->setMetaKeywords($this->parse($this->_product->getMetaKeyword()));
            }

            if ($this->_product->getMetaDescription()) {
                $this->setMetaDescription($this->parse($this->_product->getMetaDescription()));
            }
        } else {
            $this->setMetaTitle($this->parse($categorySeo->getProductMetaTitle()));
            $this->setMetaKeywords($this->parse($categorySeo->getProductMetaKeywords()));
            $this->setMetaDescription($this->parse($categorySeo->getProductMetaDescription()));
        }

        if (!$this->getTitle()) {
            $this->setTitle($this->_product->getName());
        }

        if (!$this->getMetaTitle()) {
            $this->setMetaTitle($this->_product->getName());
        }

        if (!$this->getMetaKeywords()) {
            $this->setMetaKeywords($this->_product->getName());
        }

        if (!$this->getMetaDescription()) {
            $this->setMetaDescription(Mage::helper('core/string')->substr($this->_product->getDescription(), 0, 255));
        }

        Mage::helper('mstcore/debug')->end($uid, array(
            'this' => $this->getData(),
        ));
    }
}