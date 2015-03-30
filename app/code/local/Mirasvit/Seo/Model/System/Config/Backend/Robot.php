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


class Mirasvit_Seo_Model_System_Config_Backend_Robot extends Mage_Core_Model_Config_Data
{
    protected function _afterSave()
    {
        if ($this->getValue() != '' && file_exists($this->getFilename()) && !is_writable($this->getFilename())) {
            throw new Mage_Core_Exception('Can\'t write to the file robots.txt. Please, set its permissions to 777.');
        }

        @file_put_contents($this->getFilename(), utf8_encode($this->getValue()));
    }

    protected function _afterLoad()
    {
        $text = '';
        if (file_exists($this->getFilename())) {
            $text = @file_get_contents($this->getFilename());
        }
        // if (!$text) {
        //     if (!is_writable($this->getFilename())) {
        //         Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Can\'t write and read the file robots.txt. Please, set its permissions to 777.'));
        //     }
        // }
        $this->setValue($text);
    }

    protected function getFilename() {
        return Mage::getBaseDir().'/robots.txt';
    }
}
