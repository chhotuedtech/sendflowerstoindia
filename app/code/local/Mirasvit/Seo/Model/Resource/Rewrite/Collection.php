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


class Mirasvit_Seo_Model_Resource_Rewrite_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{

    /**
     * Constructor method
     */
    protected function _construct()
    {
        $this->_init('seo/rewrite');
    }

    /**
     * Add Filter by store
     *
     * @param int|Mage_Core_Model_Store $store
     * @return My_rewrite_Model_Mysql4_News_Collection
     */
    public function addStoreFilter($store)
    {
        if (!Mage::app()->isSingleStoreMode()) {
            if ($store instanceof Mage_Core_Model_Store) {
                $store = array($store->getId());
            }

            $this->getSelect()
                ->join(array('store_table' => $this->getTable('seo/rewrite_store')), 'main_table.rewrite_id = store_table.rewrite_id', array())
                ->where('store_table.store_id in (?)', array(0, $store));
            return $this;
        }
        return $this;
    }

    /**
     * Add Filter by status
     *
     * @param int $status
     * @return My_rewrite_Model_Mysql4_News_Collection
     */
    public function addEnableFilter($status = 1)
    {
        $this->getSelect()->where('main_table.is_active = ?', $status);
        return $this;
    }
}