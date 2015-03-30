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


class Mirasvit_Helpdesk_Model_Config_Source_Is_Archive
{

    public function toArray($emptyOption = false)
    {
        $result = array();
        if ($emptyOption) {
            $result[0] = Mage::helper('helpdesk')->__('-- Please Select --');
        }

        $result[Mirasvit_Helpdesk_Model_Config::IS_ARCHIVE_TO_ARCHIVE] = Mage::helper('helpdesk')->__('Move to Archive');
        $result[Mirasvit_Helpdesk_Model_Config::IS_ARCHIVE_FROM_ARCHIVE] = Mage::helper('helpdesk')->__('Move from Archive');

        return $result;
    }

    public function toOptionArray($emptyOption = false)
    {
        $result = array();
        foreach($this->toArray($emptyOption) as $k=>$v) {
            $result[] = array('value'=>$k, 'label'=>$v);
        }
        return $result;
    }

    /************************/
}