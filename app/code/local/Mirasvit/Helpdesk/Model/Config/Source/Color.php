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
 * @package   Help Desk MX
 * @version   1.0.7
 * @build     907
 * @copyright Copyright (C) 2014 Mirasvit (http://mirasvit.com/)
 */


class Mirasvit_Helpdesk_Model_Config_Source_Color
{

    public function toArray()
    {
        return array(
            'aqua' => Mage::helper('helpdesk')->__('Aqua'),
            'grey' => Mage::helper('helpdesk')->__('Grey'),
            'navy' => Mage::helper('helpdesk')->__('Navy'),
            'silver' => Mage::helper('helpdesk')->__('Silver'),
            'black' => Mage::helper('helpdesk')->__('Black'),
            'green' => Mage::helper('helpdesk')->__('Green'),
            'olive' => Mage::helper('helpdesk')->__('Olive'),
            'teal' => Mage::helper('helpdesk')->__('Teal'),
            'blue' => Mage::helper('helpdesk')->__('Blue'),
            'lime' => Mage::helper('helpdesk')->__('Lime'),
            'purple' => Mage::helper('helpdesk')->__('Purple'),
            'fuchsia' => Mage::helper('helpdesk')->__('Fuchsia'),
            'maroon' => Mage::helper('helpdesk')->__('Maroon'),
            'red' => Mage::helper('helpdesk')->__('Red'),
            'orange' => Mage::helper('helpdesk')->__('Orange'),
            'yellow' => Mage::helper('helpdesk')->__('Yellow'),
        );
    }
    public function toOptionArray()
    {
        $result = array();
        foreach($this->toArray() as $k=>$v) {
            $result[] = array('value'=>$k, 'label'=>$v);
        }
        return $result;
    }

    /************************/
}