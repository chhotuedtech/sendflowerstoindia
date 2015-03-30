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


/**
* This file is part of the Mirasvit_SeoFilter project.
*
* Mirasvit_SeoFilter is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License version 3 as
* published by the Free Software Foundation.
*
* This script is distributed in the hope that it will be useful, but WITHOUT
* ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
* FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
*
* PHP version 5
*
* @category Mirasvit_SeoFilter
* @package Mirasvit_SeoFilter
* @author Michael TÃ¼rk <tuerk@flagbit.de>
* @copyright 2012 Flagbit GmbH & Co. KG (http://www.flagbit.de). All rights served.
* @license http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
* @version 0.1.0
* @since 0.1.0
*/
/**
 * Item model for link item of layered navigation.
 *
 * @category Mirasvit_SeoFilter
 * @package Mirasvit_SeoFilter
 * @author Michael TÃ¼rk <tuerk@flagbit.de>
 * @copyright 2012 Flagbit GmbH & Co. KG (http://www.flagbit.de). All rights served.
 * @license http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @version 0.1.0
 * @since 0.1.0
 */

if (Mage::helper('mstcore')->isModuleInstalled('GoMage_Navigation') && class_exists('GoMage_Navigation_Model_Layer_Filter_Item')) {
    abstract class Mirasvit_SeoFilter_Model_Catalog_Layer_Filter_Item_Abstract extends GoMage_Navigation_Model_Layer_Filter_Item {
    }
} elseif (Mage::helper('mstcore')->isModuleInstalled('Amasty_Shopby') && class_exists('Amasty_Shopby_Model_Catalog_Layer_Filter_Item')) {
    abstract class Mirasvit_SeoFilter_Model_Catalog_Layer_Filter_Item_Abstract extends Amasty_Shopby_Model_Catalog_Layer_Filter_Item {
    }
} elseif (Mage::helper('mstcore')->isModuleInstalled('Fishpig_AttributeSplash') && class_exists('Fishpig_AttributeSplash_Model_Layer_Filter_Item')) {
    abstract class Mirasvit_SeoFilter_Model_Catalog_Layer_Filter_Item_Abstract extends Fishpig_AttributeSplash_Model_Layer_Filter_Item {
    }
} elseif (Mage::helper('mstcore')->isModuleInstalled('EM_LayeredNavigation') && class_exists('EM_LayeredNavigation_Model_Catalog_Filter_Item')) {
    abstract class Mirasvit_SeoFilter_Model_Catalog_Layer_Filter_Item_Abstract extends EM_LayeredNavigation_Model_Catalog_Filter_Item {
    }
} else {
    abstract class Mirasvit_SeoFilter_Model_Catalog_Layer_Filter_Item_Abstract extends Mage_Catalog_Model_Layer_Filter_Item {
    }
}



class Mirasvit_SeoFilter_Model_Catalog_Layer_Filter_Item extends Mirasvit_SeoFilter_Model_Catalog_Layer_Filter_Item_Abstract {

    public function getConfig() {
        return Mage::getModel('seofilter/config');
    }

    /**
    * Get filter item url
    * Overwritten function from the original class to add rewrite to URL.
    *
    * @return string
    */
    public function getUrl()
    {
        $uid = Mage::helper('mstcore/debug')->start();
        //support of FISHPIG Attribute Splash Pages http://www.magentocommerce.com/magento-connect/fishpig-s-attribute-splash-pages.html
        if (Mage::registry('splash_page')) {
            return parent::getUrl();
        }

        $filter = $this->getFilter();
        $category = Mage::registry('current_category');
        $rewrite = Mage::getStoreConfig('web/seo/use_rewrites',Mage::app()->getStore()->getId());
        if(!$this->getConfig()->isEnabled() || $rewrite == 0) {
            return parent::getUrl();
        }

        if(!is_object($category)){
            return parent::getUrl();
        }

        $url = $this->getSpeakingFilterUrl(true);
        Mage::helper('mstcore/debug')->end($uid, array('$url' => $url));

        if($this->getFilter()->getRequestVar() == "cat") {
            $categoryUrl = Mage::getModel('catalog/category')->load($this->getValue())->getUrl();
            $url = $categoryUrl;
            $request = Mage::getUrl('*/*/*', array('_current'=>true, '_use_rewrite'=>true));
            if(strpos($request,'?') !== false ){
                $queryString = substr($request,strpos($request,'?'));
            }
            else{
                $queryString = '';
            }
            if(!empty($queryString)){
                $url .= $queryString;
            }
        }

        return $url;
    }

    /**
     * Get url for remove item from filter
     * Overwritten function from the original class to add rewrite to URL.
     *
     * @return string
     */
    public function getRemoveUrl()
    {
        $uid = Mage::helper('mstcore/debug')->start();
        //support of FISHPIG Attribute Splash Pages http://www.magentocommerce.com/magento-connect/fishpig-s-attribute-splash-pages.html
        if (Mage::registry('splash_page')) {
            return parent::getRemoveUrl();
        }

        $filter = $this->getFilter();
        $category = Mage::registry('current_category');

        $rewrite = Mage::getStoreConfig('web/seo/use_rewrites', Mage::app()->getStore()->getId());
        if (!$this->getConfig()->isEnabled() || $rewrite == 0) {
            return parent::getRemoveUrl();
        }

        if(!is_object($category)){
            return parent::getRemoveUrl();
        }

        $url = $this->getSpeakingFilterUrl(false);
        Mage::helper('mstcore/debug')->end($uid, array('$url' => $url));
        return $url;
    }

    /**
     * Main function for link generation. Implements the following process:
     * (1) get URL path from current category
     * (2) iterate over all state variables
     * (2a) attribute filter: add normalized lowercased option label for each state item ordered by attribute's position
     * (2b) category or price filter: add normal requestVar & value to query
     * (3) potentially add own value (depending on being a getUrl() or getRemoveUrl() call)
     * (4) add seo suffix
     * (5) generate direct link and return
     *
     * @param boolean $addOwnValue Signals whether or not to add the current item's value to the URL
     * @param boolean $withoutFilter To gain access to the link generation without actually having an attribute model, this switch can be set to TRUE.
     * @param array $additionalQueryParams To pass additional query parameters to the resulting link, this parameter can be used.
     */
    public function getSpeakingFilterUrl($addOwnValue, $withoutFilter = FALSE, $additionalQueryParams = array())
    {
        $uid = Mage::helper('mstcore/debug')->start();
        $category = Mage::registry('current_category');

        $filterUrlArray = $this->_getFilterUrlArrayForCurrentState($withoutFilter);
        $query = $filterUrlArray['query'];
        $query[Mage::getBlockSingleton('page/html_pager')->getPageVarName()]= null; // exclude current page from urls

        if ($addOwnValue) {
            if ($this->getFilter() instanceof Mage_Catalog_Model_Layer_Filter_Attribute) {
                $position = $this->getFilter()->getAttributeModel()->getId();
                // if(isset($filterUrlArray['filterUrl'][$position])){
                //     while(isset($filterUrlArray['filterUrl'][$position])){ // Search free position in array
                //         $position++;
                //     }
                // }

                $optionIds = explode(',', $this->getValue());
                foreach ($optionIds as $optionId) {
                    $filterUrlArray['filterUrl'][$position] = $this->_getRewriteForFilterOption($this->getFilter(), $optionId);
                }
                Mage::helper('mstcore/debug')->dump($uid, array('$thisData'=>$this->getData(), '$optionIds' => $optionIds, '$filterUrlArray' => $filterUrlArray));

            }
            else {
                $query[$this->getFilter()->getRequestVar()] = $this->getValue();
                Mage::helper('mstcore/debug')->dump($uid, $query);
            }
        }

        ksort($filterUrlArray['filterUrl']);
        $filterUrlString = implode('-', $filterUrlArray['filterUrl']);

        $baseurl = preg_replace('/\?.*/', '', Mage::getUrl());
        $url = str_replace($baseurl, '', $category->getUrl());
        $url = preg_replace('/\?.*/', '', $url);

        if (!empty($filterUrlString)) {
            $configUrlSuffix = Mage::getStoreConfig('catalog/seo/category_url_suffix');
            //user can enter .html or html or / suffix

            if ($configUrlSuffix != '' && $configUrlSuffix[0] != '.' && $configUrlSuffix != '/') {
                $configUrlSuffix = '.'.$configUrlSuffix;
            }

            if (substr($url, -strlen($configUrlSuffix)) == $configUrlSuffix) {
                $url = substr($url, 0, -strlen($configUrlSuffix));
            }

            $url .= '/' . $filterUrlString . $configUrlSuffix;
        }

        if (!empty($additionalQueryParams)) {
            $query = array_merge($query, $additionalQueryParams);
        }

        $params['_query'] = $query;
        $url = Mage::getModel('core/url')->getDirectUrl($url, array('_query' => $query));


        Mage::helper('mstcore/debug')->end($uid, array('$url' => $url, '_query' => $query));
        return $url;
    }

    public function getBaseUri() {
        $baseStoreUri = parse_url(Mage::getUrl(), PHP_URL_PATH);
        if ($baseStoreUri  == '/') {
            return $_SERVER['REQUEST_URI'];
        } else {
            return DS.str_replace($baseStoreUri, '', $_SERVER['REQUEST_URI']);;
        }
    }

    /**
     * Helper function that gains all information about the current state string. Ignores the current item in the state.
     *
     * @param boolean $withoutFilter Switches use of current item check off to make processing of links from external possible.
     * @return array Link information for further processing.
     */
    protected function _getFilterUrlArrayForCurrentState($withoutFilter) {
        $uid = Mage::helper('mstcore/debug')->start();
        $filterUrlArray = array();
        $query = array();
        //if (Mage::helper('mstcore/version')->getEdition() == 'ee' && Mage::getVersion() >= '1.13.0.0') {
            //$layer = Mage::getSingleton('enterprise_search/catalog_layer');
        //} else {
            $layer = Mage::getSingleton('catalog/layer');
        //}
        foreach ($layer->getState()->getFilters() as $item) {
            if (!$withoutFilter && $this->getName() == $item->getName()) {
                continue;
            }

            if ($item->getFilter() instanceof Mage_Catalog_Model_Layer_Filter_Attribute) {
                $optionIds = $item->getValue();

                if (!is_array($optionIds)) {
                    $optionIds = array($optionIds);
                }
                //Mage::log($optionIds);
                Mage::helper('mstcore/debug')->dump($uid, array('$itemData' => $item->getData(), '$optionIds' => $optionIds));
                foreach ($optionIds as $optionId) {
                    $position = $item->getFilter()->getAttributeModel()->getId();

                    // if(isset($filterUrlArray[$position])){
                    //     while(isset($filterUrlArray[$position])){ // Search free position in array
                    //         $position++;
                    //     }
                    // }
                    $filterUrlArray[$position] = $this->_getRewriteForFilterOption($item->getFilter(), $optionId);
                }
            }
            else {
                $value = $item->getValue();
                if (is_array($value)) {
                    $version = Mage::getVersionInfo();
                    if ($version['major'] = 1 && $version['minor'] >= 7) {
                        $value = implode('-', $value);
                    } else {
                        $value = implode(',', $value);
                    }
                }
                $query[$item->getFilter()->getRequestVar()] = $value;
                Mage::helper('mstcore/debug')->dump($uid, $query);
            }
        }

        $filterUrlArray = array('filterUrl' => $filterUrlArray, 'query' => $query);

        Mage::helper('mstcore/debug')->end($uid, array('$filterUrlArray' => $filterUrlArray));

        return $filterUrlArray;
    }

    /**
     * Gets rewrite string for given attribute filter - value combination.
     *
     * @param Mage_Catalog_Model_Layer_Filter_Attribute $filter The given filter attribute model as object.
     * @param int $value The current value to be gathered.
     * @return string Return the gathered string or NULL.
     */
    protected function _getRewriteForFilterOption($filter, $value)
    {
        $uid = Mage::helper('mstcore/debug')->start();
        $rewrite = Mage::getModel('seofilter/rewrite')
                ->loadByFilterOption($filter, $value);
        $rewrite_value = $rewrite->getRewrite();

        Mage::helper('mstcore/debug')->end($uid, array('$rewrite_value' => $rewrite_value, '$rewriteData'=> $rewrite->getData()));
        return $rewrite_value;
    }
}
