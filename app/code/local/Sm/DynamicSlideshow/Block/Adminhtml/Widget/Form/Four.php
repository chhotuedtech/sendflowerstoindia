<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Block_Adminhtml_Widget_Form_Four extends Mage_Adminhtml_Block_Widget implements Varien_Data_Form_Element_Renderer_Interface
{
    protected $_element;
    protected $_items;
    
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate( 'sm/dynamicslideshow/widget/form/four.phtml' );
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
    
    public function getAll()
    {
        if ( !$this->_items )
        {
            $items  = array();
            $count  = (int) $this->getElement()->getData( 'count' );
            $labels = $this->getElement()->getData( 'labels' );
            if ( $count && $count > 0 )
            {
                for ( $i = 0; $i < $count; $i++ )
                    $items[] = array(
                         'id' => $this->getElement()->getHtmlId() . '_' . $i,
                        'label' => isset( $labels[$i] ) ? $labels[$i] : '' 
                    );
            }
            $this->_items = $items;
        }
        return $this->_items;
    }
}