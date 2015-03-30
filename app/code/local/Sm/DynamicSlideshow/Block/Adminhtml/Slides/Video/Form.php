<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Block_Adminhtml_Slides_Video_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form( array(
             'id' => 'edit_form',
            'method' => 'post' 
        ) );
        $view = $form->addFieldset( 'video_view_fieldset', array(
             'legend' => $this->helper( 'dynamicslideshow' )->__( 'Video Preview' ) 
        ) );
        $view->addField( 'video_title', 'text', array(
             'label' => $this->helper( 'dynamicslideshow' )->__( 'Video Title' ) 
        ) );
        $view->addField( 'video_thumb', 'text', array(
             'label' => $this->helper( 'dynamicslideshow' )->__( 'Video Thumb' ) 
        ) );
        $form->getElement( 'video_thumb' )->setRenderer( $this->getLayout()->createBlock( 'dynamicslideshow/adminhtml_widget_form_thumb' ) );
        
        $fieldset = $form->addFieldset( 'video_info_fieldset', array(
             'legend' => $this->helper( 'dynamicslideshow' )->__( 'Video Settings' ) 
        ) );
        if ( $serial = $this->getRequest()->getParam( 'serial' ) )
        {
            $fieldset->addField( 'video_serial', 'hidden', array(
                 'value' => $serial 
            ) );
        }
        $v  = $fieldset->addField( 'video_type', 'select', array(
             'name' => 'video_type',
            'label' => $this->helper( 'dynamicslideshow' )->__( 'Select video service' ),
            'required' => true,
            'values' => $this->getVideoServices(),
            'onchange' => 'DnmSl.onChangeVideoType(this)' 
        ) );
        $v1 = $fieldset->addField( 'video_src', 'text', array(
             'name' => 'video_src',
            'label' => $this->helper( 'dynamicslideshow' )->__( 'Enter video ID or URL' ),
            'required' => true,
            'note' => $this->helper( 'dynamicslideshow' )->__( 'Ex: cXwQjHRZieI or 30300114' ) 
        ) );
        $v2 = $fieldset->addField( 'video_poster', 'text', array(
             'name' => 'video_poster',
            'label' => $this->helper( 'dynamicslideshow' )->__( 'Poster Image Url' ),
            'note' => $this->helper( 'dynamicslideshow' )->__( 'Ex: http://video-js.zencoder.com/oceans-clip.png' ) 
        ) );
        $v3 = $fieldset->addField( 'video_mp4', 'text', array(
             'name' => 'video_mp4',
            'label' => $this->helper( 'dynamicslideshow' )->__( 'Video MP4 Url' ),
            'note' => $this->helper( 'dynamicslideshow' )->__( 'Ex: http://video-js.zencoder.com/oceans-clip.mp4' ) 
        ) );
        $form->getElement( 'video_mp4' )->setRenderer( $this->getLayout()->createBlock( 'dynamicslideshow/adminhtml_widget_form_browser', '', array(
             'element' => $v3 
        ) ) );
        $v4 = $fieldset->addField( 'video_webm', 'text', array(
             'name' => 'video_webm',
            'label' => $this->helper( 'dynamicslideshow' )->__( 'Video WEBM Url' ),
            'note' => $this->helper( 'dynamicslideshow' )->__( 'Ex: http://video-js.zencoder.com/oceans-clip.webm' ) 
        ) );
        $form->getElement( 'video_webm' )->setRenderer( $this->getLayout()->createBlock( 'dynamicslideshow/adminhtml_widget_form_browser', '', array(
             'element' => $v4 
        ) ) );
        $v5 = $fieldset->addField( 'video_ogv', 'text', array(
             'name' => 'video_ogv',
            'label' => $this->helper( 'dynamicslideshow' )->__( 'Video OGV Url' ),
            'note' => $this->helper( 'dynamicslideshow' )->__( 'Ex: http://video-js.zencoder.com/oceans-clip.ogv' ) 
        ) );
        $form->getElement( 'video_ogv' )->setRenderer( $this->getLayout()->createBlock( 'dynamicslideshow/adminhtml_widget_form_browser', '', array(
             'element' => $v5 
        ) ) );
        $s = $fieldset->addField( 'video_search', 'text', array ());
        $form->getElement( 'video_search' )->setRenderer( $this->getLayout()->createBlock( 'dynamicslideshow/adminhtml_widget_form_search', '', array(
             'element' => $s 
        ) ) );
        $fieldset->addField( 'video_width', 'text', array(
             'name' => 'video_width',
            'label' => $this->helper( 'dynamicslideshow' )->__( 'Video Width' ),
            'required' => true,
            'class' => 'validate-number',
            'value' => '320' 
        ) );
        $fieldset->addField( 'video_height', 'text', array(
             'name' => 'video_height',
            'label' => $this->helper( 'dynamicslideshow' )->__( 'Video Height' ),
            'required' => true,
            'class' => 'validate-number',
            'value' => '240' 
        ) );
        $fieldset->addField( 'video_fullwidth', 'checkbox', array(
             'name' => 'video_fullwidth',
            'label' => $this->helper( 'dynamicslideshow' )->__( 'Full Width' ),
            'onchange' => 'DnmSl.onChangeVideoFullWidth(this)' 
        ) );
        $fieldset->addField( 'video_loop', 'checkbox', array(
             'name' => 'video_loop',
            'label' => $this->helper( 'dynamicslideshow' )->__( 'Loop Video' ) 
        ) );
        $fieldset->addField( 'video_control', 'checkbox', array(
             'name' => 'video_control',
            'label' => $this->helper( 'dynamicslideshow' )->__( 'Hide Controls' ) 
        ) );
        $fieldset->addField( 'video_args', 'text', array(
             'name' => 'video_args',
            'label' => $this->helper( 'dynamicslideshow' )->__( 'Video Paramaters' ) 
        ) );
        $a  = $fieldset->addField( 'video_autoplay', 'checkbox', array(
             'name' => 'video_autoplay',
            'label' => $this->helper( 'dynamicslideshow' )->__( 'Autoplay' ),
            'value' => 1 
        ) );
        $a1 = $fieldset->addField( 'video_autoplayonlyfirsttime', 'checkbox', array(
             'name' => 'video_autoplay_first_time',
            'label' => $this->helper( 'dynamicslideshow' )->__( 'Autplay Only First Time' ) 
        ) );
        $fieldset->addField( 'video_nextslide', 'checkbox', array(
             'name' => 'video_nextslide',
            'label' => $this->helper( 'dynamicslideshow' )->__( 'Next Slide On End' ) 
        ) );
        $fieldset->addField( 'video_force_rewind', 'checkbox', array(
             'name' => 'video_force_rewind',
            'label' => $this->helper( 'dynamicslideshow' )->__( 'Force Rewind' ) 
        ) );
        $fieldset->addField( 'video_mute', 'checkbox', array(
             'name' => 'video_mute',
            'label' => $this->helper( 'dynamicslideshow' )->__( 'Mute' ) 
        ) );
        $form->setUseContainer( true );
        $this->setForm( $form );
        $this->setChild( 'form_after', $this->getLayout()->createBlock( 'adminhtml/widget_form_element_dependence' )->addFieldMap( $v->getHtmlId(), $v->getName() )->addFieldMap( $v1->getHtmlId(), $v1->getName() )->addFieldMap( $v2->getHtmlId(), $v2->getName() )->addFieldMap( $v3->getHtmlId(), $v3->getName() )->addFieldMap( $v4->getHtmlId(), $v4->getName() )->addFieldMap( $v5->getHtmlId(), $v5->getName() )->addFieldMap( $s->getHtmlId(), $s->getName() )->addFieldDependence( $v1->getName(), $v->getName(), array(
             'youtube',
            'vimeo' 
        ) )->addFieldDependence( $s->getName(), $v->getName(), array(
             'youtube',
            'vimeo' 
        ) )->addFieldDependence( $v2->getName(), $v->getName(), 'html5' )->addFieldDependence( $v3->getName(), $v->getName(), 'html5' )->addFieldDependence( $v4->getName(), $v->getName(), 'html5' )->addFieldDependence( $v5->getName(), $v->getName(), 'html5' ) );
        return parent::_prepareForm();
    }
    
    public function getVideoServices()
    {
        return array(
             array(
                 'value' => 'youtube',
                'label' => $this->helper( 'dynamicslideshow' )->__( 'Youtube' ) 
            ),
            array(
                 'value' => 'vimeo',
                'label' => $this->helper( 'dynamicslideshow' )->__( 'Vimeo' ) 
            ),
            array(
                 'value' => 'html5',
                'label' => $this->helper( 'dynamicslideshow' )->__( 'HTML5' ) 
            ) 
        );
    }
}