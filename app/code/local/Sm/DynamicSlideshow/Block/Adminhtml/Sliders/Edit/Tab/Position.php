<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Block_Adminhtml_Sliders_Edit_Tab_Position extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $model    = Mage::registry( 'sliders' );
        $form     = new Varien_Data_Form();
        $fieldset = $form->addFieldset( 'position_fieldset', array(
             'legend' => Mage::helper( 'dynamicslideshow' )->__( 'Position Options' ) 
        ) );
        
        $fieldset->addField( 'position', 'select', array(
             'name' => 'position',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Position on the page' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Position on the page' ),
            'values' => $model->getOptLCR(),
            'value' => $model->getData( 'position' ) ? $model->getData( 'position' ) : 'center',
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'The position of the slider on the page (float:left | float:right | margin:0px auto)' ) 
        ) );
        
        $fieldset->addField( 'margin_top', 'text', array(
             'name' => 'margin_top',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Margin Top' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Margin Top' ),
            'class' => 'validate-number',
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'The top margin of the slider wrapper div' ),
            'value' => $model->getData( 'margin_top' ) ? $model->getData( 'margin_top' ) : 0 
        ) );
        
        $fieldset->addField( 'margin_bottom', 'text', array(
             'name' => 'margin_bottom',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Margin Bottom' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Margin Bottom' ),
            'class' => 'validate-number',
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'The bottom margin of the slider wrapper div' ),
            'value' => $model->getData( 'margin_bottom' ) ? $model->getData( 'margin_bottom' ) : 0 
        ) );
        
        $fieldset->addField( 'margin_left', 'text', array(
             'name' => 'margin_left',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Margin Left' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Margin Left' ),
            'class' => 'validate-number',
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'The left margin of the slider wrapper div' ),
            'value' => $model->getData( 'margin_left' ) ? $model->getData( 'margin_left' ) : 0 
        ) );
        
        $fieldset->addField( 'margin_right', 'text', array(
             'name' => 'margin_right',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Margin Right' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Margin Right' ),
            'class' => 'validate-number',
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'The right margin of the slider wrapper div' ),
            'value' => $model->getData( 'margin_right' ) ? $model->getData( 'margin_right' ) : 0 
        ) );
        
        $this->setForm( $form );
        if ( $model->getId() )
            $form->setValues( $model->getData() );
        return parent::_prepareForm();
    }
}