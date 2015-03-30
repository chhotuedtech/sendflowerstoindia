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


class Mirasvit_Helpdesk_Model_Rule_Condition_Ticket extends Mage_Rule_Model_Condition_Abstract
{
    public function loadAttributeOptions()
    {
        $attributes = array(
            'name'   => Mage::helper('helpdesk')->__('Subject'),
            'last_message'   => Mage::helper('helpdesk')->__('Last message body'),
            'created_at'   => Mage::helper('helpdesk')->__('Created At'),
            'updated_at'   => Mage::helper('helpdesk')->__('Updated At'),
            'store_id'   => Mage::helper('helpdesk')->__('Store'),
            'old_priority_id'   => Mage::helper('helpdesk')->__('Priority (before change)'),
            'priority_id'   => Mage::helper('helpdesk')->__('Priority'),
            'old_status_id'   => Mage::helper('helpdesk')->__('Status (before change)'),
            'status_id'   => Mage::helper('helpdesk')->__('Status'),
            'old_department_id'   => Mage::helper('helpdesk')->__('Department (before change)'),
            'department_id'   => Mage::helper('helpdesk')->__('Department'),
            'old_user_id'   => Mage::helper('helpdesk')->__('Owner (before change)'),
            'user_id'   => Mage::helper('helpdesk')->__('Owner'),
            'last_reply_by'   => Mage::helper('helpdesk')->__('Last Reply By'),
            'hours_since_created_at'   => Mage::helper('helpdesk')->__('Hours since Created'),
            'hours_since_updated_at'   => Mage::helper('helpdesk')->__('Hours since Updated'),
            'hours_since_last_reply_at'   => Mage::helper('helpdesk')->__('Hours since Last reply'),
            'tags'   => Mage::helper('helpdesk')->__('Tags'),
        );

        $fields = Mage::getModel('helpdesk/field')->getCollection()
                    ->setOrder('sort_order');

        foreach ($fields as $field) {
           $attributes['old_'.$field->getCode()] = Mage::helper('helpdesk')->__('%s (before change)', $field->getName());
           $attributes[$field->getCode()] = $field->getName();
        }

        // asort($attributes);
        $this->setAttributeOption($attributes);

        return $this;
    }

    public function getAttributeElement()
    {
        $element = parent::getAttributeElement();
        $element->setShowAsText(true);
        return $element;
    }

    public function getInputType()
    {
        $attrCode = $this->getAttribute();
        if (strpos($attrCode, '_id') || $attrCode == 'last_reply_by') {
            return 'select';
        }

        if ($field = $this->getCustomFieldByAttributeCode($attrCode)) {
            if ($field->getType() == 'select') {
                return 'select';
            }
        }
        return 'string';
    }

    public function getValueElementType()
    {
        switch ($this->getInputType()) {
            case 'string':
                return 'text';
        }
        return $this->getInputType();
    }

    public function validate(Varien_Object $object)
    {
        $attrCode = $this->getAttribute();
        if (strpos($attrCode, 'old_') === 0) {
            $attrCode = str_replace('old_', '', $attrCode);
            $value = $object->getOrigData($attrCode);
        } elseif ($attrCode == 'last_message') {
            $value = $object->getLastMessagePlainText();
        } elseif ($attrCode == 'last_reply_by') {
            $lastMessage = $object->getLastMessage();
            if ($lastMessage->getUserId()) {
                $value = 'user';
            } else {
                $value = 'customer';
            }
        } elseif (strpos($attrCode, 'hours_since_') === 0) {
            $attrCode = str_replace('hours_since_', '', $attrCode);
            $timestamp = $object->getData($attrCode);

            $diff = abs(strtotime(Mage::getModel('core/date')->gmtDate()) - strtotime($timestamp));
            $value = round($diff/60/60);

        } elseif ($attrCode == 'tags') {
            $value = Mage::helper('helpdesk/tag')->getTagsAsString($object);
        } else {
            $value = $object->getData($attrCode);
        }
        if (strpos($attrCode, '_id')) {
            $value = (int)$value; //Ð½Ð°Ð¼ ÑÑÐ¾ Ð½ÑÐ¶Ð½Ð¾ ÑÑÐ¾Ð± Ð¿ÑÐ¸Ð²Ð¾Ð´Ð¸ÑÑ Ð¿ÑÑÑÐ¾Ðµ Ð·Ð½Ð°ÑÐµÐ½Ð¸Ðµ Ðº Ð½ÑÐ»Ñ Ð¸ Ð´Ð°Ð»ÐµÐµ ÑÑÐ°Ð²Ð½Ð¸Ð²Ð°ÑÑ
        }
        return $this->validateAttribute($value);
    }


    protected function _prepareValueOptions()
    {
        // Check that both keys exist. Maybe somehow only one was set not in this routine, but externally.
        $selectReady = $this->getData('value_select_options');
        $hashedReady = $this->getData('value_option');
        if ($selectReady && $hashedReady) {
            return $this;
        }
        // Get array of select options. It will be used as source for hashed options
        $selectOptions = null;
        $addNotEmpty = true;
        $field = $this->getCustomFieldByAttributeCode($this->getAttribute());

        if ($field && $field->getType() == 'select') {
            $selectOptions = $field->getValues();
        } else {
            switch ($this->getAttribute()) {
                case 'status_id':
                case 'old_status_id':
                    $selectOptions = Mage::getModel('helpdesk/status')->getCollection()->getOptionArray();
                    break;
                case 'department_id':
                case 'old_department_id':
                    $selectOptions = Mage::getModel('helpdesk/department')->getCollection()->getOptionArray();
                    break;
                case 'priority_id':
                case 'old_priority_id':
                    $selectOptions = Mage::getModel('helpdesk/priority')->getCollection()->getOptionArray();
                    break;
                case 'user_id':
                case 'old_user_id':
                    $selectOptions = Mage::helper('helpdesk')->getAdminUserOptionArray();
                    break;
                case 'store_id':
                    $selectOptions = Mage::helper('helpdesk')->getCoreStoreOptionArray();
                    break;
                case 'last_reply_by':
                    $selectOptions = array(
                            'customer' => Mage::helper('helpdesk')->__('Customer'),
                            'user' => Mage::helper('helpdesk')->__('Staff'),
                        );
                    $addNotEmpty = false;
                    break;
                default:
                    return $this;
            }
        }
        if ($addNotEmpty) {
            $selectOptions = array(0 => '(not set)') + $selectOptions;
            // array_unshift($selectOptions, '(not set)');
        }

        $options1 = array();
        foreach ($selectOptions as $key => $value) {
            $options1[] = array('value' => $key, 'label' => $value);
        }
        $selectOptions = $options1;

        // Set new values only if we really got them
        if ($selectOptions !== null) {
            // Overwrite only not already existing values
            if (!$selectReady) {
                $this->setData('value_select_options', $selectOptions);
            }
            if (!$hashedReady) {
                $hashedOptions = array();
                foreach ($selectOptions as $o) {
                    if (is_array($o['value'])) {
                        continue; // We cannot use array as index
                    }
                    $hashedOptions[$o['value']] = $o['label'];
                }
                $this->setData('value_option', $hashedOptions);
            }
        }

        return $this;
    }

    /**
     * Retrieve value by option
     *
     * @param mixed $option
     * @return string
     */
    public function getValueOption($option=null)
    {
        $this->_prepareValueOptions();
        return $this->getData('value_option'.(!is_null($option) ? '/'.$option : ''));
    }

    /**
     * Retrieve select option values
     *
     * @return array
     */
    public function getValueSelectOptions()
    {
        $this->_prepareValueOptions();
        return $this->getData('value_select_options');
    }


    public function getJsFormObject()
    {
        return 'rule_conditions_fieldset';
    }

    protected function getCustomFieldByAttributeCode($attrCode)
    {
        if (strpos($attrCode, 'f_') === 0 || strpos($attrCode, 'old_f_') === 0) {
            $attrCode = str_replace('old_f_', 'f_', $attrCode);

            if ($field = Mage::helper('helpdesk/field')->getFieldByCode($attrCode)) {
                return $field;
            }
        }
    }

}