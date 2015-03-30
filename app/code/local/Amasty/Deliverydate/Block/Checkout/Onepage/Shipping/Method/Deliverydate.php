<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Block_Checkout_Onepage_Shipping_Method_Deliverydate extends Mage_Core_Block_Template        
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('amasty/amdeliverydate/deliverydate.phtml');
    }

    protected function _showByGroup($field, $storeId)
    {
        if (Mage::getStoreConfig('amdeliverydate/' . $field . '/enabled_customer_groups', $storeId)) {
            $groupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
            $groups = explode(',', Mage::getStoreConfig('amdeliverydate/' . $field . '/customer_groups', $storeId));
            if (!in_array($groupId, $groups)) {
                return false;
            }
        }
        return true;
    }
    
    public function getFormElements()
    {
        $hlp = Mage::helper('amdeliverydate');
        $storeId = $this->getCurrentStore();

        $form     = new Varien_Data_Form();
        $fieldset = $form->addFieldset('amdeliverydate', array());
        $values = array();
        
        $afterElementHtml = '<div type="anchor" id="anchor_delivery_date"></div>';
        if ($note = Mage::getStoreConfig('amdeliverydate/date_field/note', $storeId)) {
            $afterElementHtml .= '<p class="note" id="note_delivery_date"><span>' . $note . '</span></p>';
        }
        $afterElementHtml .= '<div style="padding: 4px;"></div>'
                             . '<script type="text/javascript">'
                             . 'function amdeliverydate_trig(id)'
                             . '{ $(id).click(); }'
                             . '</script>';

        if ($this->_showByGroup('date_field', $storeId)) {
            $fieldset->addField('delivery_date', 'deliverydate', array(
                'label'    => $hlp->__('Delivery Date'),
                'title'    => $hlp->__('Delivery Date'),
                'name'     => 'amdeliverydate[date]',
                'required' => Mage::getStoreConfig('amdeliverydate/date_field/required', $storeId),
                'readonly' => 1,
                'onclick'  => 'amdeliverydate_trig(delivery_date_trig)',
                'after_element_html' => $afterElementHtml,
                'image' => $this->getSkinUrl('images/amasty/amdeliverydate/grid-cal.gif'),
                'format' => Mage::getStoreConfig('amdeliverydate/date_field/format', $storeId),
            ));

            if (Mage::getStoreConfig('amdeliverydate/date_field/enabled_default', $storeId)) {
                $now = date('U') + 3600 * Mage::getStoreConfig('amdeliverydate/general/offset', $storeId); // 60 min. * 60 sec. = 3600 sec.
                $defaultValue = date('Y', $now) . '-' . date('m', $now) . '-' . (date('d', $now)+(int)Mage::getStoreConfig('amdeliverydate/date_field/default', $storeId));
                if ($hlp->checkDefault($defaultValue, $storeId, $now)) {
                    $values['delivery_date'] = $defaultValue;
                }
            }
        }
        
        if (Mage::getStoreConfig('amdeliverydate/time_field/enabled_time', $storeId)
            && $this->_showByGroup('time_field', $storeId)) {
            $options = $hlp->getTIntervals($storeId);
            if (!empty($options)) {
                $afterElementHtml = '<div type="anchor" id="anchor_delivery_time"></div>';
                if ($note = Mage::getStoreConfig('amdeliverydate/time_field/note', $storeId)) {
                    $afterElementHtml .= '<p class="note" id="note_delivery_time"><span>' . $note . '</span></p>';
                }
                $afterElementHtml .= '<div style="padding: 4px;"></div>';
                
                $fieldset->addField('delivery_time', 'select', array(
                    'label'    => $hlp->__('Delivery Time Interval'),
                    'title'    => $hlp->__('Delivery Time Interval'),
                    'name'     => 'amdeliverydate[time]',
                    'required' => Mage::getStoreConfig('amdeliverydate/time_field/required', $storeId),
                    'values'   => $options,
                    'after_element_html' => $afterElementHtml,
                ));
            }
        }
        
        if (Mage::getStoreConfig('amdeliverydate/comment_field/enabled_comment', $storeId)
            && $this->_showByGroup('comment_field', $storeId)) {
            $afterElementHtml = '<div type="anchor" id="anchor_delivery_comment"></div>';
            if ($note = Mage::getStoreConfig('amdeliverydate/comment_field/note', $storeId)) {
                $afterElementHtml .= '<p class="note" id="note_delivery_comment"><span>' . $note . '</span></p>';
            }
            $afterElementHtml .= '<div style="padding: 4px;"></div>';
            
            $fieldset->addField('delivery_comment', 'textarea', array(
                'label'    => $hlp->__('Delivery Comments'),
                'title'    => $hlp->__('Delivery Comments'),
                'name'     => 'amdeliverydate[comment]',
                'required' => Mage::getStoreConfig('amdeliverydate/comment_field/required', $storeId),
                'after_element_html' => $afterElementHtml,
            ));
        }

        $form->setValues($values);

        return $form->getElements();
    }
    
    protected function _toHtml()
    {
        if (!$this->getFormElements()) {
            return '';
        }
        $html = parent::_toHtml();
        $html = str_replace('</label>', '</label><div style="clear: both;"></div>', $html);

        return $html;
    }
    
    public function getCurrentStore()
    {
        return Mage::app()->getStore()->getId();
    }
    
    protected function _getMethodsForField($ret, $storeId, $node, $field)
    {
        $methods = explode(',', Mage::getStoreConfig('amdeliverydate/' . $node . '/carriers', $storeId));
        if (!empty($methods)) {
            foreach ($methods as $shippingMethod) {
                if (!isset($ret[$shippingMethod])) {
                    $ret[$shippingMethod] = array();
                }
                $ret[$shippingMethod][$field] = $field;
            }
        }
        
        return $ret;
    }

    function getShippingMethodsForOrderAttributes()
    {
        $ret = array();
        $model = Mage::getModel('amorderattr/shipping_methods');

        foreach($model->getCollection() as $method){
            $attribute = Mage::getModel('eav/entity_attribute')->load($method->getAttributeId());
            $attributeCode = $attribute->getAttributeCode();
            $shippingMethod = $method->getShippingMethod();

            if (!isset($ret[$shippingMethod]))
                $ret[$shippingMethod] = array();

            $ret[$shippingMethod][$attributeCode] = $attributeCode;
        }

        return $ret;

    }
    
    public function getShippingMethods()
    {
        $ret = array();

        if (Mage::helper('core')->isModuleEnabled('Amasty_Orderattr')) {
            $ret = $this->getShippingMethodsForOrderAttributes();
        }

        $storeId = $this->getCurrentStore();
        if (Mage::getStoreConfig('amdeliverydate/date_field/enabled_carriers', $storeId)) {
            $ret = $this->_getMethodsForField($ret, $storeId, 'date_field', 'delivery_date');
        }
        if (Mage::getStoreConfig('amdeliverydate/time_field/enabled_carriers', $storeId)) {
            $ret = $this->_getMethodsForField($ret, $storeId, 'time_field', 'delivery_time');
        }
        if (Mage::getStoreConfig('amdeliverydate/comment_field/enabled_carriers', $storeId)) {
            $ret = $this->_getMethodsForField($ret, $storeId, 'comment_field', 'delivery_comment');
        }

        return $ret;
    }
}