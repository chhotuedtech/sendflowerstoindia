<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Block_Adminhtml_Sliders extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'dynamicslideshow';
        $this->_controller = 'adminhtml_sliders';
        $this->_headerText = Mage::helper( 'dynamicslideshow' )->__( 'Manager Sliders' );
        parent::__construct();
        if ( $this->_isAllowedAction( 'add' ) )
        {
            $this->_updateButton( 'add', 'label', Mage::helper( 'dynamicslideshow' )->__( 'Add New Slider' ) );
        }
        else
        {
            $this->_removeButton( 'add' );
        }
    }
    
    protected function _isAllowedAction( $action )
    {
        return true;
    }
}