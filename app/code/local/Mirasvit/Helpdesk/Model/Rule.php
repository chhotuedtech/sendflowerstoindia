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


class Mirasvit_Helpdesk_Model_Rule extends Mage_Rule_Model_Rule
{
    const TYPE_PRODUCT   = 'product';
    const TYPE_CART   = 'cart';
    const TYPE_TICKET = 'ticket';

    protected function _construct()
    {
        $this->_init('helpdesk/rule');
    }

    public function toOptionArray($emptyOption = false)
    {
    	return $this->getCollection()->toOptionArray($emptyOption);
    }

    /** Rule Methods **/

    public function getConditionsInstance()
    {
        return Mage::getModel('helpdesk/rule_condition_combine');
    }

    public function getActionsInstance()
    {
        return Mage::getModel('helpdesk/rule_action_collection');
    }

    public function getProductIds()
    {
        return $this->_getResource()->getRuleProductIds($this->getId());
    }

    public function toString($format = '')
    {
        $this->load($this->getId());
        $string = $this->getConditions()->asStringRecursive();

        $string = nl2br(preg_replace('/ /', '&nbsp;', $string));

        return $string;
    }
	/************************/

    public function getNotificationEmailTemplate()
    {
        if (!$this->getData('notification_email_template')) {
            return 'helpdesk_rule_notification_email_template';
        }
        return $this->getData('notification_email_template');
    }

}