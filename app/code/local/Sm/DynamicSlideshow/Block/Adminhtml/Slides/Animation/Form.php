<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */
class Sm_DynamicSlideshow_Block_Adminhtml_Slides_Animation_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $model = Mage::registry( 'animation' );
        $form  = new Varien_Data_Form( array(
             'id' => 'edit_form',
            'method' => 'post' 
        ) );
        
        if ( $model->getId() )
        {
            $model->setData( 'anim_id', $model->getId() );
            $form->addField( 'anim_id', 'hidden', array(
                 'name' => 'id' 
            ) );
        }
        
        $fieldset = $form->addFieldset( 'animation_fieldset', array(
             'legend' => $this->helper( 'dynamicslideshow' )->__( 'Animation Settings' ) 
        ) );
        
        $fieldset->addField( 'anim_preview', 'text', array(
             'note' => Mage::helper( 'dynamicslideshow' )->__( 'Preview Transition (Speed and Easing only for preview)' ) 
        ) );
        $form->getElement( 'anim_preview' )->setRenderer( $this->getLayout()->createBlock( 'dynamicslideshow/adminhtml_widget_form_animation_preview' ) );
        $fieldset->addField( 'name', 'text', array(
             'name' => 'anim-name',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Animation Name' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Animation Name' ),
            'class' => 'required-entry' 
        ) );
        $fieldset->addField( 'anim_speed', 'text', array(
             'label' => Mage::helper( 'dynamicslideshow' )->__( 'Speed' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( '(ms)' ),
            'value' => 500 
        ) );
        $fieldset->addField( 'anim_easing', 'select', array(
             'label' => Mage::helper( 'dynamicslideshow' )->__( 'Easing' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Easing' ),
            'values' => Mage::getModel( 'dynamicslideshow/slides' )->getOptLayerEase() 
        ) );
        $fieldset->addField( 'anim_settings', 'text', array ());
        $form->getElement( 'anim_settings' )->setRenderer( $this->getLayout()->createBlock( 'dynamicslideshow/adminhtml_widget_form_animation_settings' ) );
        
        $form->setUseContainer( true );
        if ( $model->getId() )
            $form->setValues( $model->getData() );
        $this->setForm( $form );
        return parent::_prepareForm();
    }
}