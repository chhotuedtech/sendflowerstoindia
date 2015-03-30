<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Block_Adminhtml_Slides_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId( 'smdnsl_slides_tabs' );
        $this->setDestElementId( 'smdnsl_slides_form' );
        $sliders = Mage::registry( 'sliders' );
        if ( $sliders->getId() )
        {
            $this->setTitle( Mage::helper( 'dynamicslideshow' )->__( '%s\'s Slides', $sliders->getTitle() ) );
        }
        else
        {
            $this->setTitle( Mage::helper( 'dynamicslideshow' )->__( 'Sm Dynamic Slideshow' ) );
        }
    }
    
    public function _prepareLayout()
    {
        $sliders = Mage::registry( 'sliders' );
        $slides  = Mage::registry( 'slides' );
        $_slides = $sliders->getAllSlides();
        foreach ( $_slides as $item )
        {
            if ( $item->getId() == $slides->getId() )
            {
                $this->addTab( 'slides_section_' . $item->getId(), array(
                     'title' => $item->getTitle() ? $item->getTitle() : "ID: {$item->getId()}",
                    'label' => $item->getTitle() ? "<i class='fa fa-sliders'></i>" . $item->getTitle() : "<i class='fa fa-photo'></i>" . "ID: {$item->getId()}",
                    'content' => $this->getLayout()->createBlock( 'dynamicslideshow/adminhtml_slides_edit_tab_main' )->toHtml() 
                ) );
                $this->_activeTab = 'slides_section_' . $item->getId();
            }
            else
            {
                $this->addTab( 'slides_section_' . $item->getId(), array(
                     'title' => $item->getTitle() ? $item->getTitle() : "ID: {$item->getId()}",
                    'label' => $item->getTitle() ? "<i class='fa fa-sliders'></i>" . $item->getTitle() : "<i class='fa fa-photo'></i>" . "ID: {$item->getId()}",
                    'url' => $this->getUrl( '*/*/addSlides', array(
                         'sid' => $sliders->getId(),
                        'id' => $item->getId() 
                    ) ) 
                ) );
            }
        }
        if ( !$slides->getId() )
        {
            $this->addTab( 'slides_section_new', array(
                 'title' => Mage::helper( 'dynamicslideshow' )->__( 'New Slide' ),
                'label' => "<i class='fa fa-plus'></i>" . Mage::helper( 'dynamicslideshow' )->__( 'New Slide' ),
                'content' => $this->getLayout()->createBlock( 'dynamicslideshow/adminhtml_slides_edit_tab_main' )->toHtml() 
            ) );
            $this->_activeTab = 'slides_section_new';
        }
        else
        {
            $this->addTab( 'slides_section_new', array(
                 'title' => Mage::helper( 'dynamicslideshow' )->__( 'New Slide' ),
                'label' => "<i class='fa fa-plus'></i>" . Mage::helper( 'dynamicslideshow' )->__( 'New Slide' ),
                'url' => $this->getUrl( '*/*/addSlides', array(
                     'sid' => $sliders->getId() 
                ) ) 
            ) );
        }
        
        return parent::_prepareLayout();
    }
}