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
 * Parser for given url string. Tries to map the string on attribute options to rebuild the underlying parameters.
 *
 * @category Mirasvit_SeoFilter
 * @package Mirasvit_SeoFilter
 * @author Michael TÃ¼rk <tuerk@flagbit.de>
 * @copyright 2012 Flagbit GmbH & Co. KG (http://www.flagbit.de). All rights served.
 * @license http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @version 0.1.0
 * @since 0.1.0
 */
class Mirasvit_SeoFilter_Model_Parser extends Mage_Core_Model_Abstract
{
    /**
    * we need this function, because otherwise we have a bug with SEO URLs after magento compliation
    */
    private function checkCategoryId($entityId) {
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        $select = $readConnection->select()
            ->from($resource->getTableName('catalog/category'), 'entity_id')
            ->where('entity_id = :entity_id');
        $bind =  array('entity_id' => $entityId);

        return $readConnection->fetchOne($select, $bind);
    }

    /**
     * Tries to parse a given request path and return the corresponding request parameters.
     *
     * @param string $requestString The request path string to be parsed.
     * @param int $storeId The current stores id (can be multilingual).
     * @param array|false Returns the array of request parameters on success, false otherwise.
     */
    public function parseFilterInformationFromRequest($requestString, $storeId) {
        $uid = Mage::helper('mstcore/debug')->start();
        // case 1: there is a speaking url for current request path -> not our business
        $rewrite = Mage::getResourceModel('catalog/url')->getRewriteByRequestPath($requestString, $storeId);
        if ($rewrite && $rewrite->getUrlRewriteId()) {
            return false;
        }

        $configUrlSuffix = Mage::getStoreConfig('catalog/seo/category_url_suffix');
        if ($configUrlSuffix != '' && $configUrlSuffix[0] != '.') {
            $configUrlSuffix = '.'.$configUrlSuffix;
        }
        $shortRequestString = substr($requestString, 0, strrpos($requestString, '/'));

        if (Mage::helper('mstcore/version')->getEdition() == 'ee' && Mage::getVersion() >= '1.13.0.0') { //enterprice
            $request = array(
                'request' => $shortRequestString.$configUrlSuffix,
                'suffix' => $shortRequestString
                );
            $rewrite = Mage::getModel('enterprise_urlrewrite/url_rewrite')->loadByRequestPath($request);
            if (!$rewrite) {
                return false;
            }
            $categoryId = (int)str_replace('catalog/category/view/id/', '', $rewrite->getTargetPath());
            if (!$categoryId) {
                return false;
            }
        } else {
            //if user enter / suffix $shortRequestString = $shortRequestString
            if ($configUrlSuffix != './') {
                $shortRequestString = $shortRequestString.$configUrlSuffix;
            }
            $rewrite = Mage::getResourceModel('catalog/url')->getRewriteByRequestPath($shortRequestString, $storeId);

            Mage::helper('mstcore/debug')->dump($uid, array('$shortRequestString' => $shortRequestString));
            // case 2: the shortened request path cannot be found as rewrite -> no category -> not our business
            if (!$rewrite || !$rewrite->getUrlRewriteId() || !$rewrite->getCategoryId()) {
                return false;
            }

            // case 3: we have a category. May be our business.
            $categoryId = $rewrite->getCategoryId();
        }

        if (!$this->checkCategoryId($categoryId)) {
            return false;
        }


        // get last part of the URL - if we have filter base urls the filter options are lowercased and concetenated by
        // dashes. The standard file extension of catalog pages may have to be removed first.
        $filterString = substr($requestString, strrpos($requestString, '/') + 1);
        if (substr($filterString, -strlen($configUrlSuffix)) == $configUrlSuffix) {
            $filterString = substr($filterString, 0, -strlen($configUrlSuffix));
        }

        // get different filter option values and active filterable attributes
        // if one of them is empty, this is not our business
        $filterInfos = explode('-', $filterString);

        // try to translate filter option values to request parameters using the rewrite models
        $params = array();
        $rewriteCollection = Mage::getModel('seofilter/rewrite')
            ->getCollection()
            ->addFieldToFilter('rewrite', array('in' => $filterInfos))
            ->addFieldToFilter('store_id', $storeId);

        // Ugly workaround. If rewrite doesn't exist in the current store view,
        // search for the rewrite in other store views and take the first.
        // @todo generate non existing rewrites on every filterurl request
        if(count($rewriteCollection) == 0)
        {
            $rewriteCollection = Mage::getModel('seofilter/rewrite')
                ->getCollection()
                ->addFieldToFilter('rewrite', array('in' => $filterInfos));

            $rewriteCollection->getSelect()->group('rewrite');
        }

        if (count($rewriteCollection) == count($filterInfos)) {
            foreach ($rewriteCollection as $rewrite) {
                //@dva fix
                if (!isset($params[$rewrite->getAttributeCode()])) {
                    $params[$rewrite->getAttributeCode()] = array();
                }
                $params[$rewrite->getAttributeCode()][] = $rewrite->getOptionId();
            }
        }
        else {
            return false;
        }

        // return structured result
        $result = array(
            'categoryId' => $categoryId,
            'additionalParams' => $params
        );
        Mage::helper('mstcore/debug')->end($uid, array('$result' => $result));
        return $result;
    }
}