<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Block_Adminhtml_Sliders_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $model = Mage::registry( 'sliders' );
        $form  = new Varien_Data_Form();
        $this->setForm( $form );
        $fieldset = $form->addFieldset( 'form_fieldset', array(
             'legend' => Mage::helper( 'dynamicslideshow' )->__( 'General Options' ) 
        ) );
        
        $fieldset->addField( 'title', 'text', array(
             'label' => Mage::helper( 'dynamicslideshow' )->__( 'Title' ),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'title',
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Title of Slider' ) 
        ) );
        
        $fieldset->addField( 'status', 'select', array(
             'name' => 'status',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Status' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Status' ),
            'values' => $model->getOptStatus() 
        ) );
        
        if ( $model->getId() )
            $form->setValues( $model->getData() );
        return parent::_prepareForm();
    }
}