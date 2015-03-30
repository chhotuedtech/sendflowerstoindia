<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Block_Adminhtml_Sliders_Edit_Tab_Appearance extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $model    = Mage::registry( 'sliders' );
        $form     = new Varien_Data_Form();
        $fieldset = $form->addFieldset( 'appearance_fieldset', array(
             'legend' => Mage::helper( 'dynamicslideshow' )->__( 'Appearance Options' ) 
        ) );
        
        // Shadow Type
        $fieldset->addField( 'shadow_type', 'select', array(
             'name' => 'shadow_type',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Shadow Type' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Shadow Type' ),
            'values' => $model->getTypeShadow(),
            'value' => $model->getData( 'shadow_type' ) ? $model->getData( 'shadow_type' ) : 2,
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'The Shadow display underneath the banner. The shadow apply to fixed and responsive modes only, the full width slider don\'t have a shadow' ) 
        ) );
        
        // Show Timer Line
        $fieldset->addField( 'show_timerbar', 'select', array(
             'name' => 'show_timerbar',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Show Timer Line' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Show Timer Line' ),
            'values' => $model->getTimerLine(),
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Show the top running timer line' ) 
        ) );
        
        // Background Color
        $fieldset->addField( 'background_color', 'text', array(
             'name' => 'background_color',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Background Color' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Background Color' ),
            'value' => $model->getData( 'background_color' ) ? $model->getData( 'background_color' ) : 'E8E8E8',
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Slider wrapper div background color, for transparent slider, leave empty' ),
            'class' => 'color {required:false}' 
        ) );
        
        // Dotted Overlay Size
        $fieldset->addField( 'background_dotted_overlay', 'select', array(
             'name' => 'background_dotted_overlay',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Dotted Overlay Size' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Dotted Overlay Size' ),
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Show a dotted overlay on the whole slider, choose width of dots' ),
            'values' => $model->getOptBgrDottedOverlay() 
        ) );
        
        // Padding (border)
        $fieldset->addField( 'padding', 'text', array(
             'name' => 'padding',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Padding (border)' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Padding (border)' ),
            'class' => 'validate-number',
            'value' => $model->getData( 'padding' ) ? $model->getData( 'padding' ) : 0,
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'The wrapper div padding, if it has value, then together with background color it it will make border around the slider' ) 
        ) );
        
        // Show Background Image
        $bg_image0 = $fieldset->addField( 'show_background_image', 'select', array(
             'name' => 'show_background_image',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Show Background Image' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Show Background Image' ),
            'values' => $model->getOptYesNo(),
            'value' => $model::OPTION_NO,
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Yes / No to put background image to the main slider wrapper' ) 
        ) );
        
        // Background Image Url
        $bg_image1 = $fieldset->addField( 'background_image', 'text', array(
             'name' => 'background_image',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Background Image Url' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Background Image Url' ),
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'The background image that will be on the slider wrapper. Will be shown at slider preloading' ) 
        ) );
        
        // Background Fit
        $bg_image2 = $fieldset->addField( 'bg_fit', 'select', array(
             'name' => 'bg_fit',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Background Fit' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Background Fit' ),
            'values' => $model->getOptBgrFit(),
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'How the background will be fitted into the Slider' ) 
        ) );
        
        // Background Repeat
        $bg_image3 = $fieldset->addField( 'bg_repeat', 'select', array(
             'name' => 'bg_repeat',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Background Repeat' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Background Repeat' ),
            'values' => $model->getOptBgrRepeat(),
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'How the background will be repeated into the Slider' ) 
        ) );
        
        // Background Position
        $bg_image4 = $fieldset->addField( 'bg_position', 'select', array(
             'name' => 'bg_position',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Background Position' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Background Position' ),
            'values' => $model->getOptBgrPosition(),
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'How the background will be positioned into the Slider' ) 
        ) );
        
        $form->getElement( 'background_image' )->setRenderer( $this->getLayout()->createBlock( 'dynamicslideshow/adminhtml_widget_form_browsers', '', array(
             'element' => $bg_image1 
        ) ) );
        
        $this->setForm( $form );
        if ( $model->getId() )
            $form->setValues( $model->getData() );
        
        $this->setChild( 'form_after', $this->getLayout()->createBlock( 'adminhtml/widget_form_element_dependence' )->addFieldMap( $bg_image0->getHtmlId(), $bg_image0->getName() )->addFieldMap( $bg_image1->getHtmlId(), $bg_image1->getName() )->addFieldMap( $bg_image2->getHtmlId(), $bg_image2->getName() )->addFieldMap( $bg_image3->getHtmlId(), $bg_image3->getName() )->addFieldMap( $bg_image4->getHtmlId(), $bg_image4->getName() )->addFieldDependence( $bg_image1->getName(), $bg_image0->getName(), 'yes' )->addFieldDependence( $bg_image2->getName(), $bg_image0->getName(), 'yes' )->addFieldDependence( $bg_image3->getName(), $bg_image0->getName(), 'yes' )->addFieldDependence( $bg_image4->getName(), $bg_image0->getName(), 'yes' ) );
        return parent::_prepareForm();
    }
}