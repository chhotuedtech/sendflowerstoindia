<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Block_Adminhtml_Widget_Form_Layers extends Mage_Adminhtml_Block_Widget implements Varien_Data_Form_Element_Renderer_Interface
{
    protected $_element;
    
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate( 'sm/dynamicslideshow/widget/form/layers.phtml' );
    }
    
    public function getElement()
    {
        return $this->_element;
    }
    
    public function setElement( Varien_Data_Form_Element_Abstract $element )
    {
        return $this->_element = $element;
    }
    
    public function render( Varien_Data_Form_Element_Abstract $element )
    {
        $this->setElement( $element );
        return $this->toHtml();
    }
    
    protected function _prepareLayout()
    {
        $addLayerBtn = $this->getLayout()->createBlock( 'adminhtml/widget_button', 'addLayerBtn', array(
             'type' => 'button',
            'label' => "<i class='fa fa-text-width'></i>" . Mage::helper( 'dynamicslideshow' )->__( 'Add Layer Text' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( ' Add Layer: Text' ),
            'onclick' => 'DnmSl.addLayerText()' 
        ) );
        $this->setChild( 'addLayerBtn', $addLayerBtn );
        
        $addLayerBtn = $this->getLayout()->createBlock( 'adminhtml/widget_button', 'addLayerImageBtn', array(
             'type' => 'button',
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Add Layer: Image' ),
            'label' => "<i class='fa fa-file-image-o'></i>" . Mage::helper( 'dynamicslideshow' )->__( 'Add Layer: Image' ),
            'onclick' => sprintf( '_MediabrowserUtility.openDialog(\'%s\', \'addLayerImageWindow\', null, null, \'%s\')', Mage::getSingleton( 'adminhtml/url' )->getUrl( 'adminhtml/cms_wysiwyg_images/index', array(
                 'static_urls_allowed' => 1,
                'onInsertCallback' => 'DnmSl.addLayerImage' 
            ) ), Mage::helper( 'dynamicslideshow' )->__( 'Add Image' ) ) 
        ) );
        $this->setChild( 'addLayerImageBtn', $addLayerBtn );
        
        $addLayerBtn = $this->getLayout()->createBlock( 'adminhtml/widget_button', 'addLayerVideoBtn', array(
             'type' => 'button',
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Add Layer: Video' ),
            'label' => "<i class='fa fa-video-camera'></i>" . Mage::helper( 'dynamicslideshow' )->__( 'Add Layer: Video' ),
            'onclick' => sprintf( '_MediabrowserUtility.openDialog(\'%s\', \'addLayerVideoWindow\', null, 700, \'%s\')', Mage::getSingleton( 'adminhtml/url' )->getUrl( 'dynamicslideshow/adminhtml_dynamicslideshow/video' ), Mage::helper( 'dynamicslideshow' )->__( 'Add Video' ) ) 
        ) );
        $this->setChild( 'addLayerVideoBtn', $addLayerBtn );
        
        $addLayerBtn = $this->getLayout()->createBlock( 'adminhtml/widget_button', 'deleteLayerBtn', array(
             'title' => Mage::helper( 'dynamicslideshow' )->__( 'Delete Layer' ),
            'label' => "<i class='fa fa-times-circle'></i>" . Mage::helper( 'dynamicslideshow' )->__( 'Delete Layer' ),
            'onclick' => 'DnmSl.deleteLayer()',
            'type' => 'button',
            'id' => 'deleteLayerBtn' 
        ) );
        $this->setChild( 'deleteLayerBtn', $addLayerBtn );
        
        $addLayerBtn = $this->getLayout()->createBlock( 'adminhtml/widget_button', 'deleteAllLayersBtn', array(
             'title' => Mage::helper( 'dynamicslideshow' )->__( 'Delete All Layers' ),
            'label' => "<i class='fa fa-times-circle'></i>" . Mage::helper( 'dynamicslideshow' )->__( 'Delete All Layers' ),
            'onclick' => 'DnmSl.deleteAllLayers()',
            'type' => 'button' 
        ) );
        $this->setChild( 'deleteAllLayersBtn', $addLayerBtn );
        
        $addLayerBtn = $this->getLayout()->createBlock( 'adminhtml/widget_button', 'dupLayerBtn', array(
             'title' => Mage::helper( 'dynamicslideshow' )->__( 'Duplicate Layer' ),
            'label' => "<i class='fa fa-copy'></i>" . Mage::helper( 'dynamicslideshow' )->__( 'Duplicate Layer' ),
            'onclick' => 'DnmSl.duplicateLayer()',
            'type' => 'button',
            'id' => 'dupLayerBtn' 
        ) );
        $this->setChild( 'dupLayerBtn', $addLayerBtn );
        
        $addLayerBtn = $this->getLayout()->createBlock( 'adminhtml/widget_button', 'previewSlideBtn', array(
             'label' => Mage::helper( 'dynamicslideshow' )->__( 'Preview Slide' ),
            'onclick' => 'DnmSl.previewSlide()',
            'type' => 'button',
            'class' => 'show-hide' 
        ) );
        $this->setChild( 'previewLayerBtn', $addLayerBtn );
        
        $editLayerBtn = $this->getLayout()->createBlock( 'adminhtml/widget_button', 'editLayerBtn', array(
             'title' => Mage::helper( 'dynamicslideshow' )->__( 'Edit Layer' ),
            'label' => "<i class='fa fa-edit'></i>" . Mage::helper( 'dynamicslideshow' )->__( 'Edit Layer' ),
            'onclick' => 'DnmSl.editLayer()',
            'type' => 'button',
            'id' => 'editLayerBtn' 
        ) );
        $this->setChild( 'editLayerBtn', $editLayerBtn );
        
        return parent::_prepareLayout();
    }
    
    public function getDivLayersStyle()
    {
        $sliders = Mage::registry( 'sliders' );
        if ( $sliders->getId() )
        {
            return sprintf( 'width:%dpx; height:%dpx;', $sliders->getWidth() ? $sliders->getWidth() : 900, $sliders->getHeight() ? $sliders->getHeight() : 300 );
        }
    }
    
    public function getAddLayerImageUrl()
    {
        return Mage::getSingleton( 'adminhtml/url' )->getUrl( 'adminhtml/cms_wysiwyg_images/index', array(
             'static_urls_allowed' => 1,
            'onInsertCallback' => 'DnmSl.addLayerImage' 
        ) );
    }
    
    public function getAddLayerVideoUrl()
    {
        return Mage::getSingleton( 'adminhtml/url' )->getUrl( 'dynamicslideshow/adminhtml_dynamicslideshow/video' );
    }
}