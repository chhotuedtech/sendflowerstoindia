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


class Mirasvit_Seo_Model_Config
{
    const NO_TRAILING_SLASH = 1;
    const TRAILING_SLASH = 2;

    const URL_FORMAT_SHORT = 1;
    const URL_FORMAT_LONG = 2;

    const NOINDEX_NOFOLLOW = 1;
    const NOINDEX_FOLLOW = 2;
    const INDEX_NOFOLLOW = 3;

	public function isAddCanonicalUrl()
	{
		return Mage::getStoreConfig('seo/general/is_add_canonical_url');
	}

	public function getCrossDomainStore()
	{
		return Mage::getStoreConfig('seo/general/crossdomain');
	}

	public function getCanonicalUrlIgnorePages()
	{
	    $pages = Mage::getStoreConfig('seo/general/canonical_url_ignore_pages');
	    $pages = explode("\n", trim($pages));
	    $pages = array_map('trim',$pages);

	    return $pages;
	}

	public function getNoindexPages()
	{
	    $pages = Mage::getStoreConfig('seo/general/noindex_pages2');
	    $pages = unserialize($pages);
	    $result = array();
	    if (is_array($pages)) {
		    foreach ($pages as $value) {
		    	$result[] = new Varien_Object($value);
		    }
		}
	    return $result;
	}

	public function isPagingPrevNextEnabled()
	{
		return Mage::getStoreConfig('seo/general/is_paging_prevnext');
	}

	public function isOpenGraphEnabled()
	{
	    return Mage::getStoreConfig('seo/general/is_opengraph');
	}

	public function isRichSnippetsEnabled()
	{
	    return Mage::getStoreConfig('seo/general/is_rich_snippets');
	}

	public function isCategoryRichSnippetsEnabled()
	{
	    return Mage::getStoreConfig('seo/general/is_category_rich_snippets');
	}

	public function isCategoryMetaTagsUsed()
	{
	    return Mage::getStoreConfig('seo/general/is_category_meta_tags_used');
	}

	public function isProductMetaTagsUsed()
	{
	    return Mage::getStoreConfig('seo/general/is_product_meta_tags_used');
	}

	public function isSeoOfNestedCategoriesForCurrentCategoryUsed()
	{
	    return Mage::getStoreConfig('seo/general/is_seo_of_nested_categories_for_current_category_used');
	}

///////////// SEO URL
	public function isEnabledSeoUrls()
	{
		return Mage::getStoreConfig('seo/url/layered_navigation_friendly_urls');
	}

	public function getTrailingSlash()
	{
        return Mage::getStoreConfig('seo/url/trailing_slash');
	}

	public function getProductUrlFormat()
	{
       return Mage::getStoreConfig('seo/url/product_url_format');
	}

	public function getProductUrlKey($store)
	{
       return Mage::getStoreConfig('seo/url/product_url_key', $store);
	}

	public function isEnabledTagSeoUrls()
	{
		return Mage::getStoreConfig('seo/url/tag_friendly_urls');
	}

	public function isEnabledReviewSeoUrls()
	{
		return Mage::getStoreConfig('seo/url/review_friendly_urls');
	}

///////////// IMAGE
	public function getIsEnableImageFriendlyUrls()
	{
	    return Mage::getStoreConfig('seo/image/is_enable_image_friendly_urls');
	}

	public function getImageUrlTemplate()
	{
	    return Mage::getStoreConfig('seo/image/image_url_template');
	}
	public function getIsEnableImageAlt()
	{
	    return Mage::getStoreConfig('seo/image/is_enable_image_alt');
	}

	public function getImageAltTemplate()
	{
	    return Mage::getStoreConfig('seo/image/image_alt_template');
	}

	public function getBreadcrumbsSeparator($store)
	{
		$separator = trim(Mage::getStoreConfig('seo/general/breadcrumbs_separator', $store));
		if(empty($separator)) {
			return false;
		}
	    return $separator;
	}
}
