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


class Mirasvit_SeoAutolink_Model_Config
{
    public function getTarget()
    {
        //old key 'seo/autolink/target';
        return explode(',',Mage::getStoreConfig('seoautolink/autolink/target'));
    }

    public function getExcludedTags($store = null)
    {
        $conf =  Mage::getStoreConfig('seoautolink/autolink/excluded_tags', $store);
        $tags = explode("\n", trim($conf));
        $tags = array_map('trim',$tags);
        $tags = array_diff($tags, array(0, null));

        return $tags;
    }

    public function getSkipLinks($store = null)
    {
        $conf =  Mage::getStoreConfig('seoautolink/autolink/skip_links_for_page', $store);
        $links = explode("\n", trim($conf));
        $links = array_map('trim',$links);
        $links = array_diff($links, array(0, null));

        return $links;
    }

    public function getIsEnableLinksForBlog($store = null)
    {
        return Mage::getStoreConfig('seoautolink/autolink/is_enable_links_for_blog', $store);
    }
}