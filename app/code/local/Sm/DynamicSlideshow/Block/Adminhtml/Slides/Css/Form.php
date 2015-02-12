<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Block_Adminhtml_Slides_Css_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $model = Mage::registry( 'css' );
        $form  = new Varien_Data_Form( array(
             'id' => 'edit_form',
            'method' => 'post' 
        ) );
        
        if ( $model->getId() )
        {
            $form->addField( 'id', 'hidden', array(
                 'name' => 'id' 
            ) );
        }
        $setting = Mage::helper( 'core' )->jsonDecode( $model->getSettings() );
        if ( isset( $setting['hover'] ) )
            $model->setUsingHover( true );
        $preview = $form->addFieldset( 'css_preview_fieldset', array(
             'class' => 'no-spacing' 
        ) );
        $preview->addField( 'css_preview', 'text', array ());
        $form->getElement( 'css_preview' )->setRenderer( $this->getLayout()->createBlock( 'dynamicslideshow/adminhtml_widget_form_css_preview' ) );
        $preview->addField( 'css_state', 'radios', array(
             'name' => 'css_state',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'State' ),
            'value' => 1,
            'values' => array(
                 array(
                     'value' => 1,
                    'label' => Mage::helper( 'dynamicslideshow' )->__( 'Normal' ) 
                ),
                array(
                     'value' => 2,
                    'label' => Mage::helper( 'dynamicslideshow' )->__( 'Hover' ) 
                ) 
            ) 
        ) );
        
        $preview->addField( 'css_hover', 'radios', array(
             'name' => 'css_hover',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Using Hover' ),
            'value' => $model->getUsingHover() ? 1 : 2,
            'values' => array(
                 array(
                     'value' => 1,
                    'label' => Mage::helper( 'dynamicslideshow' )->__( 'Yes' ) 
                ),
                array(
                     'value' => 2,
                    'label' => Mage::helper( 'dynamicslideshow' )->__( 'No' ) 
                ) 
            ) 
        ) );
        
        $preview->addField( 'css_mode', 'radios', array(
             'name' => 'css_mode',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Edit Mode' ),
            'value' => 1,
            'values' => array(
                 array(
                     'value' => 1,
                    'label' => Mage::helper( 'dynamicslideshow' )->__( 'Simple' ) 
                ),
                array(
                     'value' => 2,
                    'label' => Mage::helper( 'dynamicslideshow' )->__( 'Advanced' ) 
                ) 
            ) 
        ) );
        
        $container = $form->addFieldset( 'css_container_fieldset', array(
             'class' => 'no-spacing' 
        ) );
        
        $advance = $form->addFieldset( 'css_advance_fieldset', array(
             'class' => 'no-spacing' 
        ) );
        $f4      = $advance->addFieldset( 'css_advance_fieldset_content', array(
             'legend' => Mage::helper( 'dynamicslideshow' )->__( 'Advanced Style' ) 
        ) );
        $f4->addField( 'css_css', 'textarea', array(
             'name' => 'css_css' 
        ) );
        $form->getElement( 'css_css' )->setRenderer( $this->getLayout()->createBlock( 'dynamicslideshow/adminhtml_widget_form_code' ) );
        
        $left  = $container->addFieldset( 'left_fieldset', array(
             'class' => 'no-spacing' 
        ) );
        $right = $container->addFieldset( 'right_fieldset', array(
             'class' => 'no-spacing' 
        ) );
        
        // Font Fieldset
        $f1 = $left->addFieldset( 'css_font_fieldset', array(
             'legend' => Mage::helper( 'dynamicslideshow' )->__( 'Font' ),
            'class' => 'popup-half-fieldset' 
        ) );
        $f1->addField( 'css_font-family', 'text', array(
             'name' => 'css_font-family',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Family' ) 
        ) );
        $f1->addField( 'css_color', 'text', array(
             'name' => 'css_color',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Color' ),
            'class' => 'color {required:false}' 
        ) );
        $f1->addField( 'css_padding', 'text', array(
             'name' => 'css_padding[]',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Padding' ),
            'labels' => array(
                 Mage::helper( 'dynamicslideshow' )->__( 'Top' ),
                Mage::helper( 'dynamicslideshow' )->__( 'Right' ),
                Mage::helper( 'dynamicslideshow' )->__( 'Bottom' ),
                Mage::helper( 'dynamicslideshow' )->__( 'Left' ) 
            ),
            'min' => 0,
            'max' => 150,
            'count' => 4 
        ) );
        $form->getElement( 'css_padding' )->setRenderer( $this->getLayout()->createBlock( 'dynamicslideshow/adminhtml_widget_form_four' ) );
        $f1->addField( 'css_font-style', 'select', array(
             'name' => 'css_font-style',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Style' ),
            'values' => Mage::getModel( 'dynamicslideshow/slides' )->getOptCssFontStyle() 
        ) );
        $f1->addField( 'css_font-size', 'text', array(
             'name' => 'css_font-size',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Size' ),
            'min' => 0,
            'max' => 150 
        ) );
        $form->getElement( 'css_font-size' )->setRenderer( $this->getLayout()->createBlock( 'dynamicslideshow/adminhtml_widget_form_sliders' ) );
        $f1->addField( 'css_line-height', 'text', array(
             'name' => 'css_line-height',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Line-Height' ),
            'min' => 0,
            'max' => 180 
        ) );
        $form->getElement( 'css_line-height' )->setRenderer( $this->getLayout()->createBlock( 'dynamicslideshow/adminhtml_widget_form_sliders' ) );
        $f1->addField( 'css_font-weight', 'text', array(
             'name' => 'css_font-weight',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Weight' ),
            'min' => 100,
            'max' => 900,
            'step' => 100 
        ) );
        $form->getElement( 'css_font-weight' )->setRenderer( $this->getLayout()->createBlock( 'dynamicslideshow/adminhtml_widget_form_sliders' ) );
        $f1->addField( 'css_text-decoration', 'select', array(
             'name' => 'css_text-decoration',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Decoration' ),
            'values' => Mage::getModel( 'dynamicslideshow/slides' )->getOptCssDecoration() 
        ) );
        
        // Background Fieldset
        $f2 = $right->addFieldset( 'css_bg_fieldset', array(
             'legend' => Mage::helper( 'dynamicslideshow' )->__( 'Background' ),
            'class' => 'popup-half-fieldset' 
        ) );
        $f2->addField( 'css_background-color', 'text', array(
             'name' => 'css_background-color',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Color' ),
            'class' => 'color {required:false}' 
        ) );
        $f2->addField( 'css_background-transparency', 'text', array(
             'name' => 'css_background-transparency',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Transparency' ),
            'min' => 0,
            'max' => 100 
        ) );
        $form->getElement( 'css_background-transparency' )->setRenderer( $this->getLayout()->createBlock( 'dynamicslideshow/adminhtml_widget_form_sliders' ) );
        
        // Border Fieldset
        $f3 = $right->addFieldset( 'css_border_fieldset', array(
             'legend' => Mage::helper( 'dynamicslideshow' )->__( 'Border' ),
            'class' => 'popup-half-fieldset' 
        ) );
        $f3->addField( 'css_border-color', 'text', array(
             'name' => 'css_border-color',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Color' ),
            'class' => 'color {required:false}' 
        ) );
        $f3->addField( 'css_border-style', 'select', array(
             'name' => 'css_border-style',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Style' ),
            'values' => Mage::getModel( 'dynamicslideshow/slides' )->getOptCssBorderStyle() 
        ) );
        $f3->addField( 'css_border-width', 'text', array(
             'name' => 'css_border-width',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Width' ),
            'min' => 0,
            'max' => 25 
        ) );
        $form->getElement( 'css_border-width' )->setRenderer( $this->getLayout()->createBlock( 'dynamicslideshow/adminhtml_widget_form_sliders' ) );
        $f3->addField( 'css_border-radius', 'text', array(
             'name' => 'css_border-radius[]',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Radius' ),
            'labels' => array(
                 Mage::helper( 'dynamicslideshow' )->__( 'Top Left' ),
                Mage::helper( 'dynamicslideshow' )->__( 'Top Right' ),
                Mage::helper( 'dynamicslideshow' )->__( 'Bottom Right' ),
                Mage::helper( 'dynamicslideshow' )->__( 'Bottom Left' ) 
            ),
            'min' => 0,
            'max' => 150,
            'count' => 4 
        ) );
        $form->getElement( 'css_border-radius' )->setRenderer( $this->getLayout()->createBlock( 'dynamicslideshow/adminhtml_widget_form_four' ) );
        
        $form->setUseContainer( true );
        $this->setForm( $form );
        return parent::_prepareForm();
    }
    
    public function getVideoServices()
    {
        return array(
             array(
                 'value' => 'youtube',
                'label' => $this->helper( 'dynamicslideshow' )->__( 'Youtube' ) 
            ),
            array(
                 'value' => 'vimeo',
                'label' => $this->helper( 'dynamicslideshow' )->__( 'Vimeo' ) 
            ),
            array(
                 'value' => 'html5',
                'label' => $this->helper( 'dynamicslideshow' )->__( 'HTML5' ) 
            ) 
        );
    }
}