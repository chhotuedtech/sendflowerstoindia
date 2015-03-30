<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Block_Adminhtml_Sliders_Edit_Tab_Thumbnails extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $model    = Mage::registry( 'sliders' );
        $form     = new Varien_Data_Form();
        $fieldset = $form->addFieldset( 'thumbnails_fieldset', array(
             'legend' => Mage::helper( 'dynamicslideshow' )->__( 'Thumbnails Options' ) 
        ) );
        
        // Thumb Width
        $fieldset->addField( 'thumb_width', 'text', array(
             'name' => 'thumb_width',
            'class' => 'validate-number',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Thumb Width' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Thumb Width' ),
            'value' => $model->getData( 'thumb_width' ) ? $model->getData( 'thumb_width' ) : 100,
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'The basic Width of one Thumbnail (only if thumb is selected)' ) 
        ) );
        
        // Thumb Height
        $fieldset->addField( 'thumb_height', 'text', array(
             'name' => 'thumb_height',
            'class' => 'validate-number',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Thumb Height' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Thumb Height' ),
            'value' => $model->getData( 'thumb_height' ) ? $model->getData( 'thumb_height' ) : 50,
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'The basic Height of one Thumbnail (only if thumb is selected)' ) 
        ) );
        
        // Thumb Amount
        $fieldset->addField( 'thumb_amount', 'text', array(
             'name' => 'thumb_amount',
            'class' => 'validate-number',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Thumb Amount' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Thumb Amount' ),
            'value' => $model->getData( 'thumb_amount' ) ? $model->getData( 'thumb_amount' ) : 5,
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'The amount of the Thumbs visible same time (only if thumb is selected). 1 is minimum.' ) 
        ) );
        
        $this->setForm( $form );
        if ( $model->getId() )
            $form->setValues( $model->getData() );
        return parent::_prepareForm();
    }
}