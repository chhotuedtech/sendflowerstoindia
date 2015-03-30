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


class Mirasvit_Helpdesk_Model_Config_Source_Field_Type
{

    public function toArray()
    {
        return array(
            Mirasvit_Helpdesk_Model_Config::FIELD_TYPE_TEXT => Mage::helper('helpdesk')->__('Text'),
            Mirasvit_Helpdesk_Model_Config::FIELD_TYPE_TEXTAREA => Mage::helper('helpdesk')->__('Multi-line text'),
            Mirasvit_Helpdesk_Model_Config::FIELD_TYPE_DATE => Mage::helper('helpdesk')->__('Date'),
            Mirasvit_Helpdesk_Model_Config::FIELD_TYPE_CHECKBOX => Mage::helper('helpdesk')->__('Checkbox'),
            Mirasvit_Helpdesk_Model_Config::FIELD_TYPE_SELECT => Mage::helper('helpdesk')->__('Drop-down list'),
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