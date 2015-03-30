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


class Mirasvit_Seo_Helper_Breadcrumbs extends Mage_Catalog_Helper_Data
{
    public function getCategory()
    {
    	if ($product = Mage::registry('current_product')) {
    		return Mage::helper('seo')->getProductSeoCategory($product);
    	}
        return Mage::registry('current_category');
    }
}