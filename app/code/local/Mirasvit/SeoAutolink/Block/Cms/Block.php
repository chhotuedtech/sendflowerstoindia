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



class  Mirasvit_SeoAutolink_Block_Cms_Block extends Mage_Cms_Block_Block
{
    public function getConfig() {
        return Mage::getSingleton('seoautolink/config');
    }

    /**
     * Prepare Content HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (!in_array(Mirasvit_SeoAutolink_Model_Config_Source_Target::CMS_BLOCK, $this->getConfig()->getTarget())) {
            return parent::_toHtml();
        }
        $html = parent::_toHtml();
        $html = Mage::helper('seoautolink')->addLinks($html);
        return $html;
    }
}