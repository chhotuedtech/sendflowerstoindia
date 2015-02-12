<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Block_Adminhtml_Sliders_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId( 'smdnsl_sliders_tabs' );
        $this->setDestElementId( 'smdnsl_sliders_form' );
        if ( $tab = $this->getRequest()->getParam( 'activeTab' ) )
        {
            $this->_activeTab = $tab;
        }
        else
        {
            $this->_activeTab = 'general_section';
        }
        $this->setTitle( Mage::helper( 'dynamicslideshow' )->__( 'Dynamic SlideShow' ) );
    }
    
    protected function _beforeToHtml()
    {
        // Add General Section
        $this->addTab( 'general_section', array(
             'label' => "<i class='fa fa-gears'></i>" . Mage::helper( 'dynamicslideshow' )->__( 'General' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'General' ),
            'content' => $this->_getTabHtml( 'general' ) 
        ) );
        
        // Add Position Section 
        $this->addTab( 'position_section', array(
             'label' => "<i class='fa fa-arrows-alt'></i>" . Mage::helper( 'dynamicslideshow' )->__( 'Position' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Position' ),
            'content' => $this->_getTabHtml( 'position' ) 
        ) );
        
        $this->addTab( 'appearance_section', array(
             'label' => "<i class='fa fa-tint'></i>" . Mage::helper( 'dynamicslideshow' )->__( 'Appearance' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Appearance' ),
            'content' => $this->_getTabHtml( 'appearance' ) 
        ) );
        
        $this->addTab( 'navigation_section', array(
             'label' => "<i class='fa fa-flickr'></i>" . Mage::helper( 'dynamicslideshow' )->__( 'Navigation' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Navigation' ),
            'content' => $this->_getTabHtml( 'navigation' ) 
        ) );
        
        $this->addTab( 'thumbnails_section', array(
             'label' => "<i class='fa fa-th-large'></i>" . Mage::helper( 'dynamicslideshow' )->__( 'Thumbnails' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Thumbnails' ),
            'content' => $this->_getTabHtml( 'thumbnails' ) 
        ) );
        
        $this->addTab( 'touch_section', array(
             'label' => "<i class='fa fa-hand-o-up'></i>" . Mage::helper( 'dynamicslideshow' )->__( 'Touch' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Touch' ),
            'content' => $this->_getTabHtml( 'Touch' ) 
        ) );
        
        $this->addTab( 'mobile_section', array(
             'label' => "<i class='fa fa-mobile-phone'></i>" . Mage::helper( 'dynamicslideshow' )->__( 'Mobile' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Mobile' ),
            'content' => $this->_getTabHtml( 'mobile' ) 
        ) );
        
        $this->addTab( 'parallax_section', array(
             'label' => "<i class='fa fa-soundcloud'></i>" . Mage::helper( 'dynamicslideshow' )->__( 'Parallax' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Parallax' ),
            'content' => $this->_getTabHtml( 'parallax' ) 
        ) );
        
        $this->addTab( 'first_section', array(
             'label' => "<i class='fa fa-sign-in'></i>" . Mage::helper( 'dynamicslideshow' )->__( 'First' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'First' ),
            'content' => $this->_getTabHtml( 'first' ) 
        ) );
        
        $this->addTab( 'slides_section', array(
             'label' => "<i class='fa fa-sliders'></i>" . Mage::helper( 'dynamicslideshow' )->__( 'Slides' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Slides' ),
            'url' => $this->getUrl( '*/*/slides', array(
                 '_current' => true 
            ) ),
            'class' => 'ajax' 
        ) );
        return parent::_beforeToHtml();
    }
    
    protected function _getTabHtml( $tab )
    {
        return $this->getLayout()->createBlock( 'dynamicslideshow/adminhtml_sliders_edit_tab_' . $tab )->toHtml();
    }
}