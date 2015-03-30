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




class Mirasvit_SeoSitemap_Model_Resource_Catalog_Product extends Mage_Sitemap_Model_Resource_Catalog_Product
{
    /**
     * Prepare product
     *
     * @param array $productRow
     * @return Varien_Object
     */
    protected function _prepareProduct(array $productRow)
    {
        $product = parent::_prepareProduct($productRow);
        $attribute = Mage::getSingleton('catalog/product')->getResource()->getAttribute('media_gallery');
        $media = Mage::getResourceSingleton('catalog/product_attribute_backend_media');
        $gallery = $media->loadGallery($product, new Varien_Object(array('attribute' => $attribute)));
        $product->setGallery($gallery);
        return $product;
    }
}