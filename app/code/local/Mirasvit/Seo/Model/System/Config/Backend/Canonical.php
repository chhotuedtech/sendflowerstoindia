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


class Mirasvit_Seo_Model_System_Config_Backend_Canonical extends Mage_Core_Model_Config_Data
{
    protected function _afterSave()
    {
        if($this->getValue()) {
            $config = Mage::getSingleton('core/config');
            $config->saveConfig( Mage_Catalog_Helper_Category::XML_PATH_USE_CATEGORY_CANONICAL_TAG, 0, $this->getScope() , $this->getScopeId());
            $config->saveConfig( Mage_Catalog_Helper_Product::XML_PATH_USE_PRODUCT_CANONICAL_TAG, 0, $this->getScope() , $this->getScopeId());
        }
    }
}
