<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Block_Adminhtml_Sliders_Edit_Tab_Slides extends Mage_Adminhtml_Block_Widget_Grid
{
    protected $_sliders = null;
    public function __construct()
    {
        parent::__construct();
        $this->setId( 'slides_grid' );
        $this->setDefaultSort( 'slide_order' );
        $this->setDefaultDir( 'ASC' );
        $this->setSaveParametersInSession( true );
        $this->setUseAjax( true );
    }
    
    protected function _getSlides()
    {
        if ( !$this->_sliders )
        {
            $sliders = Mage::getModel( 'dynamicslideshow/sliders' );
            $id      = $this->getRequest()->getParam( 'id', null );
            if ( is_numeric( $id ) )
            {
                $sliders->load( $id );
            }
            $this->_sliders = $sliders;
        }
        return $this->_sliders;
    }
    
    protected function _prepareCollection()
    {
        $slides     = $this->_getSlides();
        $collection = Mage::getModel( 'dynamicslideshow/slides' )->getCollection()->addSlidersFilter( $slides && $slides->getId() ? $slides : 0 );
        $this->setCollection( $collection );
        return parent::_prepareCollection();
    }
    
    protected function _prepareColumns()
    {
        $this->addColumn( 'slides_id', array(
             'header' => Mage::helper( 'dynamicslideshow' )->__( 'ID' ),
            'index' => 'id',
            'filter' => false,
            'sortable' => false,
            'width' => '100px' 
        ) );
        
        $this->addColumn( 'slides_title', array(
             'header' => Mage::helper( 'dynamicslideshow' )->__( 'Title' ),
            'filter' => false,
            'sortable' => false,
            'renderer' => 'dynamicslideshow/adminhtml_widget_grid_column_renderer_slides_title' 
        ) );
        
        $this->addColumn( 'slides_thumb', array(
             'header' => Mage::helper( 'dynamicslideshow' )->__( 'Thumb' ),
            'filter' => false,
            'sortable' => false,
            'width' => '400px',
            'renderer' => 'dynamicslideshow/adminhtml_widget_grid_column_renderer_slides_thumb' 
        ) );
        
        
        
        $this->addColumn( 'slide_order', array(
             'header' => Mage::helper( 'dynamicslideshow' )->__( 'Order' ),
            'index' => 'slide_order',
            'width' => '200px',
            'filter' => false,
            'renderer' => 'dynamicslideshow/adminhtml_widget_grid_column_renderer_slides_order' 
        ) );
        
        $this->addColumn( 'edit', array(
             'header' => Mage::helper( 'dynamicslideshow' )->__( 'Action' ),
            'type' => 'action',
            'getter' => 'getId',
            'width' => '80px',
            'actions' => array(
                 array(
                     'caption' => Mage::helper( 'dynamicslideshow' )->__( 'Edit' ),
                    'field' => 'id',
                    'class' => 'scalable ',
                    'url' => array(
                         'base' => '*/*/addSlides',
                        'params' => array(
                             'sid' => $this->_sliders->getId() 
                        ) 
                    ) 
                ) 
            ),
            'filter' => false,
            'sortable' => false 
        ) );
        $this->addColumn( 'delete', array(
             'header' => Mage::helper( 'dynamicslideshow' )->__( 'Delete' ),
            'type' => 'action',
            'getter' => 'getId',
            'width' => '80px',
            'actions' => array(
                 array(
                     'caption' => Mage::helper( 'dynamicslideshow' )->__( 'Delete' ),
                    'field' => 'id',
                    'confirm' => Mage::helper( 'dynamicslideshow' )->__( 'Do you realy want to delete this slide?' ),
                    'url' => array(
                         'base' => '*/*/deleteSlide',
                        'params' => array(
                             'sid' => $this->_sliders->getId(),
                            'activeTab' => 'slides_section' 
                        ) 
                    ) 
                ) 
            ),
            'filter' => false,
            'sortable' => false 
        ) );
    }
    
    public function getGridUrl()
    {
        return $this->getUrl( '*/*/slidesGrid', array(
             '_current' => true 
        ) );
    }
    
    public function getRowUrl( $row )
    {
        return '';
    }
    
    protected function _prepareLayout()
    {
        $slides = $this->_getSlides();
        if ( $slides && $slides->getId() )
        {
            $url = $this->getUrl( '*/*/addSlides', array(
                 'sid' => $slides->getId() 
            ) );
            $this->setChild( 'addSlidesButton', $this->getLayout()->createBlock( 'adminhtml/widget_button' )->setData( array(
                 'label' => Mage::helper( 'dynamicslideshow' )->__( 'Add Slide' ),
                'onclick' => "setLocation('$url')",
                'class' => 'scale add',
                'id' => 'add_slides' 
            ) ) );
        }
        return parent::_prepareLayout();
    }
    
    public function getAddSlideButtonHtml()
    {
        return $this->getChildHtml( 'addSlidesButton' );
    }
    
    public function getMainButtonsHtml()
    {
        $buttons = $this->getAddSlideButtonHtml();
        return $buttons;
    }
}