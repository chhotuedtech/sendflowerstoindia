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


class Mirasvit_Helpdesk_Model_System_Config_Source_Email_Template extends Mage_Adminhtml_Model_System_Config_Source_Email_Template {

    public function toOptionArray()
    {
        $options = parent::toOptionArray();
        array_unshift( $options,
            array(
                 'value' => 'none',
                 'label' => Mage::helper('helpdesk')->__("- Disable these emails -")
            ));
        return $options;
    }

	/************************/

}