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
* Resource model for rewrites for filterable attribute options.
*
* @category Mirasvit_SeoFilter
* @package Mirasvit_SeoFilter
* @author Michael TÃ¼rk <tuerk@flagbit.de>
* @copyright 2012 Flagbit GmbH & Co. KG (http://www.flagbit.de). All rights served.
* @license http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
* @version 0.1.0
* @since 0.1.0
*/
class Mirasvit_SeoFilter_Model_Resource_Mysql4_Rewrite extends Mage_Core_Model_Mysql4_Abstract {


    var $attributes = array();
    /**
     * Constructor
     *
     */
    protected function _construct() {

        $this->_init('seofilter/rewrite', 'rewrite_id');
    }

    /**
     * Loads the rewrite model reading a dataset from database using attribute code and option id.
     *
     * @param Mirasvit_SeoFilter_Model_Rewrite $rewrite The model to be loaded.
     * @param string $attributeCode The given attribute code.
     * @param int $optionId The given option id.
     * @return Mirasvit_SeoFilter_Model_Resource_Mysql4_Rewrite Self.
     */
    public function loadByFilterOption(Mirasvit_SeoFilter_Model_Rewrite $rewrite, $attributeCode, $optionId) {
        if (!isset($this->attributes[$attributeCode]) || !isset($this->attributes[$attributeCode][$optionId])) {
            $read = $this->_getReadAdapter();

            if ($read && !empty($attributeCode) && (int) $optionId) {
                $select = $read->select()
                            ->from($this->getMainTable())
                            ->where($this->getMainTable() . '.' . 'attribute_code = ?', $attributeCode)
                            //->where($this->getMainTable() . '.' . 'option_id = ?', $optionId)
                            ->where($this->getMainTable() . '.' . 'store_id = ?', Mage::app()->getStore()->getId());
                            //Mage::log((string)$select);
                $data = $read->fetchAll($select);
                foreach ($data as $item) {
                    $this->attributes[$attributeCode][$item['option_id']] = $item;
                }
            }
        }

        if (isset($this->attributes[$attributeCode][$optionId])) {
            $rewrite->setData($this->attributes[$attributeCode][$optionId]);
        }
        return $this;
    }

    /**
     * Loads the rewrite model reading a dataset from database using the rewrite string.
     *
     * @param Mirasvit_SeoFilter_Model_Rewrite $rewrite The model to be loaded.
     * @param string $rewriteString The rewrite string that is looked for.
     * @return Mirasvit_SeoFilter_Model_Resource_Mysql4_Rewrite Self.
     */
    public function loadByRewriteString(Mirasvit_SeoFilter_Model_Rewrite $rewrite, $rewriteString) {
        $read = $this->_getReadAdapter();

        if ($read && $rewriteString !== '' && $rewriteString !== null) {
            $select = $read->select()
                        ->from($this->getMainTable())
                        ->where($this->getMainTable() . '.' . 'rewrite = ?', $rewriteString)
                        ->where($this->getMainTable() . '.' . 'store_id = ?', Mage::app()->getStore()->getId());

            $data = $read->fetchRow($select);

            if ($data) {
                $rewrite->setData($data);
            }
        }

        return $this;
    }

}