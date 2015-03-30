<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Block_Adminhtml_Slides_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    
    public function __construct()
    {
        $this->_blockGroup    = 'dynamicslideshow';
        $this->_controller    = 'adminhtml_slides';
        $this->_form          = 'edit';
        $slides               = Mage::registry( 'slides' );
        $sliders              = Mage::registry( 'sliders' );
        $mediaUrl             = Mage::getBaseUrl( 'media' );
        $cssUrl               = $this->getUrl( 'dynamicslideshow/index/getCssCaptions' );
        $cssDelUrl            = $this->getUrl( 'dynamicslideshow/adminhtml_dynamicslideshow/deleteCss' );
        $cssSaveUrl           = $this->getUrl( 'dynamicslideshow/adminhtml_dynamicslideshow/saveCss' );
        $animUrl              = $this->getUrl( 'dynamicslideshow/adminhtml_dynamicslideshow/saveAnimation' );
        $animDelUrl           = $this->getUrl( 'dynamicslideshow/adminhtml_dynamicslideshow/deleteAnimation' );
        $anims                = $this->_getAnims();
        $this->_formScripts[] = "editForm = new varienForm('smdnsl_slides_form', '');";
        $this->_formScripts[] = "var cssEditor;";
        $this->_formScripts[] = "var DnmSl = new DynamicSlideshow(editForm, {$sliders->getDelay()}, {
            css_save_url: '{$cssSaveUrl}',
            css_delete_url: '{$cssDelUrl}',
            css_url: '{$cssUrl}',
            media_url: '{$mediaUrl}',
            anim_save_url: '{$animUrl}',
            anim_delete_url: '{$animDelUrl}',
            anims: {$anims}
        });";
        if ( is_array( $slides->getLayers() ) )
        {
            foreach ( $slides->getLayers() as $layer )
            {
                $this->_formScripts[] = "DnmSl.addLayer(" . Mage::helper( 'core' )->jsonEncode( $layer ) . ");";
            }
        }
        parent::__construct();
    }
    
    protected function _getAnims()
    {
        $anims      = array();
        $collection = Mage::getModel( 'dynamicslideshow/animation' )->getCollection();
        foreach ( $collection as $item )
        {
            $anims[] = array(
                 'id' => $item->getId(),
                'name' => $item->getName(),
                'params' => Mage::helper( 'core' )->jsonDecode( $item->getParams() ) 
            );
        }
        return Mage::helper( 'core' )->jsonEncode( $anims );
    }
    
    public function getHeaderText()
    {
        $slides = Mage::registry( 'slides' );
        return Mage::helper( 'dynamicslideshow' )->__( $slides->getId() ? "Edit Slide '{$slides->getTitle()}'" : 'New Slide' );
    }
    
    public function _prepareLayout()
    {
        parent::_prepareLayout();
        $head      = $this->getLayout()->getBlock( 'head' );
        $sliders   = Mage::registry( 'sliders' );
        $slides    = Mage::registry( 'slides' );
        $backUrl   = $this->getUrl( '*/*/edit', array(
             'id' => $sliders->getId(),
            'activeTab' => 'slides_section' 
        ) );
        $deleteUrl = $this->getUrl( '*/*/deleteSlide', array(
             'id' => $slides->getId(),
            'sid' => $sliders->getId() 
        ) );
        $this->updateButton( 'delete', 'onclick', "setLocation('{$deleteUrl}');" );
        $this->updateButton( 'save', 'onclick', 'DnmSl.save();' );
        $this->updateButton( 'back', 'onclick', 'setLocation(\'' . $backUrl . '\');' );
        $this->_addButton( 'sac', array(
             'label' => Mage::helper( 'dynamicslideshow' )->__( 'Save and Continue Edit' ),
            'class' => 'save',
            'onclick' => "DnmSl.save(true);" 
        ) );
        
        if ( $sliders->getId() )
        {
            if ( $sliders->getData( 'load_googlefont' ) == 'yes' )
            {
                $fonts = $sliders->getData( 'google_font' );
                if ( is_array( $fonts ) )
                {
                    foreach ( $fonts as $font )
                    {
                        $href = Mage::helper( 'dynamicslideshow' )->getCssHref( $font );
                        $head->addLinkRel( 'stylesheet', $href );
                    }
                }
            }
        }
        return $this;
    }
}