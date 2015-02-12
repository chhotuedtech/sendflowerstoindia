<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Block_Adminhtml_Sliders_Edit_Tab_Navigation extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $model    = Mage::registry( 'sliders' );
        $form     = new Varien_Data_Form();
        $fieldset = $form->addFieldset( 'navigation_fieldset', array(
             'legend' => Mage::helper( 'dynamicslideshow' )->__( 'Appearance Navigation' ) 
        ) );
        // Stop On Hover
        $fieldset->addField( 'pause', 'radios', array(
             'name' => 'pause',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Stop on Hover' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Stop on Hover' ),
            'value' => $model->getData( 'pause' ) ? $model->getData( 'pause' ) : $model->OPTION_ON,
            'values' => $model->getOptOnOff(),
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Stop the Timer when hovering the slider.' ) 
        ) );
        // Navigation Type
        $nav_type = $fieldset->addField( 'navigaion_type', 'select', array(
             'name' => 'navigaion_type',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Navigation Type' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Navigation Type' ),
            'values' => $model->getTypeNavigation(),
            'value' => $model->getData( 'navigaion_type' ) ? $model->getData( 'navigaion_type' ) : 'bullet',
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Display type of the navigation bar (Default: None)' ) 
        ) );
        
        // Navigation Align Horizontal
        $nav_align_hor = $fieldset->addField( 'navigaion_align_hor', 'select', array(
             'name' => 'navigaion_align_hor',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Navigation Horizontal Align' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Navigation Horizontal Align' ),
            'values' => $model->getOptLCR(),
            'value' => $model->getData( 'navigaion_align_hor' ) ? $model->getData( 'navigaion_align_hor' ) : 'center',
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Horizontal Align of Bullets / Thumbnails' ) 
        ) );
        
        // Navigation Align Vertical
        $nav_align_vert = $fieldset->addField( 'navigaion_align_vert', 'select', array(
             'name' => 'navigaion_align_vert',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Navigation Vertical Align' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Navigation Vertical Align' ),
            'values' => $model->getOptTCB(),
            'value' => $model->getData( 'navigaion_align_vert' ) ? $model->getData( 'navigaion_align_vert' ) : 'bottom',
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Vertical Align of Bullets / Thumbnails' ) 
        ) );
        
        // Navigation Horizontal Offset
        $nav_offset_hor = $fieldset->addField( 'navigaion_offset_hor', 'text', array(
             'name' => 'navigaion_offset_hor',
            'class' => 'validate-number',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Navigation Horizontal Offset' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Navigation Horizontal Offset' ),
            'value' => $model->getData( 'navigaion_offset_hor' ) ? $model->getData( 'navigaion_offset_hor' ) : 0,
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Offset from current Horizontal position of Bullets / Thumbnails negative and positive direction' ) 
        ) );
        
        // Navigation Vertical Offset
        $nav_offset_vert = $fieldset->addField( 'navigaion_offset_vert', 'text', array(
             'name' => 'navigaion_offset_vert',
            'class' => 'validate-number',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Navigation Vertical Offset' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Navigation Vertical Offset' ),
            'value' => $model->getData( 'navigaion_offset_vert' ) ? $model->getData( 'navigaion_offset_vert' ) : 20,
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Offset from current Vertical  position of Bullets / Thumbnails negative and positive direction' ) 
        ) );
		
		 // Navigation Style
        $fieldset->addField( 'navigation_style', 'select', array(
             'name' => 'navigation_style',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Navigation Style' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Navigation Style' ),
            'values' => $model->getOptStyleNav(),
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Look of the navigation bullets. If you choose navbar, we recommend to choose Navigation Arrows to With Bullets' ) 
        ) );
        
        // Navigation Arrows
        $nav_arrow = $fieldset->addField( 'navigation_arrows', 'select', array(
             'name' => 'navigation_arrows',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Navigation Arrows' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Navigation Arrows' ),
            'values' => $model->getOptNavArrows(),
            'value' => $model->getData( 'navigation_arrows' ) ? $model->getData( 'navigation_arrows' ) : 'solo',
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Display position of the Navigation Arrows (by Navigation Type Thumb arrows always centered or none visible)' ) 
        ) );
        
        // Left Arrow Horizontal Align
        $lft_align_hor = $fieldset->addField( 'leftarrow_align_hor', 'select', array(
             'name' => 'leftarrow_align_hor',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Left Arrow Horizontal Align' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Left Arrow Horizontal Align' ),
            'values' => $model->getOptLCR(),
            'value' => $model->getData( 'leftarrow_align_hor' ) ? $model->getData( 'leftarrow_align_hor' ) : 'left',
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Vertical Align of left Arrow (only if arrow is not next to bullets)' ) 
        ) );
        
        // Left Arrow Vertical Align
        $lft_align_vert = $fieldset->addField( 'leftarrow_align_vert', 'select', array(
             'name' => 'leftarrow_align_vert',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Left Arrow Vertical Align' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Left Arrow Vertical Align' ),
            'values' => $model->getOptTCB(),
            'value' => $model->getData( 'leftarrow_align_vert' ) ? $model->getData( 'leftarrow_align_vert' ) : 'center',
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Vertical Align of left Arrow (only if arrow is not next to bullets)' ) 
        ) );
        
        // Left Arrow Horizontal Offset
        $lft_offset_hor = $fieldset->addField( 'leftarrow_offset_hor', 'text', array(
             'name' => 'leftarrow_offset_hor',
            'class' => 'validate-number',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Left Arrow Horizontal Offset' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Left Arrow Horizontal Offset' ),
            'value' => $model->getData( 'leftarrow_offset_hor' ) ? $model->getData( 'leftarrow_offset_hor' ) : 20,
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Offset from current Horizontal position of Bullets / Thumbnails negative and positive direction' ) 
        ) );
        
        // Left Arrow Vertical Offset
        $lft_offset_vert = $fieldset->addField( 'leftarrow_offset_vert', 'text', array(
             'name' => 'leftarrow_offset_vert',
            'class' => 'validate-number',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Left Arrow Vertical Offset' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Left Arrow Vertical Offset' ),
            'value' => $model->getData( 'leftarrow_offset_vert' ) ? $model->getData( 'leftarrow_offset_vert' ) : 0,
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Offset from current Vertical  position of Bullets / Thumbnails negative and positive direction' ) 
        ) );
        
        // Right Arrow Horizontal Align
        $rgt_align_hor = $fieldset->addField( 'rightarrow_align_hor', 'select', array(
             'name' => 'rightarrow_align_hor',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Right Arrow Horizontal Align' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Right Arrow Horizontal Align' ),
            'values' => $model->getOptLCR(),
            'value' => $model->getData( 'rightarrow_align_hor' ) ? $model->getData( 'rightarrow_align_hor' ) : 'right',
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Vertical Align of right Arrow (only if arrow is not next to bullets)' ) 
        ) );
        
        // Right Arrow Horizontal Align
        $rgt_align_vert = $fieldset->addField( 'rightarrow_align_vert', 'select', array(
             'name' => 'rightarrow_align_vert',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Right Arrow Vertical Align' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Right Arrow Vertical Align' ),
            'values' => $model->getOptTCB(),
            'value' => $model->getData( 'rightarrow_align_vert' ) ? $model->getData( 'rightarrow_align_vert' ) : 'center',
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Vertical Align of right Arrow (only if arrow is not next to bullets)' ) 
        ) );
        
        // Right Arrow Horizontal Offset
        $rgt_offset_hor = $fieldset->addField( 'rightarrow_offset_hor', 'text', array(
             'name' => 'rightarrow_offset_hor',
            'class' => 'validate-number',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Right Arrow Horizontal Offset' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Right Arrow Horizontal Offset' ),
            'value' => $model->getData( 'rightarrow_offset_hor' ) ? $model->getData( 'rightarrow_offset_hor' ) : 20,
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Offset from current Horizontal position of of right Arrow negative and positive direction' ) 
        ) );
        
        // Right Arrow Vertical Offset
        $rgt_offset_vert = $fieldset->addField( 'rightarrow_offset_vert', 'text', array(
             'name' => 'rightarrow_offset_vert',
            'class' => 'validate-number',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Right Arrow Vertical Offset' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Right Arrow Vertical Offset' ),
            'value' => $model->getData( 'rightarrow_offset_vert' ) ? $model->getData( 'rightarrow_offset_vert' ) : 0,
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Offset from current Vertical position of of right Arrow negative and positive direction' ) 
        ) );
        
        
        // Always Show Navigation
        $nav_show = $fieldset->addField( 'navigaion_always_on', 'select', array(
             'name' => 'navigaion_always_on',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Always Show Navigation' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Always Show Navigation' ),
            'values' => $model->getOptYesNo(),
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Always show the navigation and the thumbnails' ) 
        ) );
        
        // Hide Navitagion After
        $hide_thumbs_after = $fieldset->addField( 'hide_thumbs', 'text', array(
             'name' => 'hide_thumbs',
            'class' => 'validate-number',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Hide Navitagion After' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Hide Navitagion After' ),
            'value' => $model->getData( 'hide_thumbs' ) ? $model->getData( 'hide_thumbs' ) : 200,
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Time after that the Navigation and the Thumbs will be hidden (Default: 200 ms)' ) 
        ) );
        
        $this->setForm( $form );
        if ( $model->getId() )
            $form->setValues( $model->getData() );
        
        $this->setChild( 'form_after', $this->getLayout()->createBlock( 'adminhtml/widget_form_element_dependence' )->addFieldMap( $nav_type->getHtmlId(), $nav_type->getName() )->addFieldMap( $nav_align_hor->getHtmlId(), $nav_align_hor->getName() )->addFieldMap( $nav_align_vert->getHtmlId(), $nav_align_vert->getName() )->addFieldMap( $nav_offset_hor->getHtmlId(), $nav_offset_hor->getName() )->addFieldMap( $nav_offset_vert->getHtmlId(), $nav_offset_vert->getName() )->addFieldMap( $nav_arrow->getHtmlId(), $nav_arrow->getName() )->addFieldMap( $lft_align_hor->getHtmlId(), $lft_align_hor->getName() )->addFieldMap( $lft_align_vert->getHtmlId(), $lft_align_vert->getName() )->addFieldMap( $lft_offset_hor->getHtmlId(), $lft_offset_hor->getName() )->addFieldMap( $lft_offset_vert->getHtmlId(), $lft_offset_vert->getName() )->addFieldMap( $rgt_align_hor->getHtmlId(), $rgt_align_hor->getName() )->addFieldMap( $rgt_align_vert->getHtmlId(), $rgt_align_vert->getName() )->addFieldMap( $rgt_offset_hor->getHtmlId(), $rgt_offset_hor->getName() )->addFieldMap( $rgt_offset_vert->getHtmlId(), $rgt_offset_vert->getName() )->addFieldMap( $nav_show->getHtmlId(), $nav_show->getName() )->addFieldMap( $hide_thumbs_after->getHtmlId(), $hide_thumbs_after->getName() )->addFieldDependence( $nav_align_hor->getName(), $nav_type->getName(), array(
             'bullet',
            'thumb',
            'both' 
        ) )->addFieldDependence( $nav_align_vert->getName(), $nav_type->getName(), array(
             'bullet',
            'thumb',
            'both' 
        ) )->addFieldDependence( $nav_offset_hor->getName(), $nav_type->getName(), array(
             'bullet',
            'thumb',
            'both' 
        ) )->addFieldDependence( $nav_offset_vert->getName(), $nav_type->getName(), array(
             'bullet',
            'thumb',
            'both' 
        ) )->addFieldDependence( $lft_align_hor->getName(), $nav_arrow->getName(), array(
             'nexttobullets',
            'solo' 
        ) )->addFieldDependence( $lft_align_vert->getName(), $nav_arrow->getName(), array(
             'nexttobullets',
            'solo' 
        ) )->addFieldDependence( $lft_offset_hor->getName(), $nav_arrow->getName(), array(
             'nexttobullets',
            'solo' 
        ) )->addFieldDependence( $lft_offset_vert->getName(), $nav_arrow->getName(), array(
             'nexttobullets',
            'solo' 
        ) )->addFieldDependence( $rgt_align_hor->getName(), $nav_arrow->getName(), array(
             'nexttobullets',
            'solo' 
        ) )->addFieldDependence( $rgt_align_vert->getName(), $nav_arrow->getName(), array(
             'nexttobullets',
            'solo' 
        ) )->addFieldDependence( $rgt_offset_hor->getName(), $nav_arrow->getName(), array(
             'nexttobullets',
            'solo' 
        ) )->addFieldDependence( $rgt_offset_vert->getName(), $nav_arrow->getName(), array(
             'nexttobullets',
            'solo' 
        ) )->addFieldDependence( $hide_thumbs_after->getName(), $nav_show->getName(), 'no' ) );
        return parent::_prepareForm();
    }
}