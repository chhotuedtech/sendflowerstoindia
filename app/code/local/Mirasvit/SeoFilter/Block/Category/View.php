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



class Mirasvit_SeoFilter_Block_Category_View extends Mage_Catalog_Block_Category_View
{
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $this->getLayout()->createBlock('catalog/breadcrumbs');

        if ($headBlock = $this->getLayout()->getBlock('head')) {
            $category = $this->getCurrentCategory();
            if ($title = $category->getMetaTitle()) {
                $headBlock->setTitle($title);
            }
            if ($description = $category->getMetaDescription()) {
                $headBlock->setDescription($description);
            }
            if ($keywords = $category->getMetaKeywords()) {
                $headBlock->setKeywords($keywords);
            }
            if ($this->helper('catalog/category')->canUseCanonicalTag()) {
                $rewrite = Mage::getStoreConfig('web/seo/use_rewrites',Mage::app()->getStore()->getId());
                // Rewrite
                if($rewrite == 1) {
                    $headBlock->removeItem('link_rel',$category->getUrl());
                    $headBlock->addLinkRel('canonical', Mage::getModel('seofilter/catalog_layer_filter_item')->getSpeakingFilterUrl(FALSE, TRUE));
                }
                else{
                    $headBlock->addLinkRel('canonical', $category->getUrl());

                }
            }
            /*
            want to show rss feed in the url
            */
            if ($this->IsRssCatalogEnable() && $this->IsTopCategory()) {
                $title = $this->helper('rss')->__('%s RSS Feed',$this->getCurrentCategory()->getName());
                $headBlock->addItem('rss', $this->getRssLink(), 'title="'.$title.'"');
            }
        }
        return $this;
    }

}