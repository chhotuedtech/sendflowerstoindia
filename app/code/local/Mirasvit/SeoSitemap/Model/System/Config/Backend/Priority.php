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


class Mirasvit_SeoSitemap_Model_System_Config_Backend_Priority extends Mage_Core_Model_Config_Data
{
    protected function _beforeSave()
    {
        $value = trim($this->getValue());
        $value = (float)$value;
    	if ($value < 0 || $value > 1) {
    	    throw new Exception(Mage::helper('seositemap')->__('Priority must be between 0 and 1'));
    	}
        return $this;
    }
}
