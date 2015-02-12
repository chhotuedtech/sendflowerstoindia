<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Block_Adminhtml_Sliders_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_objectId   = 'id';
        $this->_blockGroup = 'dynamicslideshow';
        $this->_controller = 'adminhtml_sliders';
        $this->_updateButton( 'save', 'label', Mage::helper( 'dynamicslideshow' )->__( 'Save Item' ) );
        $this->_updateButton( 'delete', 'label', Mage::helper( 'dynamicslideshow' )->__( 'Delete Item' ) );
        $sliders    = Mage::registry( 'sliders' );
        $previewUrl = $this->getUrl( 'dynamicslideshow/index/preview', array(
             'id' => $sliders->getId() 
        ) );
        if ( $sliders->getId() )
        {
            $this->_addButton( 'preview', array(
                 'label' => Mage::helper( 'dynamicslideshow' )->__( 'Preview' ),
                'title' => Mage::helper( 'dynamicslideshow' )->__( 'Preview Slider' ),
                'class' => 'show-hide',
                'onclick' => "popWin('$previewUrl')" 
            ) );
        }
        $this->_addButton( 'saveandcontinue', array(
             'label' => Mage::helper( 'dynamicslideshow' )->__( 'Save And Continue Edit' ),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save' 
        ), -100 );
        $this->_formScripts[] = "
			editForm = new varienForm('smdnsl_sliders_form', '');
			function toggleEditor() 
			{
				if (tinyMCE.getInstanceById('smdnsl_sliders_form') == null) 
				{
					tinyMCE.execCommand('mceAddControl', false, 'smdnsl_sliders_form');
				} 
				else 
				{
					tinyMCE.execCommand('mceRemoveControl', false, 'smdnsl_sliders_form');
				}
			}
			 
			function saveAndContinueEdit()
			{
				editForm.submit($('smdnsl_sliders_form').action+'back/edit/');
			}
			";
    }
    
    public function getHeaderText()
    {
        $model = Mage::registry( 'sliders' );
        if ( $model->getId() )
        {
            return Mage::helper( 'dynamicslideshow' )->__( "Edit Item '%s'", $this->htmlEscape( $model->getTitle() ) );
        }
        else
        {
            return Mage::helper( 'dynamicslideshow' )->__( 'New Slider' );
        }
    }
}