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


class Mirasvit_Seo_Block_Richsnippet extends Mage_Core_Block_Template
{
    protected $isCategoryFilterChecked = false;
    protected $categorySnippetsRating;
    protected $categorySnippetsRatingCount;

    public function getConfig()
    {
    	return Mage::getSingleton('seo/config');
    }

    public function getProduct()
    {
        $product =  Mage::registry('current_product');
        if (!$product->getRatingSummary()) {
            Mage::getModel('review/review')
               ->getEntitySummary($product, Mage::app()->getStore()->getId());
        }

        return $product;
    }

    public function getReviews()
    {
        $reviews = Mage::getModel('review/review')->getResourceCollection();
        $reviews->addStoreFilter( Mage::app()->getStore()->getId() )
            ->addStatusFilter( Mage_Review_Model_Review::STATUS_APPROVED )
            ->addFieldToFilter('entity_id', Mage_Review_Model_Review::ENTITY_PRODUCT)
            ->addFieldToFilter('entity_pk_value', array('in' => $this->getProduct()->getId()))
            ->setDateOrder()
            ->addRateVotes();

        return $reviews;
    }

    protected function _toHtml()
    {
        if (!$this->getConfig()->isCategoryRichSnippetsEnabled()) {
            return;
        }

        return parent::_toHtml();
    }

    public function categorySnippetsFilter() {
        if (!Mage::registry('category_product_for_snippets')) {
            $this->isCategoryFilterChecked = true;
            return false;
        }

        $productCollection = Mage::registry('category_product_for_snippets');
        if($productCollection->count()) {
            $price = array();
            $rating = array();
            foreach ($productCollection as $product) {
                if (is_object($product->getRatingSummary())) {
                    if ($product->getRatingSummary()->getRatingSummary() > 0) {
                        $rating[] = $product->getRatingSummary()->getRatingSummary();
                    }
                }
                if ($product->getFinalPrice() > 0) {
                    $price [] = $product->getFinalPrice();
                } elseif ($product->getMinimalPrice() > 0) {
                    $price[] = $product->getMinimalPrice();
                }
            }
            if (count($price) > 0) {
                $this->categorySnippetsPrice = min($price);
            }

            if (array_sum($rating) > 0) {
                $this->categorySnippetsRatingCount = count($rating);
                $summaryRating = array_sum($rating);
                $this->categorySnippetsRating = $summaryRating/$this->categorySnippetsRatingCount;
            }
        }

        $this->isCategoryFilterChecked = true;
    }

    public function getCategorySnippetsPrice() {
        if (!$this->isCategoryFilterChecked) {
            $this->categorySnippetsFilter();
        }

        return $this->categorySnippetsPrice;
    }

    public function getCategorySnippetsRating() {
        if (!$this->isCategoryFilterChecked) {
            $this->categorySnippetsFilter();
        }

        return $this->categorySnippetsRating;
    }

    public function getCategorySnippetsRatingCount() {
        if (!$this->isCategoryFilterChecked) {
            $this->categorySnippetsFilter();
        }

        return $this->categorySnippetsRatingCount;
    }
}
