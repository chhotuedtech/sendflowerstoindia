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


class Mirasvit_SeoSitemap_Model_Config
{

    public function getFrontendSitemapBaseUrl($store = null)
    {
        return Mage::getStoreConfig('seositemap/frontend/sitemap_base_url', $store);
    }

    public function getFrontendSitemapMetaTitle($store = null)
    {
        return Mage::getStoreConfig('seositemap/frontend/sitemap_meta_title', $store);
    }

    public function getFrontendSitemapMetaKeywords($store = null)
    {
        return Mage::getStoreConfig('seositemap/frontend/sitemap_meta_keywords', $store);
    }

    public function getFrontendSitemapMetaDescription($store = null)
    {
        return Mage::getStoreConfig('seositemap/frontend/sitemap_meta_description', $store);
    }

    public function getFrontendSitemapH1($store = null)
    {
        return Mage::getStoreConfig('seositemap/frontend/sitemap_h1', $store);
    }

    public function getIsShowProducts($store = null)
    {
        return Mage::getStoreConfig('seositemap/frontend/is_show_products', $store);
    }

    public function getIsShowCmsPages($store = null)
    {
        return Mage::getStoreConfig('seositemap/frontend/is_show_cms_pages', $store);
    }

    public function getIgnoreCmsPages($store = null)
    {
        $value = Mage::getStoreConfig('seositemap/frontend/ignore_cms_pages', $store);
        return explode(',', $value);
    }

    public function getIsShowStores($store = null)
    {
        return Mage::getStoreConfig('seositemap/frontend/is_show_stores', $store);
    }

    public function getAdditionalLinks($store = null)
    {
        $conf = Mage::getStoreConfig('seositemap/frontend/additional_links', $store);
        $links = array();
        $ar = explode("\n", $conf);
        foreach ($ar as $v) {
            $p = explode(',', $v);
            if (isset($p[0]) && isset($p[1])) {
                $links[] = new Varien_Object(array(
                    'url' => trim($p[0]),
                    'title' => trim($p[1])
                ));
            }
        }
        return $links;
    }

    public function getExcludeLinks($store = null)
    {
        $conf =  Mage::getStoreConfig('seositemap/frontend/exclude_links', $store);

        $links = explode("\n", trim($conf));
        $links = array_map('trim',$links);

        $links = array_diff($links, array(0, null));
        return $links;
    }

    public function getFrontendLinksLimit($store = null)
    {
        return Mage::getStoreConfig('seositemap/frontend/links_limit', $store);
    }

    public function getIsAddProductImages($store = null)
    {
        return Mage::getStoreConfig('seositemap/google/is_add_product_images', $store);
    }

    public function getIsEnableImageFriendlyUrls($store = null)
    {
        return Mage::getStoreConfig('seositemap/google/is_enable_image_friendly_urls', $store);
    }

    public function getImageUrlTemplate($store = null)
    {
        return Mage::getStoreConfig('seositemap/google/image_url_template', $store);
    }

    public function getImageSizeWidth($store = null)
    {
        return Mage::getStoreConfig('seositemap/google/image_size_width', $store);
    }

    public function getImageSizeHeight($store = null)
    {
        return Mage::getStoreConfig('seositemap/google/image_size_height', $store);
    }

    public function getIsAddProductTags($store = null)
    {
        return Mage::getStoreConfig('seositemap/google/is_add_product_tags', $store);
    }

    public function getProductTagsChangefreq($store = null)
    {
        return Mage::getStoreConfig('seositemap/google/product_tags_changefreq', $store);
    }

    public function getProductTagsPriority($store = null)
    {
        return Mage::getStoreConfig('seositemap/google/product_tags_priority', $store);
    }

    public function getLinkChangefreq($store = null)
    {
        return Mage::getStoreConfig('seositemap/google/link_changefreq', $store);
    }

    public function getLinkPriority($store = null)
    {
        return Mage::getStoreConfig('seositemap/google/link_priority', $store);
    }

    public function getSplitSize($store = null)
    {
        return Mage::getStoreConfig('seositemap/google/split_size', $store);
    }

    public function getMaxLinks($store = null)
    {
        return Mage::getStoreConfig('seositemap/google/max_links', $store);
    }


    /************************/

}