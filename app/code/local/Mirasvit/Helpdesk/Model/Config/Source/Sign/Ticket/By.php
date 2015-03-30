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


class Mirasvit_Helpdesk_Model_Config_Source_Sign_Ticket_By
{

    public function toArray()
    {
        return array(
            Mirasvit_Helpdesk_Model_Config::SIGN_TICKET_BY_DEPARTMENT => Mage::helper('helpdesk')->__('Using Department Name (e.g. Sales)'),
            Mirasvit_Helpdesk_Model_Config::SIGN_TICKET_BY_USER => Mage::helper('helpdesk')->__('Using Staff Name (e.g. John Doe)'),
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