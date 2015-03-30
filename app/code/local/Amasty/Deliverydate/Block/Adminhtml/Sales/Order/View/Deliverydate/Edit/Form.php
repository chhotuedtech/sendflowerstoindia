<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Block_Adminhtml_Sales_Order_View_Deliverydate_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _getStoreId()
    {
        if (Mage::registry('current_order')) {
            return Mage::registry('current_order')->getStoreId();
        }
        if (Mage::registry('current_invoice')) {
            return Mage::registry('current_invoice')->getStoreId();
        }
        if (Mage::registry('current_shipment')) {
            return Mage::registry('current_shipment')->getStoreId();
        }
    }
    
    protected function _prepareForm()
    {
        $hlp = Mage::helper('amdeliverydate');
        $storeId = $this->_getStoreId();
        
        $form = new Varien_Data_Form(array('id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post'));
        $form->setUseContainer(true);
        
        $fieldset = $form->addFieldset('base_fieldset',
            array('legend' => $hlp->__('Delivery Date'))
        );
        
        $afterElementHtml = '<div style="padding: 4px;"></div>'
                            . '<script type="text/javascript">'
                            . 'function amdeliverydate_trig(id)'
                            . '{ $(id).click(); }'
                            . '</script>';
        
        $fieldset->addField('date', 'deliverydate', array(
            'label'    => $hlp->__('Delivery Date'),
            'title'    => $hlp->__('Delivery Date'),
            'name'     => 'date',
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
                $fieldset->addField('time', 'select', array(
                    'label'    => $hlp->__('Delivery Time Interval'),
                    'title'    => $hlp->__('Delivery Time Interval'),
                    'name'     => 'time',
                    'required' => Mage::getStoreConfig('amdeliverydate/time_field/required', $storeId),
                    'values'   => $options,
                    'after_element_html' => $afterElementHtml,
                ));
            }
        }
        
        if (Mage::getStoreConfig('amdeliverydate/comment_field/enabled_comment', $storeId)) {
            $afterElementHtml = '<div style="padding: 4px;"></div>';
            $fieldset->addField('comment', 'textarea', array(
                'label'    => $hlp->__('Delivery Comments'),
                'title'    => $hlp->__('Delivery Comments'),
                'name'     => 'comment',
                'after_element_html' => $afterElementHtml,
            ));
        }
        
        $form->setValues(Mage::registry('current_deliverydate')->getData());
        $this->setForm($form);
        parent::_prepareForm();
        return $this;
    }
}