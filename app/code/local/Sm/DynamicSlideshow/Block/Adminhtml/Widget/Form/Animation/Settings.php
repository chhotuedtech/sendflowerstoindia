<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Block_Adminhtml_Widget_Form_Animation_Settings extends Mage_Adminhtml_Block_Widget implements Varien_Data_Form_Element_Renderer_Interface
{
    protected $_element;
    protected $_animation;
    
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate( 'sm/dynamicslideshow/widget/form/anim/settings.phtml' );
        
        $model = Mage::getModel( 'dynamicslideshow/animation' );
        $aid   = $this->getRequest()->getParam( 'aid' );
        if ( strpos( $aid, 'custom' ) === 0 )
        {
            $part = explode( '-', $aid );
            $model->load( $part[1] );
            if ( $model->getId() )
            {
                $this->_animation = Mage::helper( 'core' )->jsonDecode( $model->getParams() );
            }
        }
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
    
    public function getAnimType()
    {
        return $this->getRequest()->getParam( 'type', 'in' );
    }
    
    public function getValue( $name )
    {
        if ( $this->_animation )
        {
            return isset( $this->_animation[$name] ) ? $this->_animation[$name] : 0;
        }
    }
	
}