<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Block_Adminhtml_Sliders_Css extends Mage_Adminhtml_Block_Template
{
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate( 'sm/dynamicslideshow/css.phtml' );
    }
    
    public function _prepareLayout()
    {
        $this->setChild( 'save', $this->getLayout()->createBlock( 'adminhtml/widget_button', '', array(
             'label' => Mage::helper( 'dynamicslideshow' )->__( 'Save' ),
            'type' => 'button',
            'class' => 'save',
            'id' => 'btnCssSave',
            'onclick' => "DnmSl.saveCss('{$this->getUrl('*/*/saveCss')}', 'editCssWindow');" 
        ) ) );
        $this->setChild( 'reset', $this->getLayout()->createBlock( 'adminhtml/widget_button', '', array(
             'label' => Mage::helper( 'dynamicslideshow' )->__( 'Reset to Default' ),
            'type' => 'button',
            'class' => 'default',
            'onclick' => sprintf( 'DnmSl.resetCss(\'%s\');', $this->getUrl( 'dynamicslideshow/index/getCssCaptions', array(
                 'reset' => 1 
            ) ) ) 
        ) ) );
        return parent::_prepareLayout();
    }
    
    public function getCssContent()
    {
        $css = Mage::getStoreConfig( 'dynamicslideshow/config/css' );
        if ( !$css )
        {
            $file = Mage::getBaseDir() . '/js/sm/dynamicslideshow/rs-plugin/css/captions.css';
            if ( is_file( $file ) )
                $css = file_get_contents( $file );
        }
        return $css;
    }
}