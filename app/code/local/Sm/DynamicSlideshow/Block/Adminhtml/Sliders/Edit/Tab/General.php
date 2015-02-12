<?php

/**

 * @package SM Dynamic Slideshow 

 * @version 2.0.0

 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.

 * @author YouTech Company http://www.magentech.com

 */



class Sm_DynamicSlideshow_Block_Adminhtml_Sliders_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form

{

    public function _prepareForm()

    {

        $model    = Mage::registry( 'sliders' );

        $form     = new Varien_Data_Form();

        $fieldset = $form->addFieldset( 'general_fieldset', array(

             'legend' => Mage::helper( 'dynamicslideshow' )->__( 'General Options' ),

            'class' => 'collapsible' 

        ) );

        

        if ( is_object( $model ) && $model->getId() )

        {

            $fieldset->addField( 'sliderid', 'hidden', array(

                 'name' => 'sliderid' 

            ) );

        }

        

        $heading0 = $fieldset->addField( 'heading0', 'label', array(

             'name' => 'heading0',

            'content' => "<i class='fa fa-info-circle'></i>" . Mage::helper( 'dynamicslideshow' )->__( 'Information' ) 

        ) );

        

        $form->getElement( 'heading0' )->setRenderer( $this->getLayout()->createBlock( 'dynamicslideshow/adminhtml_widget_form_heading' ) );

        

        $fieldset->addField( 'title', 'text', array(

             'label' => Mage::helper( 'dynamicslideshow' )->__( 'Title' ),

            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Title' ),

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

        

        $fieldset->addField( 'date_from', 'date', array(

             'name' => 'date_from',

            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Visible From' ),

            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Visible From' ),

            'note' => Mage::helper( 'dynamicslideshow' )->__( 'If set, slider will be visible after the date is reached' ),

            'image' => $this->getSkinUrl( 'images/grid-cal.gif' ),

            'format' => Mage::app()->getLocale()->getDateFormat( Mage_Core_Model_Locale::FORMAT_TYPE_SHORT ) 

        ) );

        

        $fieldset->addField( 'date_to', 'date', array(

             'name' => 'date_to',

            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Visible Until' ),

            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Visible Until' ),

            'note' => Mage::helper( 'dynamicslideshow' )->__( 'If set, slider will be visible till the date is reached' ),

            'image' => $this->getSkinUrl( 'images/grid-cal.gif' ),

            'format' => Mage::app()->getLocale()->getDateFormat( Mage_Core_Model_Locale::FORMAT_TYPE_SHORT ) 

        ) );

        

        $header1 = $fieldset->addField( 'header1', 'label', array(

             'name' => 'header1',

            'content' => "<i class='fa fa-cog'></i>" . Mage::helper( 'dynamicslideshow' )->__( 'General Settings' ) 

        ) );

        

        $form->getElement( 'header1' )->setRenderer( $this->getLayout()->createBlock( 'dynamicslideshow/adminhtml_widget_form_heading' ) );

        

        $type = $fieldset->addField( 'type', 'select', array(

             'name' => 'type',

            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Slider Type' ),

            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Slider Type' ),

            'values' => $model->getTypeSlider() 

        ) );

        

        $fieldset->addField( 'width', 'text', array(

             'label' => Mage::helper( 'dynamicslideshow' )->__( 'Slide Width' ),

            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Slide Width' ),

            'name' => 'width',

            'value' => (int) $model->getData( 'width' ) ? (int) $model->getData( 'width' ) : 960 

        ) );

        

        $fieldset->addField( 'height', 'text', array(

             'label' => Mage::helper( 'dynamicslideshow' )->__( 'Slide Height' ),

            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Slide Height' ),

            'name' => 'height',

            'value' => (int) $model->getData( 'height' ) ? (int) $model->getData( 'height' ) : 350 

        ) );

        

        // For type Custom

        $type_custom0 = $fieldset->addField( 'responsitive_w1', 'text', array(

             'name' => 'responsitive_w1',

            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Screen Width 1' ),

            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Screen Width 1' ),

            'class' => 'validate-number',

            'value' => $model->getData( 'responsitive_w1' ) ? $model->getData( 'responsitive_w1' ) : 940 

        ) );

        

        $type_custom1 = $fieldset->addField( 'responsitive_sw1', 'text', array(

             'name' => 'responsitive_sw1',

            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Slider Width 1' ),

            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Slider Width 1' ),

            'class' => 'validate-number',

            'value' => $model->getData( 'responsitive_sw1' ) ? $model->getData( 'responsitive_sw1' ) : 770 

        ) );

        

        $type_custom2 = $fieldset->addField( 'responsitive_w2', 'text', array(

             'name' => 'responsitive_w2',

            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Screen Width 2' ),

            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Screen Width 2' ),

            'class' => 'validate-number',

            'value' => $model->getData( 'responsitive_w2' ) ? $model->getData( 'responsitive_w2' ) : 780 

        ) );

        

        $type_custom3 = $fieldset->addField( 'responsitive_sw2', 'text', array(

             'name' => 'responsitive_sw2',

            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Slider Width 2' ),

            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Slider Width 2' ),

            'class' => 'validate-number',

            'value' => $model->getData( 'responsitive_sw2' ) ? $model->getData( 'responsitive_sw2' ) : 500 

        ) );

        

        $type_custom4 = $fieldset->addField( 'responsitive_w3', 'text', array(

             'name' => 'responsitive_w3',

            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Screen Width 3' ),

            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Screen Width 3' ),

            'class' => 'validate-number',

            'value' => $model->getData( 'responsitive_w3' ) ? $model->getData( 'responsitive_w3' ) : 510 

        ) );

        

        $type_custom5 = $fieldset->addField( 'responsitive_sw3', 'text', array(

             'name' => 'responsitive_sw3',

            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Slider Width 3' ),

            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Slider Width 3' ),

            'class' => 'validate-number',

            'value' => $model->getData( 'responsitive_sw3' ) ? $model->getData( 'responsitive_sw3' ) : 310 

        ) );

        

        

        $type_custom6 = $fieldset->addField( 'responsitive_w4', 'text', array(

             'name' => 'responsitive_w4',

            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Screen Width 4' ),

            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Screen Width 4' ),

            'class' => 'validate-number',

            'value' => $model->getData( 'responsitive_w4' ) ? $model->getData( 'responsitive_w4' ) : 0 

        ) );

        

        $type_custom7 = $fieldset->addField( 'responsitive_sw4', 'text', array(

             'name' => 'responsitive_sw4',

            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Slider Width 4' ),

            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Slider Width 4' ),

            'class' => 'validate-number',

            'value' => $model->getData( 'responsitive_sw4' ) ? $model->getData( 'responsitive_sw4' ) : 0 

        ) );

        

        

        $type_custom8 = $fieldset->addField( 'responsitive_w5', 'text', array(

             'name' => 'responsitive_w5',

            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Screen Width 5' ),

            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Screen Width 5' ),

            'class' => 'validate-number',

            'value' => $model->getData( 'responsitive_w5' ) ? $model->getData( 'responsitive_w5' ) : 0 

        ) );

        

        $type_custom9 = $fieldset->addField( 'responsitive_sw5', 'text', array(

             'name' => 'responsitive_sw5',

            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Slider Width 5' ),

            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Slider Width 5' ),

            'class' => 'validate-number',

            'value' => $model->getData( 'responsitive_sw5' ) ? $model->getData( 'responsitive_sw5' ) : 0 

        ) );

        

        $type_custom10 = $fieldset->addField( 'responsitive_w6', 'text', array(

             'name' => 'responsitive_w6',

            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Screen Width 6' ),

            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Screen Width 6' ),

            'class' => 'validate-number',

            'value' => $model->getData( 'responsitive_w6' ) ? $model->getData( 'responsitive_w6' ) : 0 

        ) );

        

        $type_custom11 = $fieldset->addField( 'responsitive_sw6', 'text', array(

             'name' => 'responsitive_sw6',

            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Slider Width 6' ),

            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Slider Width 6' ),

            'class' => 'validate-number',

            'value' => $model->getData( 'responsitive_sw6' ) ? $model->getData( 'responsitive_sw6' ) : 0 

        ) );

        

        

        $type_full0 = $fieldset->addField( 'fullscreen_offset_container', 'text', array(

             'name' => 'fullscreen_offset_container',

            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Offset Container' ),

            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Offset Container' ),

            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Example: #header or .header, .footer, #somecontainer | The height of fullscreen slider will be decreased with the height of these Containers to fit perfect in the screen' ) 

        ) );

        

        $type_full1 = $fieldset->addField( 'fullscreen_min_height', 'text', array(

             'name' => 'fullscreen_min_height',

            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Min Height' ),

            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Min Height' ),

            'class' => 'validate-number' 

        ) );

        

        

        

        $type_auto0 = $fieldset->addField( 'auto_height', 'radios', array(

             'name' => 'auto_height',

            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Unlimited Height' ),

            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Unlimited Height' ),

            'value' => $model->getData( 'auto_height' ) ? $model->getData( 'auto_height' ) : $model->OPTION_ON,

            'values' => $model->getOptOnOff() 

        ) );

        

        $type_auto1 = $fieldset->addField( 'force_full_width', 'radios', array(

             'name' => 'force_full_width',

            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Force Full Width' ),

            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Force Full Width' ),

            'value' => $model->getData( 'force_full_width' ) ? $model->getData( 'force_full_width' ) : $model->OPTION_ON,

            'values' => $model->getOptOnOff() 

        ) );

        

        $type_full2 = $fieldset->addField( 'full_screen_align_force', 'radios', array(

             'name' => 'full_screen_align_force',

            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Align' ),

            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Align' ),

            'value' => $model->getData( 'full_screen_align_force' ) ? $model->getData( 'full_screen_align_force' ) : $model->OPTION_ON,

            'values' => $model->getOptOnOff() 

        ) );

        

        $header2 = $fieldset->addField( 'header2', 'label', array(

             'name' => 'header2',

            'content' => "<i class='fa fa-wrench'></i>" . Mage::helper( 'dynamicslideshow' )->__( 'Slider Settings' ) 

        ) );

        

        $form->getElement( 'header2' )->setRenderer( $this->getLayout()->createBlock( 'dynamicslideshow/adminhtml_widget_form_heading' ) );

        

        $fieldset->addField( 'delay', 'text', array(

             'name' => 'delay',

            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Delay' ),

            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Delay' ),

            'class' => 'validate-number',

            'note' => Mage::helper( 'dynamicslideshow' )->__( 'The time one slide stays on the screen in Milliseconds' ),

            'value' => $model->getData( 'delay' ) ? $model->getData( 'delay' ) : 9000 

        ) );

        

        $fieldset->addField( 'shuffle', 'radios', array(

             'name' => 'shuffle',

            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Shuffle Mode' ),

            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Shuffle Mode' ),

            'value' => $model->getData( 'shuffle' ) ? $model->getData( 'shuffle' ) : $model->OPTION_OFF,

            'values' => $model->getOptOnOff(),

            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Turn Shuffle Mode on and off! Will be randomized only once at the start' ) 

        ) );

        

        $fieldset->addField( 'lazy_load', 'radios', array(

             'name' => 'lazy_load',

            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Lazy Load' ),

            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Lazy Load' ),

            'value' => $model->getData( 'lazy_load' ) ? $model->getData( 'lazy_load' ) : $model->OPTION_ON,

            'values' => $model->getOptOnOff(),

            'note' => Mage::helper( 'dynamicslideshow' )->__( 'The lazy load means that the images will be loaded by demand, it speeds the loading of the slider' ) 

        ) );

        

        $stop0 = $fieldset->addField( 'stop_slider', 'select', array(

             'name' => 'stop_slider',

            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Stop Slider' ),

            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Stop Slider' ),

            'values' => $model->getOptYesNo(),

            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Yes / No to stop slider after some amount of loops / slides' ) 

        ) );

        

        $stop1 = $fieldset->addField( 'stop_after_loops', 'text', array(

             'name' => 'stop_after_loops',

            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Stop After Loops' ),

            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Stop After Loops' ),

            'class' => 'validate-number',

            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Stop the slider after certain amount of loops. 0 related to the first loop' ),

            'value' => $model->getData( 'stop_after_loops' ) ? $model->getData( 'stop_after_loops' ) : 0 

        ) );

        $stop2 = $fieldset->addField( 'stop_at_slide', 'text', array(

             'name' => 'stop_at_slide',

            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Stop At Slide' ),

            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Stop At Slide' ),

            'class' => 'validate-number',

            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Stop the slider at the given slide. Default: 2' ),

            'value' => $model->getData( 'stop_at_slide' ) ? $model->getData( 'stop_at_slide' ) : 2 

        ) );

        

        

        

        $header3 = $fieldset->addField( 'header3', 'label', array(

             'name' => 'header3',

            'content' => "<i class='fa fa-font'></i>" . Mage::helper( 'dynamicslideshow' )->__( 'Font Settings' ) 

        ) );

        

        $form->getElement( 'header3' )->setRenderer( $this->getLayout()->createBlock( 'dynamicslideshow/adminhtml_widget_form_heading' ) );

        

        $load_googlefont = $fieldset->addField( 'load_googlefont', 'select', array(

            'name' => 'load_googlefont',

            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Load Google Font' ),

            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Load Google Font' ),

            'values' => $model->getOptYesNo(),

            'value' => $model->OPTION_NO,

            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Yes / No to load google font' ) 

        ) );

        

        $load_googlefont1 = $fieldset->addField( 'google_font', 'text', array(

             'name' => 'google_font',

            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Google Font' ),

            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Ex: Open+Sans:400,300,700&subset=latin,vietnamese.' ) 

        ) );

        $form->getElement( 'google_font' )->setRenderer( $this->getLayout()->createBlock( 'dynamicslideshow/adminhtml_widget_form_font' ) );

        

		$header4 = $fieldset->addField( 'header4', 'label', array(

             'name' => 'header4',

            'content' => "<i class='fa fa-rocket'></i>" . Mage::helper( 'dynamicslideshow' )->__( 'Include jQuery' ) 

        ) );

        

        $form->getElement( 'header4' )->setRenderer( $this->getLayout()->createBlock( 'dynamicslideshow/adminhtml_widget_form_heading' ) );

		

		$fieldset->addField( 'include_jquery', 'select', array(

            'name' => 'include_jquery',

            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Include jQuery' ),

            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Include jQuery' ),

            'values' => $model->getOptYesNo(),

        ) );

		

        $this->setForm( $form );

        if ( $model->getId() )

            $form->setValues( $model->getData() );

        $this->setChild( 'form_after', $this->getLayout()->createBlock( 'adminhtml/widget_form_element_dependence' )->addFieldMap( $type->getHtmlId(), $type->getName() )->addFieldMap( $type_custom0->getHtmlId(), $type_custom0->getName() )->addFieldMap( $type_custom1->getHtmlId(), $type_custom1->getName() )->addFieldMap( $type_custom2->getHtmlId(), $type_custom2->getName() )->addFieldMap( $type_custom3->getHtmlId(), $type_custom3->getName() )->addFieldMap( $type_custom4->getHtmlId(), $type_custom4->getName() )->addFieldMap( $type_custom5->getHtmlId(), $type_custom5->getName() )->addFieldMap( $type_custom6->getHtmlId(), $type_custom6->getName() )->addFieldMap( $type_custom7->getHtmlId(), $type_custom7->getName() )->addFieldMap( $type_custom8->getHtmlId(), $type_custom8->getName() )->addFieldMap( $type_custom9->getHtmlId(), $type_custom9->getName() )->addFieldMap( $type_custom10->getHtmlId(), $type_custom10->getName() )->addFieldMap( $type_custom11->getHtmlId(), $type_custom11->getName() )->addFieldMap( $type_auto0->getHtmlId() . $model->OPTION_ON, $type_auto0->getName() )->addFieldMap( $type_auto0->getHtmlId() . $model->OPTION_OFF, $type_auto0->getName() )->addFieldMap( $type_auto1->getHtmlId() . $model->OPTION_ON, $type_auto1->getName() )->addFieldMap( $type_auto1->getHtmlId() . $model->OPTION_OFF, $type_auto1->getName() )->addFieldMap( $type_full0->getHtmlId(), $type_full0->getName() )->addFieldMap( $type_full1->getHtmlId(), $type_full1->getName() )->addFieldMap( $type_full2->getHtmlId() . $model->OPTION_ON, $type_full2->getName() )->addFieldMap( $type_full2->getHtmlId() . $model->OPTION_OFF, $type_full2->getName() )->addFieldMap( $stop0->getHtmlId(), $stop0->getName() )->addFieldMap( $stop1->getHtmlId(), $stop1->getName() )->addFieldMap( $stop2->getHtmlId(), $stop2->getName() )->addFieldMap( $load_googlefont->getHtmlId(), $load_googlefont->getName() )->addFieldMap( $load_googlefont1->getHtmlId(), $load_googlefont1->getName() )->addFieldDependence( $type_custom0->getName(), $type->getName(), 'responsive' )->addFieldDependence( $type_custom1->getName(), $type->getName(), 'responsive' )->addFieldDependence( $type_custom2->getName(), $type->getName(), 'responsive' )->addFieldDependence( $type_custom3->getName(), $type->getName(), 'responsive' )->addFieldDependence( $type_custom4->getName(), $type->getName(), 'responsive' )->addFieldDependence( $type_custom5->getName(), $type->getName(), 'responsive' )->addFieldDependence( $type_custom6->getName(), $type->getName(), 'responsive' )->addFieldDependence( $type_custom7->getName(), $type->getName(), 'responsive' )->addFieldDependence( $type_custom8->getName(), $type->getName(), 'responsive' )->addFieldDependence( $type_custom9->getName(), $type->getName(), 'responsive' )->addFieldDependence( $type_custom10->getName(), $type->getName(), 'responsive' )->addFieldDependence( $type_custom11->getName(), $type->getName(), 'responsive' )->addFieldDependence( $type_auto0->getName(), $type->getName(), 'fullwidth' )->addFieldDependence( $type_auto1->getName(), $type->getName(), array(

             'fullwidth',

            'fullscreen' 

        ) )->addFieldDependence( $type_full0->getName(), $type->getName(), 'fullscreen' )->addFieldDependence( $type_full1->getName(), $type->getName(), 'fullscreen' )->addFieldDependence( $type_full2->getName(), $type->getName(), 'fullscreen' )->addFieldDependence( $stop1->getName(), $stop0->getName(), 'yes' )->addFieldDependence( $stop2->getName(), $stop0->getName(), 'yes' )->addFieldDependence( $load_googlefont1->getName(), $load_googlefont->getName(), 'yes' ) );

        return parent::_prepareForm();

    }

}