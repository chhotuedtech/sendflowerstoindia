<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Model_Css extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init( 'dynamicslideshow/css' );
    }
    
    protected function _afterLoad()
    {
        $helper            = Mage::helper( 'core' );
        $normalParams      = $helper->jsonDecode( $this->getParams() );
        $hoverParams       = $helper->jsonDecode( $this->getHover() );
        $style['normal']   = $this->_parseToCss( $normalParams );
        $style['hover']    = $this->_parseToCss( $hoverParams );
        $style['settings'] = $helper->jsonDecode( $this->getSettings() );
        $this->setData( 'style', $style );
        parent::_afterLoad();
    }
    
    public function getPrettyName()
    {
        return $this->getData( 'handle' ) ? str_replace( '.tp-caption.', '', $this->getData( 'handle' ) ) : '';
    }
    
    protected function _parseToCss( $params )
    {
        $style = array();
        if ( is_array( $params ) )
        {
            foreach ( $params as $param => $value )
            {
                switch ( $param )
                {
                    case 'margin':
                    case 'padding':
                    case 'border-radius':
                        $part = explode( 'px', trim( $value ) );
                        if ( count( $part ) == 5 )
                        {
                            $style[$param] = array(
                                (int) $part[0],
                                (int) $part[1],
                                (int) $part[2],
                                (int) $part[3] 
                            );
                        }
                        elseif ( count( $part ) == 4 )
                        {
                            $style[$param] = array(
                                (int) $part[0],
                                (int) $part[1],
                                (int) $part[2],
                                (int) $part[1] 
                            );
                        }
                        elseif ( count( $part ) == 3 )
                        {
                            $style[$param] = array(
                                (int) $part[0],
                                (int) $part[1],
                                (int) $part[0],
                                (int) $part[1] 
                            );
                        }
                        elseif ( count( $part ) == 2 )
                        {
                            $style[$param] = array(
                                (int) $part[0],
                                (int) $part[0],
                                (int) $part[0],
                                (int) $part[0] 
                            );
                        }
                        else
                        {
                            $style[$param] = array(
                                 0,
                                0,
                                0,
                                0 
                            );
                        }
                        break;
                    case 'font-size':
                    case 'line-height':
                    case 'border-width':
                        $style[$param] = (int) str_replace( 'px', '', $value );
                        break;
                    default:
                        $style[$param] = trim( $value );
                        break;
                }
            }
        }
        return $style;
    }
}