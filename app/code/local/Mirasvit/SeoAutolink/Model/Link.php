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


class Mirasvit_SeoAutolink_Model_Link extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('seoautolink/link');
    }

    public function loadByKeyword($keyword) {
        $collection = $this->getCollection()
                        ->addFieldToFilter('keyword', $keyword);
         if ($collection->count() > 0) {
             return $collection->getFirstItem();
         }
         return false;
    }
}