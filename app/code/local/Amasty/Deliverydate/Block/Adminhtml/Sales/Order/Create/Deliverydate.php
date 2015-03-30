<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (http://www.amasty.com)
 * @package Amasty_Deliverydate
 */
class Amasty_Deliverydate_Block_Adminhtml_Sales_Order_Create_Deliverydate extends Mage_Adminhtml_Block_Template
{
    protected $_formElements = array();

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('amasty/amdeliverydate/create_order.phtml');

        $orderId = 0;
        if (Mage::getSingleton('adminhtml/session_quote')->getOrderId()) { // edit order
            $orderId = Mage::getSingleton('adminhtml/session_quote')->getOrderId();
        } elseif (Mage::getSingleton('adminhtml/session_quote')->getReordered()) { // reorder
            $orderId = Mage::getSingleton('adminhtml/session_quote')->getReordered();
        }

        if ($orderId) {
            $deliveryDate = Mage::getModel('amdeliverydate/deliverydate');
            $deliveryDate->load($orderId, 'order_id');
            Mage::register('current_deliverydate', $deliveryDate, true);
        }
    }

    public function getFormElements()
    {
        if ($this->_formElements) {
            return $this->_formElements;
        }

        $hlp = Mage::helper('amdeliverydate');
        $storeId = Mage::getSingleton('adminhtml/session_quote')->getStoreId();

        $form = new Varien_Data_Form();
        $fieldset = $form->addFieldset('amdeliverydate', array());

        $afterElementHtml = '<div style="padding: 4px;"></div>'
            . '<script type="text/javascript">'
            . 'function amdeliverydate_trig(id)'
            . '{ $(id).click(); }'
            . '</script>';

        $fieldset->addField('delivery_date', 'deliverydate', array(
            'label'    => $hlp->__('Delivery Date'),
            'title'    => $hlp->__('Delivery Date'),
            'name'     => 'amdeliverydate[date]',
            'required' => Mage::getStoreConfig('amdeliverydate/date_field/required', $storeId),
            'readonly' => 1,
            'onclick'  => 'amdeliverydate_trig(delivery_date_trig)',
            'after_element_html' => $afterElementHtml,
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format' => Mage::getStoreConfig('amdeliverydate/date_field/format'),
        ));

        if (Mage::getStoreConfig('amdeliverydate/time_field/enabled_time', $storeId)) {
            $options = $hlp->getTIntervals($storeId);
            if (!empty($options)) {
                $afterElementHtml = '<div style="padding: 4px;"></div>';
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

        if (Mage::getStoreConfig('amdeliverydate/comment_field/enabled_comment', $storeId)) {
            $afterElementHtml = '<div style="padding: 4px;"></div>';
            $fieldset->addField('delivery_comment', 'textarea', array(
                'label'    => $hlp->__('Delivery Comments'),
                'title'    => $hlp->__('Delivery Comments'),
                'name'     => 'amdeliverydate[comment]',
                'after_element_html' => $afterElementHtml,
            ));
        }

        if (Mage::registry('current_deliverydate')) {
            $temp = array();
            foreach (Mage::registry('current_deliverydate')->getData() as $key => $value) {
                $temp['delivery_' . $key] = $value;
            }
            $form->setValues($temp);
        }

        $this->_formElements = $form->getElements();
        return $this->_formElements;
    }
}
