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


class Mirasvit_Seo_Model_System_Config_Source_Trailingslash
{
    public function toOptionArray()
    {
        return array(
            array('value' => 0, 'label'=>Mage::helper('seo')->__('Disabled')),
            array('value' => Mirasvit_Seo_Model_Config::TRAILING_SLASH, 'label'=>Mage::helper('seo')->__('Redirect to same page with Trailing Slash')),
            array('value' => Mirasvit_Seo_Model_Config::NO_TRAILING_SLASH, 'label'=>Mage::helper('seo')->__('Redirect to same page without Trailing Slash')),
        );
    }
}
