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


class Mirasvit_Seo_Model_Rewrite_Tag extends Mage_Tag_Model_Tag
{
    public function getConfig() {
    	return Mage::getSingleton('seo/config');
    }

    public function getTaggedProductsUrl()
    {
        if ($this->getConfig()->isEnabledTagSeoUrls()) {
            $uri = Mage::getModel('catalog/product_url')->formatUrlKey($this->getName());
            $options = array();
            if ($this->getStoreId()) {
                $options['_store'] = $this->getStoreId();
            }
            return Mage::getUrl('tag/'.$uri.'-'.$this->getId(), $options);
        } else {
            return parent::getTaggedProductsUrl();
        }
    }
}