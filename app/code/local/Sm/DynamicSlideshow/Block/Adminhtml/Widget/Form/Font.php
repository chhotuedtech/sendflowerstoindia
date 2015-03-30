<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Block_Adminhtml_Widget_Form_Font extends Mage_Adminhtml_Block_Widget implements Varien_Data_Form_Element_Renderer_Interface
{
    protected $_element;
    
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate( 'sm/dynamicslideshow/widget/form/font.phtml' );
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
    
    public function getAddButtonHtml()
    {
        return $this->getChildHtml( 'addBtn' );
    }
    
    public function getDeleteButtonHtml()
    {
        return $this->getChildHtml( 'deleteBtn' );
    }
    
    protected function _prepareLayout()
    {
        $addBtn = $this->getLayout()->createBlock( 'adminhtml/widget_button' )->setData( array(
             'label' => '<i class="fa fa-plus-circle"></i>',
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Add Font' ),
            'onclick' => 'return dnSl.add()' 
        ) );
        $this->setChild( 'addBtn', $addBtn );
        
        $delteBtn = $this->getLayout()->createBlock( 'adminhtml/widget_button' )->setData( array(
             'label' => '<i class="fa fa-times-circle"></i>',
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Delete Font' ),
            'onclick' => 'return dnSl.remove({{id}})' 
        ) );
        $this->setChild( 'deleteBtn', $delteBtn );
        parent::_prepareLayout();
    }
    
    public function getFonts()
    {
        $fonts = array();
        $data  = $this->getElement()->getValue();
        if ( is_array( $data ) )
        {
            foreach ( $data as $value )
            {
                if ( $value )
                    $fonts[] = Mage::helper( 'core' )->escapeHtml( $value );
            }
        }
        return $fonts;
    }
}