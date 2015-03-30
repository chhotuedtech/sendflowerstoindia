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



class Mirasvit_SeoFilter_Model_Catalog_Layer extends Mage_Catalog_Model_Layer
{
    /**
    * Get attribute sets identifiers of current product set
    *
    * @return array
    */
    protected function _getSetIds()
    {
        $key = $this->getStateKey().'_SET_IDS';
        $setIds = $this->getAggregator()->getCacheData($key);
    
        if ($setIds === null) {
            $productCollection = $this->getProductCollection();
            $select = clone $productCollection->getSelect();
            /** @var $select Varien_Db_Select */
            $select->reset(Zend_Db_Select::COLUMNS);
            $select->distinct(true);
            $select->columns('attribute_set_id');
            $setIds = $productCollection->getConnection()->fetchCol($select);
            
            $this->getAggregator()->saveCacheData($setIds, $key, $this->getStateTags());
        }
    
        return $setIds;
    }
}