<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Block_Adminhtml_Widget_Form_Browsers extends Mage_Adminhtml_Block_Widget implements Varien_Data_Form_Element_Renderer_Interface
{
    protected $_element;
    
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate( 'sm/dynamicslideshow/widget/form/browser.phtml' );
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
        $this->setElement( $this->getData( 'element' ) );
        $browserBtn = $this->getLayout()->createBlock( 'adminhtml/widget_button', 'button', array(
             'label' => "<i class='fa fa-upload'></i>",
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Click to browser media' ),
            'type' => 'button',
            'onclick' => sprintf( '_MediabrowserUtility.openDialog(\'%s\')', Mage::getSingleton( 'adminhtml/url' )->getUrl( 'adminhtml/cms_wysiwyg_images/index', array(
                 'static_urls_allowed' => 1,
                'target_element_id' => $this->getElement()->getHtmlId() 
            ) ) ) 
        ) );
        $this->setChild( 'browserBtn', $browserBtn );
        $clearBtn = $this->getLayout()->createBlock( 'adminhtml/widget_button', 'button', array(
             'label' => "<i class='fa fa-times-circle'></i>",
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Click to clear  value' ),
            'type' => 'button',
            'onclick' => "on_{$this->getElement()->getHtmlId()}_clear_click();" 
        ) );
        $this->setChild( 'clearBtn', $clearBtn );
        return parent::_prepareLayout();
    }
}