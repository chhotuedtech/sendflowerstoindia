<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_Dynamicslideshow_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getCssHref( $data )
    {
        if ( $data )
            return sprintf( 'http://fonts.googleapis.com/css?family=%s', $data );
    }
}
