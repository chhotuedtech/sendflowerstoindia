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


/*******************************************
Mirasvit
This source file is subject to the Mirasvit Software License, which is available at http://mirasvit.com/license/.
Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
If you wish to customize this module for your needs
Please refer to http://www.magentocommerce.com for more information.
@category Mirasvit
@copyright Copyright (C) 2012 Mirasvit (http://mirasvit.com.ua), Vladimir Drok <dva@mirasvit.com.ua>, Alexander Drok<alexander@mirasvit.com.ua>
*******************************************/

class Mirasvit_Seo_Model_System_Template_Worker extends Varien_Object
{
    protected $maxPerStep = 1000;
    protected $totalNumber;
   	public function run() {
   	    $this->totalNumber = Mage::getModel('catalog/product')->getCollection()->count();
   	    if (($this->getStep()-1)*$this->maxPerStep >= $this->totalNumber) {
            Mage::getModel('catalog/url')->refreshRewrites();
            return false;
        }
        $this->process(); 
        return true;
	}

    public function formatUrlKey($str)
    {
        $urlKey = preg_replace('#[^0-9a-z]+#i', '-', Mage::helper('catalog/product_url')->format($str));
        $urlKey = strtolower($urlKey);
        $urlKey = trim($urlKey, '-');
        return $urlKey;
    }

	public function getMaxPerStep() {
	    return $this->maxPerStep;
	}

	public function getCurrentNumber() {
	    $c = $this->getStep() * $this->getMaxPerStep();
	    if ($c > $this->totalNumber) {
	        return $this->totalNumber;
	    } else {
	        return $c;
	    }
	}

	public function getTotalNumber() {
	    return $this->totalNumber;
	}

	public function process() {
	    $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
	    $tablePrefix = (string)Mage::getConfig()->getTablePrefix();
        $select = $connection->select()->from($tablePrefix.'eav_entity_type')->where("entity_type_code = 'catalog_product'");
        $productTypeId = $connection->fetchOne($select);
        $select = $connection->select()->from($tablePrefix.'eav_attribute')->where("entity_type_id = $productTypeId AND (attribute_code = 'url_path')");
        $urlPathId = $connection->fetchOne($select);
        $select = $connection->select()->from($tablePrefix.'eav_attribute')->where("entity_type_id = $productTypeId AND (attribute_code = 'url_key')");
        $urlKeyId = $connection->fetchOne($select);

   	    $config = Mage::getSingleton('seo/config');

        foreach (Mage::app()->getStores() as $store) {
            $products = Mage::getModel('catalog/product')->getCollection()
                        ->addAttributeToSelect('*')
                        ->setCurPage($this->getStep())
                        ->setPageSize($this->maxPerStep)
                        ->setStore($store);
            foreach ($products as $product) {                  
                $urlKeyTemplate = $config->getProductUrlKey($store);
                $storeId = $store->getId();
                $templ = Mage::getModel('seo/object_producturl')
                            ->setProduct($product)
                            ->setStore($store);
                $urlKey = $templ->parse($urlKeyTemplate);
                $urlKey = $this->formatUrlKey($urlKey);
                if ($product->getUrlKey() == $urlKey) {
                    continue;
                }

                $urlSuffix = Mage::getStoreConfig('catalog/seo/product_url_suffix', $store);
                $select = $connection->select()->from($tablePrefix.'catalog_product_entity_varchar')->
                        where("entity_type_id = $productTypeId AND attribute_id = $urlKeyId AND entity_id = {$product->getId()} AND store_id = {$storeId}");
                $row = $connection->fetchRow($select);//echo $select;die;
                if ($row) {
                    $connection->update($tablePrefix.'catalog_product_entity_varchar', array('value' => $urlKey), "entity_type_id = $productTypeId AND attribute_id = $urlKeyId AND entity_id = {$product->getId()} AND store_id = {$storeId}");
                } else {
                    $data = array(
                        'entity_type_id' => $productTypeId,
                        'attribute_id' => $urlKeyId,
                        'entity_id' => $product->getId(),
                        'store_id' => $storeId,
                        'value' => $urlKey
                    );
                    $connection->insert($tablePrefix.'catalog_product_entity_varchar', $data);
                }
                $select = $connection->select()->from($tablePrefix.'catalog_product_entity_varchar')->
                        where("entity_type_id = $productTypeId AND attribute_id = $urlPathId AND entity_id = {$product->getId()} AND store_id = {$storeId}");
                $row = $connection->fetchRow($select);
                if ($row) {
                    $connection->update($tablePrefix.'catalog_product_entity_varchar', array('value' => $urlKey . $urlSuffix), "entity_type_id = $productTypeId AND attribute_id = $urlPathId AND entity_id = {$product->getId()} AND store_id = {$storeId}");
                } else {
                    $data = array(
                        'entity_type_id' => $productTypeId,
                        'attribute_id' => $urlPathId,
                        'entity_id' => $product->getId(),
                        'store_id' => $storeId,
                        'value' => $urlKey.$urlSuffix
                    );
                    $connection->insert($tablePrefix.'catalog_product_entity_varchar', $data);
                }
            }
        }       
	}
}
