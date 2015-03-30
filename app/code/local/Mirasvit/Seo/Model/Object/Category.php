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


class Mirasvit_Seo_Model_Object_Category extends Mirasvit_Seo_Model_Object_Abstract
{
    protected $_product;
    protected $_category;
    protected $_parseObjects = array();

    public function _construct()
    {
        $uid = Mage::helper('mstcore/debug')->start();

        parent::_construct();
        $this->_category = Mage::registry('seo_current_category');

        if (!$this->_category) {
            $this->_category = Mage::registry('current_category');
        }

        if (!$this->_category) {
            return;
        }

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
            $this->setAdditionalVariable('category', 'page_title', $this->_category->getMetaTitle());
        }
        if ($this->_category) {
            $this->_parseObjects['category'] = $this->_category;
        }

        //Ð¼Ñ Ð¼Ð¾Ð¶ÐµÐ¼ ÑÐ¾Ð·Ð´Ð°Ð²Ð°ÑÑ Ð´Ð°Ð½Ð½ÑÑ Ð¼Ð¾Ð´ÐµÐ»Ñ Ð¿ÑÐ¸ ÑÐ°ÑÑÐµÑÐµ ÑÐµÐ¾ Ð¿ÑÐ¾Ð´ÑÐºÑÐ°
        $this->_product = Mage::registry('current_product');
        if ($this->_product) {
            $this->_parseObjects['product'] = $this->_product;
            $this->setAdditionalVariable('product', 'url', $this->_product->getProductUrl());
        }
        $this->_parseObjects['store'] = Mage::getModel('seo/object_store');
        $this->_parseObjects['pager'] = Mage::getModel('seo/object_pager');
        $this->_parseObjects['filter'] = Mage::getModel('seo/object_wrapper_filter');

        $this->init();

        Mage::helper('mstcore/debug')->end($uid, array(
            'product_id'  => isset($this->_parseObjects['product'])?$this->_parseObjects['product']->getId(): false, //id Ð¿ÑÐ¾Ð´ÑÐºÑÐ°, Ð´Ð°Ð½Ð½ÑÐµ Ð¿Ð¾ ÐºÐ¾ÑÐ¾ÑÐ¾Ð¼Ñ Ð±ÑÐ´ÑÑ Ð¸ÑÐ¿Ð¾Ð»ÑÐ·Ð¾Ð²Ð°ÑÑÑÑ Ð² ÑÐ°Ð±Ð»Ð¾Ð½Ð°Ñ
            'category_id' => isset($this->_parseObjects['category'])?$this->_parseObjects['category']->getId():false,//id ÐºÐ°ÑÐµÐ³Ð¾ÑÐ¸Ð¸, Ð´Ð°Ð½Ð½ÑÐµ Ð¿Ð¾ ÐºÐ¾ÑÐ¾ÑÐ¾Ð¹ Ð±ÑÐ´ÑÑ Ð¸ÑÐ¿Ð¾Ð»ÑÐ·Ð¾Ð²Ð°ÑÑÑÑ Ð² ÑÐ°Ð±Ð»Ð¾Ð½Ð°Ñ
            'store_id'    => isset($this->_parseObjects['store'])?$this->_parseObjects['store']->getStoreId():false//id ÑÑÐ¾ÑÐ°, Ð´Ð°Ð½Ð½ÑÐµ Ð¿Ð¾ ÐºÐ¾ÑÐ¾ÑÐ¾Ð¼Ñ Ð±ÑÐ´ÑÑ Ð¸ÑÐ¿Ð¾Ð»ÑÐ·Ð¾Ð²Ð°ÑÑÑÑ Ð² ÑÐ°Ð±Ð»Ð¾Ð½Ð°Ñ
        ));
    }

    public function getConfig()
    {
        return Mage::getSingleton('seo/config');
    }

    protected function init()
    {
        $uid = Mage::helper('mstcore/debug')->start();
        // Ð²Ð½Ð°ÑÐ°Ð»Ðµ ÑÑÑÐ°Ð½Ð°Ð²Ð»Ð¸Ð²Ð°ÐµÐ¼ Ð¸Ð· ÑÐ¾Ð´Ð¸ÑÐµÐ»ÑÑÐºÐ¸Ñ ÐºÐ°ÑÐµÐ³Ð¾ÑÐ¸Ð¹. Ð½Ð¸Ð·ÑÐ¸Ð¹ Ð¿ÑÐ¸Ð¾ÑÐ¸ÑÐµÑ.
        if (!$this->_category) {
            return;
        }

        $ids = $this->_category->getParentIds();
        $collection = Mage::getModel('catalog/category')->getCollection()
                        ->addAttributeToSelect('*')
                        ->addIdFilter($ids)
                        ->addIsActiveFilter()
                        ->setOrder('level', 'asc')
                        ;
        foreach ($collection as $category) {
            $this->process($category);
        }

        //ÑÑÑÐ°Ð½Ð°Ð²Ð»Ð¸Ð²Ð°ÐµÐ¼ Ð¸Ð· ÑÐµÐºÑÑÐµÐ¹ ÐºÐ°ÑÐµÐ³Ð¾ÑÐ¸Ð¸
        $this->processCurrentCategory($this->_category);

        if (!$this->getTitle()) {
            $this->setTitle($this->_category->getName());
        }

        if (!$this->getMetaTitle()) {
            if ($this->_category->getMetaTitle()) {
                $this->setMetaTitle($this->parse($this->_category->getMetaTitle()));
            } else {
                $this->setMetaTitle($this->_category->getName());
            }
        }

        if (!$this->getMetaKeywords()) {
            if ($this->_category->getMetaKeywords()) {
                $this->setMetaKeywords($this->parse($this->_category->getMetaKeywords()));
            } else {
                $this->setMetaKeywords($this->_category->getName());
            }
        }

        if (!$this->getMetaDescription()) {
            if ($this->_category->getMetaDescription()) {
                $this->setMetaDescription($this->parse($this->_category->getMetaDescription()));
            } else {
                $this->setMetaDescription(Mage::helper('core/string')->substr($this->_category->getDescription(), 0, 255));
            }
        }

        Mage::helper('mstcore/debug')->end($uid, array(
            'this' => $this->getData(),
        ));
    }

    protected function processCurrentCategory()
    {
        $uid = Mage::helper('mstcore/debug')->start();

        if ($this->getConfig()->isSeoOfNestedCategoriesForCurrentCategoryUsed()) {
            if ($this->_category->getCategoryMetaTitleTpl()) {
                $this->setMetaTitle($this->parse($this->_category->getCategoryMetaTitleTpl()));
            }
            if ($this->_category->getCategoryMetaKeywordsTpl()) {
                $this->setMetaKeywords($this->parse($this->_category->getCategoryMetaKeywordsTpl()));
            }
            if ($this->_category->getCategoryMetaDescriptionTpl()) {
                $this->setMetaDescription($this->parse($this->_category->getCategoryMetaDescriptionTpl()));
            }
            if ($this->_category->getCategoryTitleTpl()) {
                $this->setTitle($this->parse($this->_category->getCategoryTitleTpl()));
            }
            if ($this->_category->getCategoryDescriptionTpl()) {
                $this->setDescription($this->parse($this->_category->getCategoryDescriptionTpl()));
            }
        }

        // set for current category. it has high priority.
        if ($this->getConfig()->isCategoryMetaTagsUsed()) {
            if ($this->_category->getMetaTitle()) {
                $this->setMetaTitle($this->parse($this->_category->getMetaTitle()));
            }

            if ($this->_category->getMetaKeywords()) {
                $this->setMetaKeywords($this->parse($this->_category->getMetaKeywords()));
            }
            if ($this->_category->getMetaDescription()) {
                $this->setMetaDescription($this->parse($this->_category->getMetaDescription()));
            }
        }
        //not necessary for most of customers
        //~ if ($this->_category->getDescription()) {
            //~ $this->setDescription($this->parse($this->_category->getDescription()));
        //~ }

        if ($this->_category->getProductTitleTpl()) {
            $this->setProductTitle($this->parse($this->_category->getProductTitleTpl()));
        }
        if ($this->_category->getProductDescriptionTpl()) {
            $this->setProductDescription($this->parse($this->_category->getProductDescriptionTpl()));
        }
        if ($this->_category->getProductMetaTitleTpl()) {
            $this->setProductMetaTitle($this->parse($this->_category->getProductMetaTitleTpl()));
        }
        if ($this->_category->getProductMetaKeywordsTpl()) {
            $this->setProductMetaKeywords($this->parse($this->_category->getProductMetaKeywordsTpl()));
        }
        if ($this->_category->getProductShortDescriptionTpl()) {
            $this->setProductShortDescription($this->parse($this->_category->getProductShortDescriptionTpl()));
        }
        if ($this->_category->getProductFullDescriptionTpl()) {
            $this->setProductFullDescription($this->parse($this->_category->getProductFullDescriptionTpl()));
        }
        if ($this->_category->getProductMetaDescriptionTpl()) {
            $this->setProductMetaDescription($this->parse($this->_category->getProductMetaDescriptionTpl()));
        }

        Mage::helper('mstcore/debug')->end($uid, array(
            'category_data' => $this->_category->getData(),
            'this'          => $this->getData(),
        ));
    }

    protected function process($category)
    {
        $uid = Mage::helper('mstcore/debug')->start();

        if ($category->getCategoryMetaTitleTpl()) {
            $this->setMetaTitle($this->parse($category->getCategoryMetaTitleTpl()));
        }
        if ($category->getCategoryMetaKeywordsTpl()) {
            $this->setMetaKeywords($this->parse($category->getCategoryMetaKeywordsTpl()));
        }
        if ($category->getCategoryMetaDescriptionTpl()) {
            $this->setMetaDescription($this->parse($category->getCategoryMetaDescriptionTpl()));
        }
            if ($category->getCategoryTitleTpl()) {
            $this->setTitle($this->parse($category->getCategoryTitleTpl()));
        }

        if ($category->getCategoryDescriptionTpl()) {
            $this->setDescription($this->parse($category->getCategoryDescriptionTpl()));
        }

        if ($category->getProductTitleTpl()) {
            $this->setProductTitle($this->parse($category->getProductTitleTpl()));
        }
        if ($category->getProductDescriptionTpl()) {
            $this->setProductDescription($this->parse($category->getProductDescriptionTpl()));
        }
        if ($category->getProductMetaTitleTpl()) {
            $this->setProductMetaTitle($this->parse($category->getProductMetaTitleTpl()));
        }
        if ($category->getProductMetaKeywordsTpl()) {
            $this->setProductMetaKeywords($this->parse($category->getProductMetaKeywordsTpl()));
        }
        if ($category->getProductShortDescriptionTpl()) {
            $this->setProductShortDescription($this->parse($category->getProductShortDescriptionTpl()));
        }
        if ($category->getProductFullDescriptionTpl()) {
            $this->setProductFullDescription($this->parse($category->getProductFullDescriptionTpl()));
        }
        if ($category->getProductMetaDescriptionTpl()) {
            $this->setProductMetaDescription($this->parse($category->getProductMetaDescriptionTpl()));
        }

        Mage::helper('mstcore/debug')->end($uid, array(
            'category_data' => $category->getData(),
            'this'          => $this->getData(),
        ));
    }
}