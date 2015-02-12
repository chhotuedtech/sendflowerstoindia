<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Block_Adminhtml_Sliders_Edit_Tab_First extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $model    = Mage::registry( 'sliders' );
        $form     = new Varien_Data_Form();
        $fieldset = $form->addFieldset( 'mobile_fieldset', array(
             'legend' => Mage::helper( 'dynamicslideshow' )->__( 'First Slide Options' ) 
        ) );
        
        //Start With Slide
        $fieldset->addField( 'start_with_slide', 'text', array(
             'name' => 'start_with_slide',
            'class' => 'validate-number',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Start' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Start' ),
            'value' => $model->getData( 'start_with_slide' ) ? $model->getData( 'start_with_slide' ) : 1,
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Change it if you want to start from a different slide then 1' ) 
        ) );
        
        //First Transition Active
        $transition_active = $fieldset->addField( 'first_transition_active', 'select', array(
             'name' => 'first_transition_active',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Transition Active' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Transition Active' ),
            'values' => $model->getOptYesNo(),
            'value' => $model->OPTION_NO,
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'If active, it will overwrite the first slide transition. Use it when you want a special transition for the first slide only' ) 
        ) );
        
        //First Transition Type
        $transition_type = $fieldset->addField( 'first_transition_type', 'select', array(
             'name' => 'first_transition_type',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Transition Type' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Transition Type' ),
            'values' => $model->getOptTransistion(),
            'value' => $model->getData( 'first_transition_type' ) ? $model->getData( 'first_transition_type' ) : 'fade',
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'First slide transition type' ) 
        ) );
        
        //First Transition Duration
        $transition_duration = $fieldset->addField( 'first_transition_duration', 'text', array(
             'name' => 'first_transition_duration',
            'class' => 'validate-number',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Transition Duration' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Transition Duration' ),
            'value' => $model->getData( 'first_transition_duration' ) ? $model->getData( 'first_transition_duration' ) : 300,
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'First slide transition duration (Default:300, min: 100 max 2000)' ) 
        ) );
        
        //First Transition Slot Amount
        $transition_slot_amount = $fieldset->addField( 'first_transition_slot_amount', 'text', array(
             'name' => 'first_transition_slot_amount',
            'class' => 'validate-number',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Transition Slot Amount' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Transition Slot Amount' ),
            'value' => $model->getData( 'first_transition_slot_amount' ) ? $model->getData( 'first_transition_slot_amount' ) : 7,
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'The number of slots or boxes the slide is divided into. If you use boxfade, over 7 slots can be juggy' ) 
        ) );
        
        $this->setForm( $form );
        if ( $model->getId() )
            $form->setValues( $model->getData() );
        $this->setChild( 'form_after', $this->getLayout()->createBlock( 'adminhtml/widget_form_element_dependence' )->addFieldMap( $transition_active->getHtmlId(), $transition_active->getName() )->addFieldMap( $transition_type->getHtmlId(), $transition_type->getName() )->addFieldMap( $transition_duration->getHtmlId(), $transition_duration->getName() )->addFieldMap( $transition_slot_amount->getHtmlId(), $transition_slot_amount->getName() )->addFieldDependence( $transition_type->getName(), $transition_active->getName(), 'yes' )->addFieldDependence( $transition_duration->getName(), $transition_active->getName(), 'yes' )->addFieldDependence( $transition_slot_amount->getName(), $transition_active->getName(), 'yes' ) );
        return parent::_prepareForm();
    }
}