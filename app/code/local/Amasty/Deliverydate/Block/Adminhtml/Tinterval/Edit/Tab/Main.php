<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Block_Adminhtml_Tinterval_Edit_Tab_Main extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        
        $hlp   = Mage::helper('amdeliverydate');
        $model = Mage::registry('amdeliverydate_tinterval');
        
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
        
        $fldMain->addField('from', 'text', array(
            'label'    => $hlp->__('From'),
            'title'    => $hlp->__('From'),
            'name'     => 'from',
        ));
        
        $fldMain->addField('to', 'text', array(
            'label'    => $hlp->__('To'),
            'title'    => $hlp->__('To'),
            'name'     => 'to',
        ));
        
        $fldMain->addField('sorting_order', 'text', array(
            'label'    => $hlp->__('Position'),
            'title'    => $hlp->__('Position'),
            'name'     => 'sorting_order',
        ));
        
        //set form values
        $form->setValues($model->getData());
        
        $this->setForm($form);
        
        return parent::_prepareForm();
    }
}