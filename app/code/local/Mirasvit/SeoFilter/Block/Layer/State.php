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
 * @author Damian Luszczymak
 * @copyright 2012 Flagbit GmbH & Co. KG (http://www.flagbit.de). All rights served.
 * @license http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @version 0.1.0
 * @since 0.1.0
 */

if (Mage::helper('mstcore')->isModuleInstalled('Aitoc_Aitmanufacturers') && class_exists('Aitoc_Aitmanufacturers_Block_Rewrite_CatalogLayerState')) {
    abstract class Mirasvit_SeoFilter_Block_Layer_State_Abstract extends Aitoc_Aitmanufacturers_Block_Rewrite_CatalogLayerState {

    }
} else {
    abstract class Mirasvit_SeoFilter_Block_Layer_State_Abstract extends Mage_Catalog_Block_Layer_State {

    }
}

class Mirasvit_SeoFilter_Block_Layer_State extends Mage_Catalog_Block_Layer_State
{
    /**
     * Retrieve Clear Filters URL
     * Das ist keine schoene Loesung aber sie funktioniert
     *
     * @return string
     */
    public function getClearUrl()
    {
        //support of FISHPIG Attribute Splash Pages http://www.magentocommerce.com/magento-connect/fishpig-s-attribute-splash-pages.html
        if (Mage::registry('splash_page')) {
            return parent::getClearUrl();
        }

        $filterState = array();
        foreach ($this->getActiveFilters() as $item) {
            $filterState[$item->getFilter()->getRequestVar()] = $item->getFilter()->getCleanValue();
        }
        $params['_current']     = true;
        $params['_use_rewrite'] = true;
        $params['_query']       = $filterState;
        $params['_escape']      = true;

        $category = Mage::registry('current_category');

        $rewrite = Mage::getStoreConfig('web/seo/use_rewrites',Mage::app()->getStore()->getId());

        if($rewrite == 1 && is_object($category)) {

            $id = Mage::registry('current_category')->getId();
            return Mage::getModel('catalog/category')->load($id)->getUrl();
        }

        return Mage::getUrl('*/*/*', $params);
    }
}