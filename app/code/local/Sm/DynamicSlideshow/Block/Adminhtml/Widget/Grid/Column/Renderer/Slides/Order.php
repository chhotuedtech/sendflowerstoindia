<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Block_Adminhtml_Widget_Grid_Column_Renderer_Slides_Order extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    protected $_variablePattern = '/\\$([a-z0-9_]+)/i';
    
    public function _getValue( Varien_Object $row )
    {
        $format       = ( $this->getColumn()->getFormat() ) ? $this->getColumn()->getFormat() : null;
        $defaultValue = $this->getColumn()->getDefault();
        $htmlId       = 'editable_' . $row->getId();
        $saveUrl      = $this->getUrl( '*/*/ajaxSave' );
        if ( is_null( $format ) )
        {
            $data   = parent::_getValue( $row );
            $string = is_null( $data ) ? $defaultValue : $data;
            $html   = sprintf( '<div id="%s" control="text" saveUrl="%s" attr="%s" entity="%s" class="editable">%s</div>', $htmlId, $saveUrl, $this->getColumn()->getIndex(), $row->getId(), $this->escapeHtml( $string ) );
        }
        elseif ( preg_match_all( $this->_variablePattern, $format, $matches ) )
        {
            $formattedString = $format;
            foreach ( $matches[0] as $matchIndex => $match )
            {
                $value           = $row->getData( $matches[1][$matchIndex] );
                $formattedString = str_replace( $match, $value, $formattedString );
            }
            $html = sprintf( '<div id="%s" control="text" saveUrl="%s" attr="%s" entity="%s" class="editable">%s</div>', $htmlId, $saveUrl, $this->getColumn()->getIndex(), $row->getId(), $formattedString );
        }
        else
        {
            $html = sprintf( '<div id="%s" control="text" saveUrl="%s" attr="%s" entity="%s" class="editable">%s</div>', $htmlId, $saveUrl, $this->getColumn()->getIndex(), $row->getId(), $this->escapeHtml( $format ) );
        }
        return $html . "<script>if (bindInlineEdit) bindInlineEdit('{$htmlId}');</script>";
    }
}