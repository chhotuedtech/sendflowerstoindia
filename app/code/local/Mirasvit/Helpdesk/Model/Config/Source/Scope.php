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


class Mirasvit_Helpdesk_Model_Config_Source_Scope
{

    public function toArray()
    {
        return array(
            Mirasvit_Helpdesk_Model_Config::SCOPE_HEADERS => Mage::helper('helpdesk')->__('Headers'),
            Mirasvit_Helpdesk_Model_Config::SCOPE_SUBJECT => Mage::helper('helpdesk')->__('Subject'),
            Mirasvit_Helpdesk_Model_Config::SCOPE_BODY => Mage::helper('helpdesk')->__('Body'),
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