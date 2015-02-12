<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Block_Adminhtml_Sliders_Edit_Tab_Parallax extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $model    = Mage::registry( 'sliders' );
        $form     = new Varien_Data_Form();
        $fieldset = $form->addFieldset( 'parallax_fieldset', array(
             'legend' => Mage::helper( 'dynamicslideshow' )->__( 'Parallax Settings' ) 
        ) );
        $fieldset->addField( 'parallax', 'radios', array(
             'name' => 'parallax',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Action' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Action' ),
            'value' => $model->getData( 'parallax' ) ? $model->getData( 'parallax' ) : 'mouse',
            'values' => array(
                 array(
                     'value' => 'mouse',
                    'label' => Mage::helper( 'dynamicslideshow' )->__( 'Mouse' ) 
                ),
                array(
                     'value' => 'scroll',
                    'label' => Mage::helper( 'dynamicslideshow' )->__( 'Scroll' ) 
                ) 
            ),
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Possible Values: "mouse" , "scroll" - How the Parallax should act. On Mouse Hover movements and Tilts on Mobile Devices, or on Scroll (scroll is disabled on Mobiles !)' ) 
        ) );
        
        $fieldset->addField( 'parallaxBgFreeze', 'radios', array(
             'name' => 'parallaxBgFreeze',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Bg Freeze' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Bg Freeze' ),
            'value' => $model->getData( 'parallaxBgFreeze' ) ? $model->getData( 'parallaxBgFreeze' ) : $model->OPTION_ON,
            'values' => $model->getOptOnOff(),
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Possible Values: "on", "off"  - if it is off, the Main slide images will also move with Parallax Level 1 on Scroll.' ) 
        ) );
        
        $fieldset->addField( 'parallaxDisableOnMobile', 'radios', array(
             'name' => 'parallaxDisableOnMobile',
            'label' => Mage::helper( 'dynamicslideshow' )->__( 'Disable On Mobile' ),
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Disable On Mobile' ),
            'value' => $model->getData( 'parallaxDisableOnMobile' ) ? $model->getData( 'parallaxDisableOnMobile' ) : $model->OPTION_OFF,
            'values' => $model->getOptOnOff(),
            'note' => Mage::helper( 'dynamicslideshow' )->__( 'Possible Values: "on", "off"  - Turn on/ off Parallax Effect on Mobile Devices' ) 
        ) );
        
        $this->setForm( $form );
        if ( $model->getId() )
            $form->setValues( $model->getData() );
        return parent::_prepareForm();
    }
}