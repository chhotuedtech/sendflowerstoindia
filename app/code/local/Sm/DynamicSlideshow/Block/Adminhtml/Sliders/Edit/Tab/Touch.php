<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Block_Adminhtml_Sliders_Edit_Tab_Touch extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $model    = Mage::registry( 'sliders' );
        $form     = new Varien_Data_Form();
        $fieldset = $form->addFieldset( 'touch_fieldset', array(
             'legend' => Mage::helper( 'dynamicslideshow' )->__( 'Touch Settings' ) 
        ) );
        // Touch Enable
        $fieldset->addField( 'touch', 'radios', array(
             'name' => 'touch',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Touch Enable' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Touch Enable' ),
            'value' => $model->getData( 'touch' ) ? $model->getData( 'touch' ) : $model->OPTION_ON,
            'values' => $model->getOptOnOff(),
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Enable Swipe function on touch devices' ) 
        ) );
        
        $fieldset->addField( 'swipe_velocity', 'text', array(
             'name' => 'swipe_velocity',
            'class' => 'validate-number',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Swipe Velocity (0 - 1)' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Swipe Velocity (0 - 1)' ),
            'value' => $model->getData( 'swipe_velocity' ) ? $model->getData( 'swipe_velocity' ) : 0.7,
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'The Sensibility of Swipe Gesture (lower is more sensible) (Default: 0.7)' ) 
        ) );
        
        $fieldset->addField( 'swipe_min_touches', 'text', array(
             'name' => 'swipe_min_touches',
            'class' => 'validate-number',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Swipe Min Touches ' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Swipe Min Touches ' ),
            'value' => $model->getData( 'swipe_min_touches' ) ? $model->getData( 'swipe_min_touches' ) : 1,
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Min Amount of Fingers to touch (Default: 1)' ) 
        ) );
        
        $fieldset->addField( 'swipe_max_touches', 'text', array(
             'name' => 'swipe_max_touches',
            'class' => 'validate-number',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Swipe Max Touches ' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Swipe Max Touches ' ),
            'value' => $model->getData( 'swipe_max_touches' ) ? $model->getData( 'swipe_max_touches' ) : 1,
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Max Amount of Fingers to touch (Default: 1)' ) 
        ) );
        
        // $fieldset->addField( 'drag_block_vertical', 'radios', array(
             // 'name' => 'drag_block_vertical',
            // 'label' => Mage::helper( 'dynamicslideshow' )->__( 'Drag Block Vertical' ),
            // 'title' => Mage::helper( 'dynamicslideshow' )->__( 'Drag Block Vertical' ),
            // 'value' => $model->getData( 'drag_block_vertical' ) ? $model->getData( 'drag_block_vertical' ) : $model::OPTION_OFF,
            // 'values' => $model->getOptOnOff(),
            // 'note' => Mage::helper( 'dynamicslideshow' )->__( 'Prevent Vertical Scroll on Drag (Default: false)' ) 
        // ) );
        $this->setForm( $form );
        if ( $model->getId() )
            $form->setValues( $model->getData() );
        return parent::_prepareForm();
    }
}