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


$installer = $this;
$installer->startSetup();

$helper = Mage::helper('mstcore');
$helper->copyConfigData('sitemap/extended/is_add_product_tags', 'seositemap/google/is_add_product_tags');
$helper->copyConfigData('sitemap/extended/product_tags_changefreq', 'seositemap/google/product_tags_changefreq');
$helper->copyConfigData('sitemap/extended/product_tags_priority', 'seositemap/google/product_tags_priority');
$helper->copyConfigData('sitemap/extended/link_changefreq', 'seositemap/google/link_changefreq');
$helper->copyConfigData('sitemap/extended/link_priority', 'seositemap/google/link_priority');
$helper->copyConfigData('sitemap/extended/split_size', 'seositemap/google/split_size');
$helper->copyConfigData('sitemap/extended/max_links', 'seositemap/google/max_links');

$installer->endSetup();

Mage::getSingleton('core/config')->cleanCache();
