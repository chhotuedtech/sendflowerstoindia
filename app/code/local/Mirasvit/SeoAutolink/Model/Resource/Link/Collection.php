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


class Mirasvit_SeoAutolink_Model_Resource_Link_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('seoautolink/link');
    }

    public function toOptionArray()
    {
    	$this->addFieldToFilter('is_active', 1)
    		->setOrder('sort_order', 'asc');
        return $this->_toOptionArray('link_id');
    }
    
    public function addActiveFilter() {
        $date = Mage::app()->getLocale()->date();
        $activeFrom = array();
        $activeFrom[] = array('date' => true, 'to' => $date->toString('YYYY-MM-dd H:mm:ss'));
        $activeFrom[] = array('date' => true, 'null' => true);

        $activeTo = array();
        $activeTo[] = array('date' => true, 'from' => $date->toString('YYYY-MM-dd H:mm:ss'));
        $activeTo[] = array('date' => true, 'null' => true);

        $this->addFieldToFilter('active_from', $activeFrom)
                    ->addFieldToFilter('active_to', $activeTo)
                    ->addFieldToFilter('is_active', 1);
        return $this;    	
    }
    
    public function addStoreFilter($store)
    {
        if (!Mage::app()->isSingleStoreMode()) {
            if ($store instanceof Mage_Core_Model_Store) {
                $store = array($store->getId());
            }

            $this->getSelect()
                ->joinLeft(array('store_table' => $this->getTable('seoautolink/link_store')), 'main_table.link_id = store_table.link_id', array())
                ->where('store_table.store_id in (?)', array(0, $store));
            return $this;
        }
        return $this;
    }    
}