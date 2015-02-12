<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Model_Source_Sliders
{
    public function toOptionArray()
    {
        $collection = Mage::getModel( 'dynamicslideshow/sliders' )->getCollection();
        $array      = array();
        foreach ( $collection as $slide )
        {
            $array[] = array(
                 'value' => $slide->getId(),
                'label' => $slide->getTitle() 
            );
        }
        return $array;
    }
}