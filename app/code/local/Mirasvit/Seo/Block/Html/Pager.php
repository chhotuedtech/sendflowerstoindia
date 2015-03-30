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


class Mirasvit_Seo_Block_Html_Pager extends Mage_Page_Block_Html_Pager
{
    public function getPageUrl($page)
    {
        if (Mage::helper('mstcore')->isModuleInstalled('Mirasvit_SeoFilter') && Mage::registry('current_category')) {
            if (Mage::getModel('seofilter/config')->isEnabled()) {
                if ($page == 1) {
                    $url = Mage::getModel('seofilter/catalog_layer_filter_item')->getSpeakingFilterUrl(FALSE, TRUE, array());

                    return $url;
                } else {
                    $url = Mage::getModel('seofilter/catalog_layer_filter_item')->getSpeakingFilterUrl(FALSE, TRUE, array($this->getPageVarName()=>$page));

                    return $url;
                }
            }
        }

        if ($page == 1) {
            $params = Mage::app()->getFrontController()->getRequest()->getQuery();
            unset($params['p']);

            $urlParams['_use_rewrite'] = true;
            $urlParams['_escape']      = true;
            $urlParams['_query']       = $params;

            return $this->getUrl('*/*/*', $urlParams);
        } else {
            return $this->getPagerUrl(array($this->getPageVarName()=>$page));
        }
    }
}
