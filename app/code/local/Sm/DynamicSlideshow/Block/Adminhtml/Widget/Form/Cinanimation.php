<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Block_Adminhtml_Widget_Form_Cinanimation extends Mage_Adminhtml_Block_Widget implements Varien_Data_Form_Element_Renderer_Interface
{
    protected $_element;
    
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate( 'sm/dynamicslideshow/widget/form/cinanimation.phtml' );
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
        $this->setChild( 'edit', $this->getLayout()->createBlock( 'adminhtml/widget_button', '', array(
             'label' => Mage::helper( 'dynamicslideshow' )->__( 'Custom Animation' ),
            'type' => 'button',
            'id' => 'cInAnimation',
            'onclick' => sprintf( 'DnmSl.openAnimationDialog(\'%s\', \'editAnimationWindow\', \'%s\', \'in\')', $this->getUrl( 'dynamicslideshow/adminhtml_dynamicslideshow/animation' ), Mage::helper( 'dynamicslideshow' )->__( 'Edit Animation' ) ) 
        ) ) );
        $this->setChild( 'new', $this->getLayout()->createBlock( 'adminhtml/widget_button', '', array(
             'label' => Mage::helper( 'dynamicslideshow' )->__( 'New Animation' ),
            'type' => 'button',
            'id' => 'cNewInAnimation',
            'onclick' => sprintf( 'DnmSl.openAnimationDialog(\'%s\', \'editAnimationWindow\', \'%s\', \'new\')', $this->getUrl( 'dynamicslideshow/adminhtml_dynamicslideshow/animation' ), Mage::helper( 'dynamicslideshow' )->__( 'New Animation' ) ) 
        ) ) );
        
        return parent::_prepareLayout();
    }
}