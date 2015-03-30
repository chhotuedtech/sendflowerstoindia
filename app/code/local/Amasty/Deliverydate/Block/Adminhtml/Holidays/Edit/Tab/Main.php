<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Block_Adminhtml_Holidays_Edit_Tab_Main extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        
        $hlp   = Mage::helper('amdeliverydate');
        $model = Mage::registry('amdeliverydate_holidays');
        
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
                    $fldMain->addField('year', 'select', array(
                        'label'  => $hlp->__('Year'),
                        'title'  => $hlp->__('Year'),
                        'name'   => 'year',
                        'values' => $hlp->getYears($model->getYear()),
                    ));
                    break;
                case 'M':
                    $fldMain->addField('month', 'select', array(
                        'label'  => $hlp->__('Month'),
                        'title'  => $hlp->__('Month'),
                        'name'   => 'month',
                        'values' => $hlp->getMonths(),
                    ));
                    break;
                case 'D':
                    $fldMain->addField('day', 'select', array(
                        'label'  => $hlp->__('Day'),
                        'title'  => $hlp->__('Day'),
                        'name'   => 'day',
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