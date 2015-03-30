<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Model_Mysql4_Slides_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init( 'dynamicslideshow/slides' );
    }
    
    public function addSlidersFilter( $sliders )
    {
        if ( $sliders instanceof Sm_DynamicSlideshow_Model_Sliders && $sliders->getId() )
        {
            $this->addFieldToFilter( 'sliderid', array(
                 'eq' => $sliders->getId() 
            ) );
        }
        elseif ( is_numeric( $sliders ) )
        {
            $this->addFieldToFilter( 'sliderid', array(
                 'eq' => $sliders 
            ) );
        }
        elseif ( is_array( $sliders ) )
        {
            $this->addFieldToFilter( 'sliderid', array(
                 'in' => $sliders 
            ) );
        }
        return $this;
    }
}