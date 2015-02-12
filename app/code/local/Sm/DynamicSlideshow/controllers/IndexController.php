<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */
class Sm_DynamicSlideshow_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
    
    public function previewAction()
    {
        $id = $this->getRequest()->getParam( 'id' );
        $this->loadLayout();
        $this->getLayout()->getBlock( 'root' )->setTemplate( 'page/empty.phtml' );
        $block = $this->getLayout()->createBlock( 'dynamicslideshow/sliders_preview', '', array(
             'id' => $id 
        ) );
        $this->getLayout()->getBlock( 'content' )->append( $block );
		$this->_title( Mage::helper( 'dynamicslideshow' )->__( 'Sm Dynamic SlideShow' ) );
		$this->_title( Mage::helper( 'dynamicslideshow' )->__( 'Preview Slider' ) );
        $this->renderLayout();
    }
    
    public function getCssCaptionsAction()
    {
        $this->getResponse()->setHeader( 'Content-Type', 'text/css' );
        $this->getResponse()->setHeader( 'X-Content-Type-Options', 'nosniff' );
        
        $css        = '';
        $collection = Mage::getModel( 'dynamicslideshow/css' )->getCollection();
        foreach ( $collection as $item )
        {
            try
            {
                $rules = Mage::helper( 'core' )->jsonDecode( $item->getParams() );
                $css .= sprintf( "%s{%s}\n", $item->getHandle(), implode( '', $this->_getCssRule( $rules ) ) );
                $setting = Mage::helper( 'core' )->jsonDecode( $item->getSettings() );
                if ( isset( $setting['hover'] ) )
                {
                    $hover = Mage::helper( 'core' )->jsonDecode( $item->getHover() );
                    $css .= sprintf( "%s:hover{%s}\n", $item->getHandle(), implode( '', $this->_getCssRule( $hover ) ) );
                }
            }
            catch ( Exception $e )
            {
            }
        }
        
        $this->getResponse()->setBody( $css );
    }
    
    protected function _getCssRule( $rules )
    {
        $out = array();
        if ( is_array( $rules ) )
        {
            foreach ( $rules as $k => $v )
            {
                $out[] = sprintf( "%s: %s;", $k, $v );
            }
        }
        return $out;
    }
}