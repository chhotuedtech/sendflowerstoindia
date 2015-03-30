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


class Mirasvit_SeoAutolink_Model_Config_Source_Target
{
    const CMS_PAGE = 1;
    const CATEGORY_DESCRIPTION = 2;
    const PRODUCT_SHORT_DESCRIPTION = 3;
    const PRODUCT_FULL_DESCRIPTION = 4;
    const CMS_BLOCK = 5;
    const SEO_DESCRIPTION = 6;

    public function toOptionArray()
    {
        $optionArray = array(
            array('value'=>self::CMS_PAGE, 'label'=>Mage::helper('seoautolink')->__('CMS Page')),
            array('value'=>self::CMS_BLOCK, 'label'=>Mage::helper('seoautolink')->__('CMS Block')),
            array('value'=>self::CATEGORY_DESCRIPTION, 'label'=>Mage::helper('seoautolink')->__('Category Description')),
            array('value'=>self::PRODUCT_SHORT_DESCRIPTION, 'label'=>Mage::helper('seoautolink')->__('Product Short Description')),
            array('value'=>self::PRODUCT_FULL_DESCRIPTION, 'label'=>Mage::helper('seoautolink')->__('Product Full Description')),
        );

        if (Mage::helper('mstcore')->isModuleInstalled('Mirasvit_Seo')) {
            array_push($optionArray, array('value'=>self::SEO_DESCRIPTION, 'label'=>Mage::helper('seoautolink')->__('Seo Description')));
        }

        return $optionArray;
    }
}