<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Block_Adminhtml_Sliders_Edit_Tab_Mobile extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $model    = Mage::registry( 'sliders' );
        $form     = new Varien_Data_Form();
        $fieldset = $form->addFieldset( 'mobile_fieldset', array(
             'legend' => Mage::helper( 'dynamicslideshow' )->__( 'Mobile Options' ) 
        ) );
        
        // Hide Slider Under Width
        $fieldset->addField( 'hide_slider_under', 'text', array(
             'name' => 'hide_slider_under',
            'class' => 'validate-number',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Hide Slider Under Width(px) ' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Hide Slider Under Width(px) ' ),
            'value' => $model->getData( 'hide_slider_under' ) ? $model->getData( 'hide_slider_under' ) : 0,
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Hide the slider under some slider width. Works only in Responsive Style. Not available for Fullwidth.') 
        ) );
        
        // Hide Defined Layers Under Width
        $fieldset->addField( 'hide_defined_layers_under', 'text', array(
             'name' => 'hide_defined_layers_under',
            'class' => 'validate-number',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Hide Defined Layers Under Width (px)' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Hide Defined Layers Under Width (px)' ),
            'value' => $model->getData( 'hide_defined_layers_under' ) ? $model->getData( 'hide_defined_layers_under' ) : 0,
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Hide some defined layers in the layer properties under some slider width.' ) 
        ) );
        
        // Hide All Layers Under Width
        $fieldset->addField( 'hide_all_layers_under', 'text', array(
             'name' => 'hide_all_layers_under',
            'class' => 'validate-number',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Hide All Layers Under Width (px)' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Hide All Layers Under Width (px)' ),
            'value' => $model->getData( 'hide_all_layers_under' ) ? $model->getData( 'hide_all_layers_under' ) : 0,
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Hide all layers under some slider width' ) 
        ) );
        
        //Hide Arrows on Mobile
        $fieldset->addField( 'hide_arrows_on_mobile', 'radios', array(
             'name' => 'hide_arrows_on_mobile',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Hide Arrows on Mobile' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Hide Arrows on Mobile' ),
            'value' => $model::OPTION_OFF,
            'values' => $model->getOptOnOff(),
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Possible Values: "on", "off"  - if set to "on", Arrows are not shown on Mobile Devices
 ' ) 
        ) );
        
        //Hide Bullets on Mobile
        $fieldset->addField( 'hide_bullets_on_mobile', 'radios', array(
             'name' => 'hide_bullets_on_mobile',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Hide Bullets on Mobile' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Hide Bullets on Mobile' ),
            'value' => $model::OPTION_OFF,
            'values' => $model->getOptOnOff(),
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Possible Values: "on", "off"  - if set to "on", Bullets are not shown on Mobile Devices' ) 
        ) );
        
        //Hide Thumbnails on Mobile
        $fieldset->addField( 'hide_thumbs_on_mobile', 'radios', array(
             'name' => 'hide_thumbs_on_mobile',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Hide Thumbnails on Mobile' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Hide Thumbnails on Mobile' ),
            'value' => $model::OPTION_OFF,
            'values' => $model->getOptOnOff(),
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Possible Values: "on", "off"  - if set to "on", Thumbs are not shown on Mobile Devices' ) 
        ) );
        
        //Hide Thumbs Under Resolution
        $fieldset->addField( 'hide_thumbs_under_resolution', 'text', array(
             'name' => 'hide_thumbs_under_resolution',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Hide Thumbs Under Resolution' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Hide Thumbs Under Resolution' ),
            'value' => $model->getData( 'hide_thumbs_under_resolution' ) ? $model->getData( 'hide_thumbs_under_resolution' ) : 0,
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Possible Values: 0 - 1900 - defines under which resolution the Thumbs should be hidden. (only if hideThumbonMobile set to off)' ),
            'class' => 'validate-number' 
        ) );
        $this->setForm( $form );
        if ( $model->getId() )
            $form->setValues( $model->getData() );
        return parent::_prepareForm();
    }
}