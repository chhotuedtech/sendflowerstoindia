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


class Mirasvit_Seo_Model_Rewrite extends Mage_Core_Model_Abstract
{
    const CACHE_LIFETIME = 3600;
    const CACHE_TAG      = 'seo';
    
    protected $_cacheTag =  self::CACHE_TAG;

    protected function _construct()
    {
        $this->_init('seo/rewrite');
    }
}