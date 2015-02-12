<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Block_Adminhtml_Widget_Grid_Column_Renderer_Slides_Title extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    
    public function render( Varien_Object $row )
    {
        $params = Mage::helper( 'core' )->jsonDecode( $row->getParams() );
        if ( isset( $params['title'] ) )
            return $params['title'];
        return '';
    }
}