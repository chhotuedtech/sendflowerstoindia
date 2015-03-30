<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Block_Adminhtml_Slides_Css extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'dynamicslideshow';
        $this->_controller = 'adminhtml_slides';
        $this->_mode       = 'css';
        parent::__construct();
        $model = Mage::registry( 'css' );
        
        $this->setId( 'editCssForm' );
        $this->_headerText = $model->getId() ? Mage::helper( 'dynamicslideshow' )->__( 'Edit Style "%s"', $model->getPrettyName() ) : Mage::helper( 'dynamicslideshow' )->__( 'Edit Style' );
        
        $this->removeButton( 'back' );
        $this->removeButton( 'reset' );
        $this->_updateButton( 'save', 'label', Mage::helper( 'dynamicslideshow' )->__( 'Save' ) );
        $this->_updateButton( 'save', 'id', 'btnCssSave' );
        $popupId = $this->getRequest()->getParam( 'popupId' );
        $this->_updateButton( 'save', 'onclick', "DnmSl.saveLayerCss('{$popupId}', '{$model->getId()}')" );
        $this->_addButton( 'delete', array(
             'label' => Mage::helper( 'dynamicslideshow' )->__( 'Delete' ),
            'class' => 'delete',
            'onclick' => "DnmSl.deleteLayerCss('{$popupId}', '{$model->getId()}')" 
        ) );
        $this->_addButton( 'saveas', array(
             'id' => 'btnCssSaveAs',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Save as' ),
            'class' => 'save',
            'onclick' => "DnmSl.saveAsLayerCss('{$popupId}')" 
        ) );
        
        if ( $model->getId() )
        {
            $this->_formScripts[] = sprintf( "DnmSl.assignCssForm(%s)", Mage::helper( 'core' )->jsonEncode( $model->getStyle() ) );
        }
        
        $this->_formScripts[] = "setTimeout(function(){jscolor.init();});";
    }
}