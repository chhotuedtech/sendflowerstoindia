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


class Mirasvit_Seo_Block_Adminhtml_System_Config_Warning extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
    	$m = false;

    	if (Mage::helper('mstcore')->isModuleInstalled('GoMage_Navigation')) {
    		$m = 'GoMage Advanced Navigation';
    	} elseif (Mage::helper('mstcore')->isModuleInstalled('Amasty_Shopby')) {
    		$m = 'Amasty Shopby';
    	} elseif (Mage::helper('mstcore')->isModuleInstalled('Mana_Filters')) {
            $m = 'Mana Filters';
        } elseif (Mage::helper('mstcore')->isModuleInstalled('MGS_Filterslayer')) {
            $m = 'MGS Filterslayer';
        } elseif (Mage::helper('mstcore')->isModuleInstalled('EM_LayeredNavigation')) {
            $m = 'EM LayeredNavigation';
        }
    	if ($m) {
    		$element->setComment("We recommend to disable this option to avoid possible conflicts, because extension '$m' is installed");
    	}
    	return parent::_getElementHtml($element);
        $element->setValue(Mage::app()->loadCache('admin_notifications_lastcheck'));
        $format = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);
        return Mage::app()->getLocale()->date(intval($element->getValue()))->toString($format);
    }
}
