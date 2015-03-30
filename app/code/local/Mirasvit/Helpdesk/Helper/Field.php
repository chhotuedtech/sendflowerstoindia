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


class Mirasvit_Helpdesk_Helper_Field extends Mage_Core_Helper_Abstract
{
    public function getEditableCustomerCollection()
    {
        return Mage::getModel('helpdesk/field')->getCollection()
            ->addFieldToFilter('is_active', true)
            ->addFieldToFilter('is_editable_customer', true)
            ->addStoreFilter(Mage::app()->getStore()->getId())
            ->setOrder('sort_order');
    }

    public function getVisibleCustomerCollection()
    {
        return Mage::getModel('helpdesk/field')->getCollection()
            ->addFieldToFilter('is_active', true)
            ->addFieldToFilter('is_visible_customer', true)
            ->addStoreFilter(Mage::app()->getStore()->getId())
            ->setOrder('sort_order');
    }

    public function getContactFormCollection()
    {
        return Mage::getModel('helpdesk/field')->getCollection()
            ->addFieldToFilter('is_active', true)
            ->addFieldToFilter('is_visible_contact_form', true)
            ->addStoreFilter(Mage::app()->getStore()->getId())
            ->setOrder('sort_order');
    }

    public function getStaffCollection()
    {
        return Mage::getModel('helpdesk/field')->getCollection()
            ->addFieldToFilter('is_active', true)
            ->setOrder('sort_order');
    }

    public function getActiveCollection()
    {
        return Mage::getModel('helpdesk/field')->getCollection()
            ->addFieldToFilter('is_active', true)
            ->addStoreFilter(Mage::app()->getStore()->getId())
            ->setOrder('sort_order');
    }

    public function getInputParams($field, $staff = true, $ticket = false)
    {
        return array(
            'label'    => Mage::helper('helpdesk')->__($field->getName()),
            'name'     => $field->getCode(),
            'required' => $staff? $field->getIsRequiredStaff(): $field->getIsRequiredCustomer(),
            'value'    => $field->getType() == 'checkbox'? 1 : ($ticket? $ticket->getData($field->getCode()): ''),
            'checked'  => $ticket? $ticket->getData($field->getCode()): false,
            'values'   => $field->getValues(true),
            'image'    => Mage::getDesign()->getSkinUrl('images/grid-cal.gif'),
            'note'     => $field->getDescription(),
            'format'   => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT)
        );
    }

    public function getInputHtml($field)
    {
        $params = $this->getInputParams($field, false);
        unset($params['label']);
        $className = 'Varien_Data_Form_Element_'.ucfirst(strtolower($field->getType()));
        $element = new $className($params);
        $element->setForm(new Varien_Object());
        $element->setId($field->getCode());
        $element->setNoSpan(true);
        if ($field->getIsRequiredCustomer()) {
            $element->addClass('required-entry');
        }

        return $element->toHtml();
    }

    public function processPost($post, $ticket)
    {
        $collection = Mage::helper('helpdesk/field')->getActiveCollection();
        foreach ($collection as $field) {
            if (isset($post[$field->getCode()])) {
                $value = $post[$field->getCode()];
                $ticket->setData($field->getCode(), $value);
            }
            if ($field->getType() == 'checkbox') {
                if (!isset($post[$field->getCode()])) {
                    $ticket->setData($field->getCode(), 0);
                }
            } elseif ($field->getType() == 'date') {
                $format = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
                Mage::helper('mstcore/date')->formatDateForSave($ticket, $field->getCode(), $format);
            }
        }
    }

    public function getValue($ticket, $field)
    {
        $value = $ticket->getData($field->getCode());
        if (!$value) {
            return false;
        }
        if ($field->getType() == 'checkbox') {
            $value = $value? Mage::helper('helpdesk')->__('Yes'): Mage::helper('helpdesk')->__('No');
        } elseif ($field->getType() == 'date') {
            $value = Mage::helper('core')->formatDate($value, Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);
        } elseif ($field->getType() == 'select') {
            $values = $field->getValues();
            $value = $values[$value];
        }

        return $value;
    }

    public function getFieldByCode($code)
    {
        $field = Mage::getModel('helpdesk/field')->getCollection()
            ->addFieldToFilter('code', $code)
            ->getFirstItem();
        if ($field->getId()) {
            return $field;
        }
    }
}