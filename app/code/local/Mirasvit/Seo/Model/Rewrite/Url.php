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


if (Mage::helper('mstcore')->isModuleInstalled('Dnd_Patchindexurl') && class_exists('Dnd_Patchindexurl_Model_Url')) {
   abstract class Mirasvit_Seo_Model_Rewrite_Url_Abstract extends Dnd_Patchindexurl_Model_Url {

   }
} else {
    abstract class Mirasvit_Seo_Model_Rewrite_Url_Abstract extends Mage_Catalog_Model_Url {

    }
}

class Mirasvit_Seo_Model_Rewrite_Url extends Mirasvit_Seo_Model_Rewrite_Url_Abstract
{
	protected $newProducts = array();

    protected function _refreshProductRewrite(Varien_Object $product, Varien_Object $category)
    {
        if ($category->getId() == $category->getPath()) {
            return $this;
        }
        if ($product->getUrlKey() == '' || in_array($product->getId(), $this->newProducts)) {
            $config = Mage::getSingleton('seo/config');
            $storeId = $product->getStoreId();
			$store = Mage::getModel('core/store')->load($storeId);

            $productFull = Mage::getModel('catalog/product')->load($product->getId());
            $urlKeyTemplate = $config->getProductUrlKey($store);
            $templ = Mage::getModel('seo/object_producturl')
                        ->setProduct($productFull)
                        ->setStore($store);
            $urlKey = $templ->parse($urlKeyTemplate);
            if ($urlKey == '') {
                $urlKey = $product->getName();
            }
            $urlKey = $this->getProductModel()->formatUrlKey($urlKey);
            $this->newProducts[] = $product->getId();
// echo "URL KEY: $urlKey <br>";
// echo "STORE ID: $storeId <br>";
// echo "Template: $urlKeyTemplate <br>";
// die;
        }
        else {
            $urlKey = $this->getProductModel()->formatUrlKey($product->getUrlKey());
        }

        $idPath      = $this->generatePath('id', $product, $category);
        $targetPath  = $this->generatePath('target', $product, $category);
        $requestPath = $this->getProductRequestPath($product, $category);

        $categoryId = null;
        $updateKeys = true;
        if ($category->getLevel() > 1) {
            $categoryId = $category->getId();
            $updateKeys = false;
        }

        $rewriteData = array(
            'store_id'      => $category->getStoreId(),
            'category_id'   => $categoryId,
            'product_id'    => $product->getId(),
            'id_path'       => $idPath,
            'request_path'  => $requestPath,
            'target_path'   => $targetPath,
            'is_system'     => 1
        );

        $this->getResource()->saveRewrite($rewriteData, $this->_rewrite);

        if (Mage::getVersion() >= '1.4.1.1') {
            if ($this->getShouldSaveRewritesHistory($category->getStoreId())) {
                $this->_saveRewriteHistory($rewriteData, $this->_rewrite);
            }
        }
        if ($updateKeys && $product->getUrlKey() != $urlKey) {
            $product->setUrlKey($urlKey);
            $this->getResource()->saveProductAttribute($product, 'url_key');
        }

        if ($updateKeys && $product->getUrlPath() != $requestPath) {
            $product->setUrlPath($requestPath);
            $this->getResource()->saveProductAttribute($product, 'url_path');
        }

        return $this;
    }
}