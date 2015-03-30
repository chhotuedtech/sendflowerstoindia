<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Block_Adminhtml_Dinterval_Edit_Tab_Main extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        
        $hlp   = Mage::helper('amdeliverydate');
        $model = Mage::registry('amdeliverydate_dinterval');
        
        $fldMain = $form->addFieldset('general', array('legend'=> $hlp->__('General')));
        
        if (!Mage::app()->isSingleStoreMode()) {
            $fldMain->addField('store_ids', 'multiselect', array(
                'name'     => 'store_ids[]',
                'label'    => $hlp->__('Store View'),
                'title'    => $hlp->__('Store View'),
                'required' => true,
                'values'   => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));
        }
        
        $format = explode('/', Mage::getStoreConfig('amdeliverydate/general/format'));
        foreach ($format as $part) {
            switch ($part) {
                case 'Y':
                    $fldMain->addField('from_year', 'select', array(
                        'label'  => $hlp->__('From Year'),
                        'title'  => $hlp->__('From Year'),
                        'name'   => 'from_year',
                        'values' => $hlp->getYears($model->getYear()),
                    ));
                    break;
                case 'M':
                    $fldMain->addField('from_month', 'select', array(
                        'label'  => $hlp->__('From Month'),
                        'title'  => $hlp->__('From Month'),
                        'name'   => 'from_month',
                        'values' => $hlp->getMonths(),
                    ));
                    break;
                case 'D':
                    $fldMain->addField('from_day', 'select', array(
                        'label'  => $hlp->__('From Day'),
                        'title'  => $hlp->__('From Day'),
                        'name'   => 'from_day',
                        'values' => $hlp->getDays(),
                    ));
                    break;
            }
        }
        
        foreach ($format as $part) {
            switch ($part) {
                case 'Y':
                    $fldMain->addField('to_year', 'select', array(
                        'label'  => $hlp->__('To Year'),
                        'title'  => $hlp->__('To Year'),
                        'name'   => 'to_year',
                        'values' => $hlp->getYears($model->getYear()),
                    ));
                    break;
                case 'M':
                    $fldMain->addField('to_month', 'select', array(
                        'label'  => $hlp->__('To Month'),
                        'title'  => $hlp->__('To Month'),
                        'name'   => 'to_month',
                        'values' => $hlp->getMonths(),
                    ));
                    break;
                case 'D':
                    $fldMain->addField('to_day', 'select', array(
                        'label'  => $hlp->__('To Day'),
                        'title'  => $hlp->__('To Day'),
                        'name'   => 'to_day',
                        'values' => $hlp->getDays(),
                    ));
                    break;
            }
        }
        
        $fldMain->addField('description', 'text', array(
            'label'    => $hlp->__('Description'),
            'title'    => $hlp->__('Description'),
            'name'     => 'description',
        ));
        
        //set form values
        $form->setValues($model->getData());
        
        $this->setForm($form);
        
        return parent::_prepareForm();
    }
}