<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Model_Mysql4_Css_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init( 'dynamicslideshow/css' );
    }
    
    public function toOptionArray()
    {
        $res = array();
        foreach ( $this as $item )
        {
            $style = $item->getPrettyName();
            $res[] = array(
                 'value' => $item->getId(),
                'label' => $style 
            );
        }
        return $res;
    }
}