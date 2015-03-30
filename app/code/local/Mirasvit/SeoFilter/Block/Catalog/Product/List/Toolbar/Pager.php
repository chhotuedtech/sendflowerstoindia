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
 * Magento's catalog list toolbar pager. Needs to be adapted to adapt toolbar links to FilterUrls URL scheme.
 *
 * @category Mirasvit_SeoFilter
 * @package Mirasvit_SeoFilter
 * @author Michael TÃ¼rk <tuerk@flagbit.de>
 * @copyright 2012 Flagbit GmbH & Co. KG (http://www.flagbit.de). All rights served.
 * @license http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @version 0.1.0
 * @since 0.1.0
 */
class Mirasvit_SeoFilter_Block_Catalog_Product_List_Toolbar_Pager extends Mage_Page_Block_Html_Pager
{
    public function getPageUrl($page)
    {
        if ($page == 1) {
            $url = Mage::getModel('seofilter/catalog_layer_filter_item')->getSpeakingFilterUrl(FALSE, TRUE, array());
            return $url;
        } else {
            return $this->getPagerUrl(array($this->getPageVarName()=>$page));
        }
    }

    public function getPagerUrl($params=array())
    {
        $url = Mage::getModel('seofilter/catalog_layer_filter_item')->getSpeakingFilterUrl(FALSE, TRUE, $params);
        return $url;
    }
}