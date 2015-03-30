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



class Mirasvit_Seo_Model_Rewrite_Product_Attribute_Backend_Urlkey extends Mage_Catalog_Model_Product_Attribute_Backend_Urlkey
{
    public function beforeSave($object)
    {
        $urlKey = $object->getData($this->getAttribute()->getName());    

        if ($urlKey == '' && $product = Mage::registry('current_product')){
            $config = Mage::getSingleton('seo/config');
            $store = $product->getStore();
            $urlKeyTemplate = $config->getProductUrlKey($store);
            $templ = Mage::getModel('seo/object_producturl')
                        ->setProduct($product)
                        ->setStore($store);
            $urlKey = $templ->parse($urlKeyTemplate);
            if ($urlKey == '') {
                $urlKey = $object->getName();
            }                
            $object->setData($this->getAttribute()->getName(), $object->formatUrlKey($urlKey));
        } else {
            parent::beforeSave($object);
        }
        return $this;
    }
}
