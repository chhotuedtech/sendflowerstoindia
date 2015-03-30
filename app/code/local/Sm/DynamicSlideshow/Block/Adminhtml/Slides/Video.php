<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Block_Adminhtml_Slides_Video extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'dynamicslideshow';
        $this->_controller = 'adminhtml_slides';
        $this->_mode       = 'video';
        parent::__construct();
        $this->setId( 'addVideoForm' );
        $this->_headerText = Mage::helper( 'dynamicslideshow' )->__( 'Add Video Form' );
        $this->removeButton( 'back' );
        $this->removeButton( 'reset' );
        $this->_updateButton( 'save', 'label', Mage::helper( 'dynamicslideshow' )->__( 'Add' ) );
        $popupId = $this->getRequest()->getParam( 'popupId' );
        $this->_updateButton( 'save', 'onclick', "DnmSl.addLayerVideo('{$popupId}')" );
        if ( $serial = $this->getRequest()->getParam( 'serial' ) )
        {
            $this->_formScripts[] = "DnmSl.assignVideoForm('{$serial}')";
        }
        else
        {
            $this->_formScripts[] = 'DnmSl.toggleVideoForm(false)';
        }
    }
}