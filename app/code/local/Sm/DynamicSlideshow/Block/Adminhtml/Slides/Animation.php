<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Block_Adminhtml_Slides_Animation extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'dynamicslideshow';
        $this->_controller = 'adminhtml_slides';
        $this->_mode       = 'animation';
        parent::__construct();
        $this->setId( 'editAnimationForm' );
        $this->_headerText = Mage::helper( 'dynamicslideshow' )->__( 'Custom Animation Form' );
        $this->removeButton( 'back' );
        $this->removeButton( 'reset' );
        $this->_updateButton( 'save', 'label', Mage::helper( 'dynamicslideshow' )->__( 'Save' ) );
        $popupId = $this->getRequest()->getParam( 'popupId' );
        $type    = $this->getRequest()->getParam( 'type' );
        $aid     = $this->getRequest()->getParam( 'aid' );
        $this->_updateButton( 'save', 'onclick', "DnmSl.addCustomAnimation('{$popupId}', '{$type}')" );
        $this->_addButton( 'delete', array(
             'label' => Mage::helper( 'dynamicslideshow' )->__( 'Delete' ),
            'class' => 'delete',
            'onclick' => "DnmSl.removeCustomAnimation('{$popupId}', '{$aid}')" 
        ) );
    }
}