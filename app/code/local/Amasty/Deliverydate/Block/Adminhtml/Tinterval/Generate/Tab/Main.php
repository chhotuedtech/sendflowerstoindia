<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Block_Adminhtml_Tinterval_Generate_Tab_Main extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        
        $hlp   = Mage::helper('amdeliverydate');
        
        $fldMain = $form->addFieldset('configuration', array('legend'=> $hlp->__('Configuration')));
        
        if (!Mage::app()->isSingleStoreMode()) {
            $fldMain->addField('store_ids', 'multiselect', array(
                'name'     => 'store_ids[]',
                'label'    => $hlp->__('Store View'),
                'title'    => $hlp->__('Store View'),
                'required' => true,
                'values'   => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));
        }
        
        $fldMain->addField('start', 'time', array(
            'label'    => $hlp->__('Starting Time'),
            'title'    => $hlp->__('Starting Time'),
            'name'     => 'start',
        ));
        
        $fldMain->addField('finish', 'time', array(
            'label'    => $hlp->__('Ending Time'),
            'title'    => $hlp->__('Ending Time'),
            'name'     => 'finish',
        ));
        
        $fldMain->addField('step', 'text', array(
            'label'    => $hlp->__('Minutes Interval'),
            'title'    => $hlp->__('Minutes Interval'),
            'name'     => 'step',
            'required' => true,
        ));
        
        $formats = array(
            array(
                'value' => 'H:i',
                'label' => $hlp->__('05:00 - 06:00 (24 Hour Format)'),
            ),
            array(
                'value' => 'G:i',
                'label' => $hlp->__('5:00 - 6:00 (24 Hour Format)'),
            ),
            array(
                'value' => 'h:i a',
                'label' => $hlp->__('05:00 am - 06:00 am (12 Hour Format)'),
            ),
            array(
                'value' => 'h:i A',
                'label' => $hlp->__('05:00 AM - 06:00 AM (12 Hour Format)'),
            ),
            array(
                'value' => 'g:i a',
                'label' => $hlp->__('5:00 am - 6:00 am (12 Hour Format)'),
            ),
            array(
                'value' => 'g:i A',
                'label' => $hlp->__('5:00 AM - 6:00 AM (12 Hour Format)'),
            ),
        );
        
        $fldMain->addField('format', 'select', array(
            'label'  => $hlp->__('Format'),
            'title'  => $hlp->__('Format'),
            'name'   => 'format',
            'values' => $formats,
        ));
        
        $fldMain->addField('sorting_start', 'text', array(
            'label'    => $hlp->__('Staring Value for Position'),
            'title'    => $hlp->__('Staring Value for Position'),
            'name'     => 'sorting_start',
        ));
        
        $fldMain->addField('sorting_step', 'text', array(
            'label'    => $hlp->__('Step for Position'),
            'title'    => $hlp->__('Step for Position'),
            'name'     => 'sorting_step',
        ));
        
        $this->setForm($form);
        
        return parent::_prepareForm();
    }
}