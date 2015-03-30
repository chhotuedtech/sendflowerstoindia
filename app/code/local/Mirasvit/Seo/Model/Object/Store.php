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


class Mirasvit_Seo_Model_Object_Store extends Varien_Object
{
    public function _construct() {
        $this->setData(Mage::app()->getStore()->getData());
        if (Mage::getStoreConfig('general/store_information/name')) {
            $this->setName(Mage::getStoreConfig('general/store_information/name'));
        }
        $this->setPhone(Mage::getStoreConfig('general/store_information/phone'));
        $this->setAddress(Mage::getStoreConfig('general/store_information/address'));
        $this->setEmail(Mage::getStoreConfig('trans_email/ident_general/email'));
        $this->setUrl(Mage::getBaseUrl());
    }

}