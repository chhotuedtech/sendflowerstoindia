<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Block_Adminhtml_Widget_Grid_Column_Renderer_Slides_Thumb extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function _getValue( Varien_Object $row )
    {
        $params = Mage::helper( 'core' )->jsonDecode( $row->getParams() );
        if ( isset( $params['slide_thumb'] ) && $params['slide_thumb'] )
        {
            return sprintf( '<img src="%s" height="100" width="400"/>', $params['slide_thumb'] );
        }
        elseif ( isset( $params['background_type'] ) )
        {
            switch ( $params['background_type'] )
            {
                case 'image':
                    if ( isset( $params['image_url'] ) && $params['image_url'] )
                    {
                        return sprintf( '<img src="%s" height="100" width="400" style="margin-top:4px;"/>', strpos( $params['image_url'], 'http' ) === 0 ? $params['image_url'] : Mage::getBaseUrl( 'media' ) . $params['image_url'] );
                    }
                    break;
                case 'solid':
                    if ( isset( $params['slide_bg_color'] ) && $params['slide_bg_color'] )
                        return sprintf( '<div style="width:400px;height:100px;background:#%s;"></div>', $params['slide_bg_color'] );
                    break;
                case 'trans':
                    return '<div style="width:400px;height:100px;background:url(' . $this->getSkinUrl( 'sm/dynamicslideshow/trans_tile2.png' ) . ');"></div>';
                    break;
                case 'external':
                    if ( isset( $params['bg_external'] ) && $params['bg_external'] )
                    {
                        return sprintf( '<img src="%s" height="100" width="400" style="margin-top:4px;"/>', strpos( $params['bg_external'], 'http' ) === 0 ? $params['bg_external'] : Mage::getBaseUrl( 'media' ) . $params['bg_external'] );
                    }
                    break;
            }
        }
        return '';
    }
}