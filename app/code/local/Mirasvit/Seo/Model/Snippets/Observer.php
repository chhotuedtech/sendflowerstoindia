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


class Mirasvit_Seo_Model_Snippets_Observer extends Varien_Object {
    var $appliedSnippets         = false;
    var $isProductPage           = false;
    var $isCategoryPage          = false;
    var $appliedCategorySnippets = false;

    function __construct() {
        if(Mage::app()->getFrontController()->getRequest()->getControllerName() === "product") {
            $this->isProductPage = true;
        }
        if(Mage::app()->getFrontController()->getRequest()->getControllerName() === "category"
           || Mage::app()->getFrontController()->getRequest()->getModuleName() === "amlanding") {
            $this->isCategoryPage = true;
        }
    }

    public function getConfig()
    {
        return Mage::getSingleton('seo/config');
    }

    public function addProductSnippets($e) {
        if($this->isProductPage
            && !$this->appliedSnippets
            && $e->getData('block')->getNameInLayout() == "product.info"
            && $this->getConfig()->isRichSnippetsEnabled()) {

            $html = $e->getData('transport')->getHtml();
            $product = $e->getData('block')->getProduct();

            if ($this->offerFilter($html,$product)) {
                $html = $this->offerFilter($html,$product);
            }

            if ($this->productFilter($html,$product)) {
                $html = $this->productFilter($html,$product);
            }

            if ($this->aggregateRatingFilter($html,$product)) {
                $html = $this->aggregateRatingFilter($html,$product);
            }

            $e->getData('transport')->setHtml(
                $html
            );

            $this->appliedSnippets = true;
        } elseif ($this->isCategoryPage
                && !$this->appliedCategorySnippets
                && $e->getData('block')->getNameInLayout() == "product_list"
                && $this->getConfig()->isCategoryRichSnippetsEnabled()) {
                    $productCollection = $e->getData('block')->getLoadedProductCollection();
                    Mage::register('category_product_for_snippets', $productCollection);
                    $this->appliedCategorySnippets = true;
        }
        // if ($e->getData('block')->getNameInLayout() == "breadcrumbs") {
        //         $html = $e->getData('transport')->getHtml();
        //         if ($crumbs = $this->breadcrumbsFilter($html)) {
        //             $html = $crumbs;
        //         }
        //         $e->getData('transport')->setHtml(
        //             $html
        //         );
        // }
    }

    public function productFilter($html, $product) {
        $html = preg_replace('/\\"product\\-name\\"/i','"product-name" itemprop="name"',$html,1);
        $html = preg_replace('/(\\"product\\-img\\-box.*?\\<img)/ims','$1 itemprop="image" ',$html,1);
        $html = preg_replace('/\\"short\\-description\\"/i','"short-description" itemprop="description"',$html,1);

        $html = '<div itemscope itemtype="http://schema.org/Product">'.$html.'</div>';
        return $html;
    }

    public function offerFilter($html, $product)
    {
        $availability = "";
            if(method_exists ($product , "isAvailable" )) {
                $check = $product->isAvailable();
            } else {
                $check = $product->isInStock();
            }
            if ($check) {
                $availability .= '<link itemprop="availability" href="http://schema.org/InStock" />';
            } else {
                $availability .= '<link itemprop="availability" href="http://schema.org/OutOfStock" />';
            }

        $price = "";
            $price .= '<meta itemprop="priceCurrency" content="'.Mage::app()->getStore()->getCurrentCurrencyCode().'" />';
            $price .= '<span class="price" $1>$2<span itemprop="price">$3</span></span>';

        // if(preg_match('/product-view.*?(special\\-price\\".*?)\\<span class\\=\\"price\\"(.*?)>(.*?)\\<\\/span\\>.*?action-box/ims',$html)) {
        if(preg_match('/(special\\-price\\".*?)\\<span class\\=\\"price\\"(.*?)>(.*?)\\<\\/span\\>/ims',$html)) {
            $currencyCode           = Mage::app()->getStore()->getCurrentCurrencyCode();
            $priceSymbol            = Mage::app()->getLocale()->currency($currencyCode)->getSymbol();
            $productFinalPrice = false;
            $priceModel  = $product->getPriceModel();
            if ($product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_BUNDLE) {
                list($minimalPriceInclTax, $maximalPriceInclTax) = $priceModel->getPrices($product, null, true, false);
                if (($minimalPriceInclTax = $this->formatPrice($minimalPriceInclTax)) && $currencyCode) {
                    $productFinalPrice = $minimalPriceInclTax;
                }
            } elseif ($product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_GROUPED) {
                if (($minimalPriceValue = $this->formatPrice($this->getGroupedMinimalPrice($product))) && $currencyCode) {
                    $productFinalPrice = $minimalPriceValue;
                }
            } else {
                $finalPriceInclTax = Mage::helper('tax')->getPrice($product, $product->getFinalPrice(), true);
                if (($finalPriceInclTax = $this->formatPrice($finalPriceInclTax)) && $currencyCode) {
                    $productFinalPrice = $finalPriceInclTax;
                }
            }

            if ($productFinalPrice) {
                $price = '<meta itemprop="priceCurrency" content="'.$currencyCode.'" />'.
                         '<meta itemprop="price" content="'.$productFinalPrice.'" />'.
                         '<span class="price" $2>$3</span>'
                        ;
                $replacement = '$1<span itemprop="offers" itemscope itemtype="http://schema.org/Offer">'.$availability.$price.'</span>';
                $html = preg_replace('/(special\\-price\\".*?)\\<span class\\=\\"price\\"(.*?)\\>(.*?)\\<\\/span\\>/ims', $replacement, $html, 1);
            }
        } else {
            $replacement = '<span itemprop="offers" itemscope itemtype="http://schema.org/Offer">'.$availability.$price.'</span>';
            $html = preg_replace('/\\<span class\\=\\"price\\"(.*?)\\>(.*?)([0-9\.,]+)\\<\\/span\\>/ims',$replacement,$html,1);
        }

        return $html;
    }

    protected function formatPrice($price)
    {
        if (intval($price)) {
            $price = Mage::getModel('directory/currency')->format(
                $price,
                array('display'=>Zend_Currency::NO_SYMBOL),
                false
            );

            return $price;
        }

        return false;
    }

    public function aggregateRatingFilter($html, $product) {
            if (!is_object($product->getRatingSummary())) {
                return false;
            }
            if ($product->getRatingSummary()->getRatingSummary()) {
                $pattern = '/<p class="rating-links">(.*?)([0-9]+\s)/ims';
                $ratingValue = number_format($product->getRatingSummary()->getRatingSummary()/100*5, 1);
                $rating = '<p class="rating-links">$1<span itemprop="ratingValue">'.$ratingValue.'</span>';
                $html = preg_replace($pattern,$rating." "."/"." <span itemprop=\"reviewCount\">$2</span>",$html,1);
                $html = preg_replace('/\\<div class\\=\\"ratings\\"(.*?)\\>/ims','<div class="ratings" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">',$html,1);
                return $html;
            } else {
                return false;
            }
    }

    public function breadcrumbsFilter($html) {
        if($this->getBreadcrumbsSeparator()) {
            if (strpos($html, 'class="breadcrumbs"') !== false) {
                $html = preg_replace('/\\<li/','<li typeof="v:Breadcrumb"',$html);
                $html = preg_replace('/\\<a/','<a rel="v:url" property="v:title"',$html);
                return $html;
            }
            return false;
        }
    }

    public function getBreadcrumbsSeparator()
    {
        return $this->getConfig()->getBreadcrumbsSeparator(Mage::app()->getStore()->getId());
    }

}