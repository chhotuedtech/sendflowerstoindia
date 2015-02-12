<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Block_Adminhtml_Slides_Edit_Tab_Main extends Mage_Adminhtml_Block_Widget_Form 
{
    public function _prepareForm()
	{
        $sliders     = Mage::registry('sliders');
        $slides      = Mage::registry('slides');
        $form       = new Varien_Data_Form();
        $fieldset   = $form->addFieldset(
            'general_fieldset', array(
                'legend'    => Mage::helper('dynamicslideshow')->__('General Slide Settings'),
				'class'     => 'collapsible'
            )
        );
        if ($sliders->getId()){
            $fieldset->addField('sliderid', 'hidden', array(
                'name'  => 'sliderid',
                'value' => $sliders->getId()
            ));
        }
        if ($slides->getId()){
            $fieldset->addField('id', 'hidden', array(
                'name'  => 'id',
                'value' => $slides->getId()
            ));
        }
        $fieldset->addField('title', 'text', array(
            'name'      => 'title',
            'required'  => true,
            'label'     => Mage::helper('dynamicslideshow')->__('Slide Title'),
            'title'     => Mage::helper('dynamicslideshow')->__('Slide Title'),
            'note'      => Mage::helper('dynamicslideshow')->__('The title of the slide, will be shown in the slides list')
        ));
        $fieldset->addField('state', 'select', array(
            'name'      => 'state',
            'label'     => Mage::helper('dynamicslideshow')->__('State'),
            'title'     => Mage::helper('dynamicslideshow')->__('State'),
            'note'      => Mage::helper('dynamicslideshow')->__('The state of the slide. The unpublished slide will be excluded from the sliders'),
            'values'    => $slides->getOptPubUnpub()
        ));
		
		 $fieldset->addField('date_from', 'date', array(
            'name'      => 'date_from',
            'label'     => Mage::helper('dynamicslideshow')->__('Visible From'),
            'title'     => Mage::helper('dynamicslideshow')->__('Visible From'),
            'note'      => Mage::helper('dynamicslideshow')->__('If set, slide will be visible after the date is reached'),
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            'format'    => Mage::app()->getLocale()->getDateFormat(
                Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
            )
        ));
        $fieldset->addField('date_to', 'date', array(
            'name'      => 'date_to',
            'label'     => Mage::helper('dynamicslideshow')->__('Visible Until'),
            'title'     => Mage::helper('dynamicslideshow')->__('Visible Until'),
            'note'      => Mage::helper('dynamicslideshow')->__('If set, slide will be visible till the date is reached'),
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            'format'    => Mage::app()->getLocale()->getDateFormat(
                Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
            )
        ));
		
        $fieldset->addField('slide_transition', 'multiselect', array(
            'name'      => 'transitions',
            'label'     => Mage::helper('dynamicslideshow')->__('Transitions'),
            'title'     => Mage::helper('dynamicslideshow')->__('Transitions'),
            'note'      => Mage::helper('dynamicslideshow')->__('The appearance transitions of this slide'),
            'values'    => $sliders->getOptTransistion(),
            'value'     => $slides->getData('slide_transition') ? $slides->getData('slide_transition') : array('random')
        ));
        $fieldset->addField('slot_amount', 'text', array(
            'name'      => 'slot_amount',
            'class'     => 'validate-number',
            'label'     => Mage::helper('dynamicslideshow')->__('Slot Amount'),
            'title'     => Mage::helper('dynamicslideshow')->__('Slot Amount'),
            'note'      => Mage::helper('dynamicslideshow')->__('The number of slots or boxes the slide is divided into. If you use boxfade, over 7 slots can be juggy'),
            'value'     => $slides->getData('slot_amount') ? $slides->getData('slot_amount') : 7
        ));
        $fieldset->addField('transition_rotation', 'text', array(
            'name'      => 'transition_rotation',
            'class'     => 'validate-number',
            'label'     => Mage::helper('dynamicslideshow')->__('Rotation'),
            'title'     => Mage::helper('dynamicslideshow')->__('Rotation'),
            'note'      => Mage::helper('dynamicslideshow')->__('Rotation (-720 → 720, 999 = random) Only for Simple Transitions'),
            'value'     => $slides->getData('transition_rotation') ? $slides->getData('transition_rotation') : 0
        ));
        $fieldset->addField('transition_duration', 'text', array(
            'name'      => 'transition_duration',
            'class'     => 'validate-number',
            'label'     => Mage::helper('dynamicslideshow')->__('Transition Duration'),
            'title'     => Mage::helper('dynamicslideshow')->__('Transition Duration'),
            'note'      => Mage::helper('dynamicslideshow')->__('The duration of the transition (Default:300, min: 100 max 2000)'),
            'value'     => $slides->getData('transition_duration') ? $slides->getData('transition_duration') : 500
        ));
        $fieldset->addField('delay', 'text', array(
            'name'      => 'delay',
            'class'     => 'validate-number',
            'label'     => Mage::helper('dynamicslideshow')->__('Delay'),
            'title'     => Mage::helper('dynamicslideshow')->__('Delay'),
            'note'      => Mage::helper('dynamicslideshow')->__('A new delay value for the Slide. If no delay defined per slide, the delay defined via Options (5000ms) will be used')
        ));
		
        $link = $fieldset->addField('enable_link', 'select', array(
            'name'      => 'enable_link',
            'label'     => Mage::helper('dynamicslideshow')->__('Enable Link'),
            'title'     => Mage::helper('dynamicslideshow')->__('Enable Link'),
            'values'    => $sliders->getOptYesNo(),
			'value'     => $slides->getData('enable_link') ? $slides->getData('enable_link') : 'no',
        ));
        $link1 = $fieldset->addField('link_type', 'select', array(
            'name'      => 'link_type',
            'label'     => Mage::helper('dynamicslideshow')->__('Link Type'),
            'title'     => Mage::helper('dynamicslideshow')->__('Link Type'),
            'values'    => $slides->getOptLinkType()
        ));
        $link2 = $fieldset->addField('link', 'text', array(
            'name'      => 'link',
            'label'     => Mage::helper('dynamicslideshow')->__('Slide Link'),
            'title'     => Mage::helper('dynamicslideshow')->__('Slide Link'),
            'note'      => Mage::helper('dynamicslideshow')->__('A link on the whole slide pic')
        ));
        $link3 = $fieldset->addField('link_open_in', 'select', array(
            'name'      => 'link_open_in',
            'label'     => Mage::helper('dynamicslideshow')->__('Link Open In'),
            'title'     => Mage::helper('dynamicslideshow')->__('Link Open In'),
            'note'      => Mage::helper('dynamicslideshow')->__('The target of the slide link'),
            'values'    => $slides->getOptLinkTarget()
        ));
        $link4 = $fieldset->addField('slide_link', 'select', array(
            'name'      => 'slide_link',
            'label'     => Mage::helper('dynamicslideshow')->__('Link To Slide'),
            'title'     => Mage::helper('dynamicslideshow')->__('Link To Slide'),
            'values'    => $sliders->getOptLinkSlide(array($slides ? $slides->getId() : 0))
        ));
        $link5 = $fieldset->addField('link_pos', 'select', array(
            'name'      => 'link_pos',
            'label'     => Mage::helper('dynamicslideshow')->__('Link Position'),
            'title'     => Mage::helper('dynamicslideshow')->__('Link Position'),
            'values'    => $slides->getOptLinkPosition()
        ));
        $thumb = $fieldset->addField('slide_thumb', 'text', array(
            'name'      => 'slide_thumb',
            'label'     => Mage::helper('dynamicslideshow')->__('Thumbnail'),
            'title'     => Mage::helper('dynamicslideshow')->__('Thumbnail'),
            'note'      => Mage::helper('dynamicslideshow')->__('Slide Thumbnail. If not set - it will be taken from the slide image')
        ));
        $form->getElement('slide_thumb')->setRenderer(
            $this->getLayout()->createBlock('dynamicslideshow/adminhtml_widget_form_browsers', '', array(
                'element' => $thumb
            ))
        );
        $fieldset2 = $form->addFieldset('layers_fieldset', array(
            'legend'    => Mage::helper('dynamicslideshow')->__('Slide Image and Layers')
        ));
		
        $bg = $fieldset2->addField('background_type', 'select', array(
            'name'      => 'background_type',
            'label'     => Mage::helper('dynamicslideshow')->__('Background Type'),
            'title'     => Mage::helper('dynamicslideshow')->__('Background Type'),
            'values'    => $slides->getOptBackgroundType(),
            'value'     => $slides->getBgType() ? $slides->getBgType() : 'trans',
            'onchange'  => 'DnmSl.updateContainer()'
        ));
        $bg1 = $fieldset2->addField('image_url', 'text', array(
            'name'      => 'image_url',
            'label'     => Mage::helper('dynamicslideshow')->__('Background Image'),
            'title'     => Mage::helper('dynamicslideshow')->__('Background Image'),
            'onchange'  => 'DnmSl.updateContainer()',
            'readonly'  => true
        ));
        $form->getElement('image_url')->setRenderer(
            $this->getLayout()->createBlock('dynamicslideshow/adminhtml_widget_form_browsers', '', array(
                'element' => $bg1
            ))
        );
        $bg2 = $fieldset2->addField('slide_bg_color', 'text', array(
            'name'      => 'slide_bg_color',
            'label'     => Mage::helper('dynamicslideshow')->__('Background Color'),
            'title'     => Mage::helper('dynamicslideshow')->__('Background Color'),
            'class'     => 'color',
            'onchange'  => 'DnmSl.updateContainer()'
        ));
        $bg3 = $fieldset2->addField('bg_external', 'text', array(
            'name'      => 'bg_external',
            'label'     => Mage::helper('dynamicslideshow')->__('External URL'),
            'title'     => Mage::helper('dynamicslideshow')->__('External URL')
        ));
        $form->getElement('bg_external')->setRenderer(
            $this->getLayout()->createBlock('dynamicslideshow/adminhtml_widget_form_url')
        );
        $bg4 = $fieldset2->addField('bg_fit', 'select', array(
            'name'      => 'bg_fit',
            'label'     => Mage::helper('dynamicslideshow')->__('Background Fit'),
            'title'     => Mage::helper('dynamicslideshow')->__('Background Fit'),
            'values'    => $slides->getOptBackgroundSize(),
            'value'     => $slides->getBgFit() ? $slides->getBgFit() : 'cover',
            'onchange'  => 'DnmSl.updateContainer()'
        ));
        $bg41 = $fieldset2->addField('bg_fit_x', 'text', array(
            'name'      => 'bg_fit_x',
            'label'     => Mage::helper('dynamicslideshow')->__('Background Fit X'),
            'title'     => Mage::helper('dynamicslideshow')->__('Background Fit X'),
            'onchange'  => 'DnmSl.updateContainer()'
        ));
        $bg42 = $fieldset2->addField('bg_fit_y', 'text', array(
            'name'      => 'bg_fit_y',
            'label'     => Mage::helper('dynamicslideshow')->__('Background Fit Y'),
            'title'     => Mage::helper('dynamicslideshow')->__('Background Fit Y'),
            'onchange'  => 'DnmSl.updateContainer()'
        ));
		
        $fieldset2->addField('bg_repeat', 'select', array(
            'name'      => 'bg_repeat',
            'label'     => Mage::helper('dynamicslideshow')->__('Background Repeat'),
            'title'     => Mage::helper('dynamicslideshow')->__('Background Repeat'),
            'values'    => $sliders->getOptBgrRepeat(),
            'onchange'  => 'DnmSl.updateContainer()'
        ));
		
        $bg5 = $fieldset2->addField('bg_position', 'select', array(
            'name'      => 'bg_position',
            'label'     => Mage::helper('dynamicslideshow')->__('Background Position'),
            'title'     => Mage::helper('dynamicslideshow')->__('Background Position'),
            'values'    => $slides->getOptBackgroundPosition(),
            'value'     => $slides->getBgPosition() ? $slides->getBgPosition() : 'left top',
            'onchange'  => 'DnmSl.updateContainer()'
        ));
        $bg51 = $fieldset2->addField('bg_position_x', 'text', array(
            'name'      => 'bg_position_x',
            'label'     => Mage::helper('dynamicslideshow')->__('Background Position X'),
            'title'     => Mage::helper('dynamicslideshow')->__('Background Position X'),
            'onchange'  => 'DnmSl.updateContainer()'
        ));
        $bg52 = $fieldset2->addField('bg_position_y', 'text', array(
            'name'      => 'bg_position_y',
            'label'     => Mage::helper('dynamicslideshow')->__('Background Position Y'),
            'title'     => Mage::helper('dynamicslideshow')->__('Background Position Y'),
            'onchange'  => 'DnmSl.updateContainer()'
        ));
        $bg6 = $fieldset2->addField('kenburn_effect', 'select', array(
            'name'      => 'kenburn_effect',
            'label'     => Mage::helper('dynamicslideshow')->__('Ken Burns / Pan Zoom Settings'),
            'values'    => $sliders->getOptYesNo()
        ));
        
        $bg62 = $fieldset2->addField('kb_start_fit', 'text', array(
            'name'      => 'kb_start_fit',
            'label'     => Mage::helper('dynamicslideshow')->__('Start Fit: (in %)'),
            'value'     => $slides->getKbStartFit() ? $slides->getKbStartFit() : '100',
        ));
        $bg63 = $fieldset2->addField('bg_end_position', 'select', array(
            'name'      => 'bg_end_position',
            'label'     => Mage::helper('dynamicslideshow')->__('Background End Position'),
            'values'    => $slides->getOptBackgroundPosition(),
            'value'     => $slides->getBgEndPosition() ? $slides->getBgEndPosition() : 'center top',
        ));
        $bg631 = $fieldset2->addField('bg_end_position_x', 'text', array(
            'name'      => 'bg_end_position_x',
            'label'     => Mage::helper('dynamicslideshow')->__('Background End Position X')
        ));
        $bg632 = $fieldset2->addField('bg_end_position_y', 'text', array(
            'name'      => 'bg_end_position_y',
            'label'     => Mage::helper('dynamicslideshow')->__('Background End Position Y')
        ));
        $bg64 = $fieldset2->addField('kb_end_fit', 'text', array(
            'name'      => 'kb_end_fit',
            'label'     => Mage::helper('dynamicslideshow')->__('End Fit: (in %)'),
            'value'     => $slides->getKbEndFit() ? $slides->getKbEndFit() : '100',
        ));
        $bg65 = $fieldset2->addField('kb_easing', 'select', array(
            'name'      => 'kb_easing',
            'label'     => Mage::helper('dynamicslideshow')->__('Easing'),
            'values'    => $slides->getOptLayerEase()
        ));
        $bg66 = $fieldset2->addField('kb_duration', 'text', array(
            'name'      => 'kb_duration',
            'label'     => Mage::helper('dynamicslideshow')->__('Duration (in ms)'),
            'value'     => $slides->getKbDuration() ? $slides->getKbDuration() : $sliders->getDelay()
        ));
		
        $fieldset2->addField('layers', 'text', array(
            'label'     => Mage::helper('dynamicslideshow')->__('Layers')
        ));
        $form->getElement('layers')->setRenderer(
            $this->getLayout()->createBlock('dynamicslideshow/adminhtml_widget_form_layers')
        );

        $container = $form->addFieldset('container_fieldset', array(
            'class'     => 'no-spacing'
        ));

        $left = $container->addFieldset('left_fieldset', array(
            'class'     => 'no-spacing'
        ));

        $fieldset3 = $left->addFieldset('layer_params_fieldset', array(
            'legend'    => Mage::helper('dynamicslideshow')->__('Layer General Parameters'),
            'class'     => 'collapsible'
        ));
        $fieldset3->addField('layer_style', 'select', array(
            'label'     => Mage::helper('dynamicslideshow')->__('Style'),
            'title'     => Mage::helper('dynamicslideshow')->__('Style'),
            'values'    => Mage::getModel('dynamicslideshow/css')->getCollection()->toOptionArray()
        ));

        $css = $fieldset3->addField('layer_css', 'text', array(
            'label'     => Mage::helper('dynamicslideshow')->__('CSS')
        ));
		
        $form->getElement('layer_css')->setRenderer(
            $this->getLayout()->createBlock('dynamicslideshow/adminhtml_widget_form_css', '', array(
                'element' => $css
            ))
        );
        $fieldset3->addField('layer_text', 'textarea', array(
            'label'     => Mage::helper('dynamicslideshow')->__('Text / Html'),
            'title'     => Mage::helper('dynamicslideshow')->__('Text / Html')
        ));
        $fieldset3->addField('layer_align', 'text', array(
            'label'     => Mage::helper('dynamicslideshow')->__('Align')
        ));
        $form->getElement('layer_align')->setRenderer(
            $this->getLayout()->createBlock('dynamicslideshow/adminhtml_widget_form_align')
        );
		
		$fieldset3->addField('layer_parallaxLevels', 'select', array(
            'label'     => Mage::helper('dynamicslideshow')->__('Parallax Levels'),
            'title'     => Mage::helper('dynamicslideshow')->__('Parallax Levels'),
            'values'    => $slides->getOptParallaxLevel()
        ));
		
        $fieldset3->addField('layer_left', 'text', array(
            'label'     => Mage::helper('dynamicslideshow')->__('Offset X'),
            'title'     => Mage::helper('dynamicslideshow')->__('Offset X'),
            'class'     => 'validate-number',
        ));
        $fieldset3->addField('layer_top', 'text', array(
            'label'     => Mage::helper('dynamicslideshow')->__('Offset Y'),
            'title'     => Mage::helper('dynamicslideshow')->__('Offset Y'),
            'class'     => 'validate-number',
        ));
		
		$fieldset3->addField('layer_whitespace','select', array(
			'label'		=> Mage::helper('dynamicslideshow')->__('White Space'),
			'title'		=> Mage::helper('dynamicslideshow')->__('White Space'),
			'values'    => $slides->getOptWhiteSpace(),
		));
		
		$fieldset3->addField('layer_max_width', 'text', array(
            'label'     => Mage::helper('dynamicslideshow')->__('Max Width'),
            'title'     => Mage::helper('dynamicslideshow')->__('Max Width'),
            'note'     => 'Example: 50px, 50%, auto',
			'value'		=> 'auto'
        ));
		
		$fieldset3->addField('layer_max_height', 'text', array(
            'label'     => Mage::helper('dynamicslideshow')->__('Max Height'),
            'title'     => Mage::helper('dynamicslideshow')->__('Max Height'),
            'note'     => 'Example: 50px, 50%, auto',
			'value'		=> 'auto'
        ));
		
        $fieldset3->addField('layer_proportional_scale', 'checkbox', array(
            'label'     => Mage::helper('dynamicslideshow')->__('Scale Proportional'),
            'title'     => Mage::helper('dynamicslideshow')->__('Scale Proportional'),
            'onchange'  => 'return DnmSl.setScale(true, this.checked)'
        ));
        $fieldset3->addField('layer_scaleX', 'text', array(
            'label'     => Mage::helper('dynamicslideshow')->__('Width'),
            'title'     => Mage::helper('dynamicslideshow')->__('Width'),
            'class'     => 'validate-number'
        ));
        $fieldset3->addField('layer_scaleY', 'text', array(
            'label'     => Mage::helper('dynamicslideshow')->__('Height'),
            'title'     => Mage::helper('dynamicslideshow')->__('Height'),
            'class'     => 'validate-number'
        ));

        $fieldset4 = $left->addFieldset('layer_animate_fieldset', array(
            'legend'    => Mage::helper('dynamicslideshow')->__('Layer Animation '),
            'class'     => 'collapsible'
        ));
        $fieldset4->addField('layer_animation_preview', 'text', array(
            'label'     => Mage::helper('dynamicslideshow')->__('Preview Transition'),
            'note'      => Mage::helper('dynamicslideshow')->__('Preview Transition (Start & End Time is ignored during demo)')
        ));
        $form->getElement('layer_animation_preview')->setRenderer(
            $this->getLayout()->createBlock('dynamicslideshow/adminhtml_widget_form_animation')
        );
        $fieldset4->addField('layer_animation', 'select', array(
            'label'     => Mage::helper('dynamicslideshow')->__('Animation'),
            'title'     => Mage::helper('dynamicslideshow')->__('Animation'),
            'values'    => $slides->getOptLayerEndAnimation()
        ));
        $fieldset4->addField('custom_animation', 'text', array());
        $form->getElement('custom_animation')->setRenderer(
            $this->getLayout()->createBlock('dynamicslideshow/adminhtml_widget_form_cinanimation')
        );
        $fieldset4->addField('layer_easing', 'select', array(
            'label'     => Mage::helper('dynamicslideshow')->__('Easing'),
            'title'     => Mage::helper('dynamicslideshow')->__('Easing'),
            'values'    => $slides->getOptLayerEase()
        ));
        $fieldset4->addField('layer_speed', 'text', array(
            'label'     => Mage::helper('dynamicslideshow')->__('Speed'),
            'title'     => Mage::helper('dynamicslideshow')->__('Speed'),
            'class'     => 'validate-number',
        ));
		
		 $fieldset4->addField('layer_split', 'select', array(
            'label'     => Mage::helper('dynamicslideshow')->__('Split Text Per'),
            'title'     => Mage::helper('dynamicslideshow')->__('Split Text Per'),
            'values'    => $slides->getOptLayerSplit()
        ));
		
		$fieldset4->addField('layer_splitdelay', 'text', array(
            'label'     => Mage::helper('dynamicslideshow')->__('Split Delay'),
            'title'     => Mage::helper('dynamicslideshow')->__('Split Delay'),
            'class'     => 'validate-number',
        ));
		
        $fieldset4->addField('layer_endanimation', 'select', array(
            'label'     => Mage::helper('dynamicslideshow')->__('End Animation'),
            'title'     => Mage::helper('dynamicslideshow')->__('End Animation'),
            'values'    => array('auto' => Mage::helper('dynamicslideshow')->__('Choose Automatic')) 
			+ $slides->getOptLayerEndAnimation()
        ));
		
        $fieldset4->addField('custom_endanimation', 'text', array());
        $form->getElement('custom_endanimation')->setRenderer(
            $this->getLayout()->createBlock('dynamicslideshow/adminhtml_widget_form_coutanimation')
        );
        $fieldset4->addField('layer_endeasing', 'select', array(
            'label'     => Mage::helper('dynamicslideshow')->__('End Easing'),
            'title'     => Mage::helper('dynamicslideshow')->__('End Easing'),
            'values'    => array('nothing' => Mage::helper('dynamicslideshow')->__('No Change'))
			+ $slides->getOptLayerEase()
        ));
        $fieldset4->addField('layer_endspeed', 'text', array(
            'label'     => Mage::helper('dynamicslideshow')->__('End Speed'),
            'title'     => Mage::helper('dynamicslideshow')->__('End Speed'),
            'class'     => 'validate-number',
        ));
        $fieldset4->addField('layer_endtime', 'text', array(
            'label'     => Mage::helper('dynamicslideshow')->__('End Time'),
            'title'     => Mage::helper('dynamicslideshow')->__('End Time'),
            'class'     => 'validate-number',
        ));
		
		 $fieldset4->addField('layer_endsplit', 'select', array(
            'label'     => Mage::helper('dynamicslideshow')->__('Split Text Per'),
            'title'     => Mage::helper('dynamicslideshow')->__('Split Text Per'),
            'values'    => $slides->getOptLayerSplit()
        ));
		
		$fieldset4->addField('layer_endsplitdelay', 'text', array(
            'label'     => Mage::helper('dynamicslideshow')->__('Split Delay'),
            'title'     => Mage::helper('dynamicslideshow')->__('Split Delay'),
            'class'     => 'validate-number',
        ));

        $fieldset5 = $left->addFieldset('layer_advance_fieldset', array(
            'legend'    => Mage::helper('dynamicslideshow')->__('Layer Links & Advanced Params '),
            'class'     => 'collapsible'
        ));
        $layerLink = $fieldset5->addField('layer_link_enable', 'select', array(
            'label'     => Mage::helper('dynamicslideshow')->__('Enable Link'),
            'title'     => Mage::helper('dynamicslideshow')->__('Enable Link'),
            'values'    => $sliders->getOptYesNo(),
			'value'    =>  $slides->getData('layer_link_enable') ? $slides->getData('layer_link_enable') : 'no'
        ));
        $layerLink1 = $fieldset5->addField('layer_link_type', 'select', array(
            'label'     => Mage::helper('dynamicslideshow')->__('Link Type'),
            'title'     => Mage::helper('dynamicslideshow')->__('Link Type'),
            'values'    => $slides->getOptLinkType()
        ));
        $layerLink2 = $fieldset5->addField('layer_link', 'text', array(
            'label'     => Mage::helper('dynamicslideshow')->__('Slide Link'),
            'title'     => Mage::helper('dynamicslideshow')->__('Slide Link'),
            'note'      => Mage::helper('dynamicslideshow')->__('A link on the layer')
        ));
        $layerLink3 = $fieldset5->addField('layer_link_open_in', 'select', array(
            'label'     => Mage::helper('dynamicslideshow')->__('Link Open In'),
            'title'     => Mage::helper('dynamicslideshow')->__('Link Open In'),
            'note'      => Mage::helper('dynamicslideshow')->__('The target of the layer link'),
            'values'    => $slides->getOptLinkTarget()
        ));
        $layerLink4 = $fieldset5->addField('layer_link_slide', 'select', array(
            'label'     => Mage::helper('dynamicslideshow')->__('Link To Slide'),
            'title'     => Mage::helper('dynamicslideshow')->__('Link To Slide'),
            'values'    => $sliders->getOptLinkSlide(array($slides ? $slides->getId() : 0))
        ));
        $fieldset5->addField('layer_corner_left', 'select', array(
            'label'     => Mage::helper('dynamicslideshow')->__('Left Corner'),
            'title'     => Mage::helper('dynamicslideshow')->__('Left Corner'),
            'note'      => Mage::helper('dynamicslideshow')->__('Only with BG color'),
            'values'    => $slides->getOptCornor()
        ));
        $fieldset5->addField('layer_corner_right', 'select', array(
            'label'     => Mage::helper('dynamicslideshow')->__('Right Corner'),
            'title'     => Mage::helper('dynamicslideshow')->__('Right Corner'),
            'note'      => Mage::helper('dynamicslideshow')->__('Only with BG color'),
            'values'    => $slides->getOptCornor()
        ));
        $fieldset5->addField('layer_resizeme', 'checkbox', array(
            'label'     => Mage::helper('dynamicslideshow')->__('Responsive Through All Levels'),
			'title'     => Mage::helper('dynamicslideshow')->__('Responsive Through All Levels')
        ));
        $fieldset5->addField('layer_hiddenunder', 'checkbox', array(
            'label'     => Mage::helper('dynamicslideshow')->__('Hide Under Width'),
			'title'     => Mage::helper('dynamicslideshow')->__('Hide Under Width')
        ));
        $fieldset5->addField('layer_id', 'text', array(
            'label'     => Mage::helper('dynamicslideshow')->__('Layer ID'),
            'title'     => Mage::helper('dynamicslideshow')->__('Layer ID')
        ));
        $fieldset5->addField('layer_classes', 'text', array(
            'label'     => Mage::helper('dynamicslideshow')->__('Layer Classes'),
            'title'     => Mage::helper('dynamicslideshow')->__('Layer Classes')
        ));
        $fieldset5->addField('layer_title', 'text', array(
            'label'     => Mage::helper('dynamicslideshow')->__('Layer Title'),
            'title'     => Mage::helper('dynamicslideshow')->__('Layer Title')
        ));
        // $fieldset5->addField('layer_rel', 'text', array(
            // 'label'     => Mage::helper('dynamicslideshow')->__('Layer Rel'),
            // 'title'     => Mage::helper('dynamicslideshow')->__('Layer Rel')
        // ));
        $fieldset5->addField('layer_alt', 'text', array(
            'label'     => Mage::helper('dynamicslideshow')->__('Layer Alt Text'),
            'title'     => Mage::helper('dynamicslideshow')->__('Layer Alt Text')
        ));
        $right = $container->addFieldset('right_fieldset', array(
            'class'     => 'no-spacing'
        ));
        $fieldset6 = $right->addFieldset('layer_time_fieldset', array(
            'legend'    => Mage::helper('dynamicslideshow')->__('Layers Timing & Sorting')
        ));
        $fieldset6->setHeaderBar(
            $this->getLayout()->createBlock('adminhtml/widget_button', '', array(
                'label'     => "<i class='fa fa-list'></i>".Mage::helper('dynamicslideshow')->__('By Depth'),
				'title'     => Mage::helper('dynamicslideshow')->__('By Depth'),
                'type'      => 'button',
                'onclick'   => 'DnmSl.sortLayerItem(this,\'depth\')'
            ))->toHtml()
            .'&nbsp;'.
			
            $this->getLayout()->createBlock('adminhtml/widget_button', '', array(
                'label'     => "<i class='fa fa-clock-o'></i>".Mage::helper('dynamicslideshow')->__('By Time'),
				'title'     => Mage::helper('dynamicslideshow')->__('By Time'),
                'type'      => 'button',
                'class'     => 'normal',
                'onclick'   => 'DnmSl.sortLayerItem(this,\'time\')'
            ))->toHtml()
			 .'&nbsp;'.
            $this->getLayout()->createBlock('adminhtml/widget_button', '', array(
                'label'     => "<i class='fa fa-eye'></i>",
				'title'		=> Mage::helper('dynamicslideshow')->__('Hide All Layer'),
                'type'		=> 'button',
                'class'     => 'btn-hide-all normal ',
				'id'		=> 'button_sort_visibility',
                'onclick'   => 'DnmSl.setHideAll()'
            ))->toHtml()
        );
		
		$fieldset6->addField('list_sorting', 'text', array());
		$form->getElement('list_sorting')->setRenderer(
				$this->getLayout()->createBlock('dynamicslideshow/adminhtml_widget_form_sorting')
			);
       
        $this->setForm($form);
        if ($slides->getId()) $form->setValues($slides->getData());
        
        $this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
            ->addFieldMap($link->getHtmlId(), $link->getName())
            ->addFieldMap($link2->getHtmlId(), $link2->getName())
            ->addFieldMap($link3->getHtmlId(), $link3->getName())
            ->addFieldMap($link4->getHtmlId(), $link4->getName())
            ->addFieldMap($link1->getHtmlId(), $link1->getName())
            ->addFieldMap($link5->getHtmlId(), $link5->getName())
            ->addFieldMap($bg->getHtmlId(), $bg->getName())
            ->addFieldMap($bg1->getHtmlId(), $bg1->getName())
            ->addFieldMap($bg2->getHtmlId(), $bg2->getName())
            ->addFieldMap($bg3->getHtmlId(), $bg3->getName())
            ->addFieldMap($bg4->getHtmlId(), $bg4->getName())
            ->addFieldMap($bg41->getHtmlId(), $bg41->getName())
            ->addFieldMap($bg42->getHtmlId(), $bg42->getName())
            ->addFieldMap($bg5->getHtmlId(), $bg5->getName())
            ->addFieldMap($bg51->getHtmlId(), $bg51->getName())
            ->addFieldMap($bg52->getHtmlId(), $bg52->getName())
            ->addFieldMap($bg6->getHtmlId(), $bg6->getName())
            ->addFieldMap($bg62->getHtmlId(), $bg62->getName())
            ->addFieldMap($bg63->getHtmlId(), $bg63->getName())
            ->addFieldMap($bg631->getHtmlId(), $bg631->getName())
            ->addFieldMap($bg632->getHtmlId(), $bg632->getName())
            ->addFieldMap($bg64->getHtmlId(), $bg64->getName())
            ->addFieldMap($bg65->getHtmlId(), $bg65->getName())
            ->addFieldMap($bg66->getHtmlId(), $bg66->getName())
            ->addFieldMap($layerLink->getHtmlId(), 'layer_link_enable')
            ->addFieldMap($layerLink1->getHtmlId(), 'layer_link_type')
            ->addFieldMap($layerLink2->getHtmlId(), 'layer_link')
            ->addFieldMap($layerLink3->getHtmlId(), 'layer_link_target')
            ->addFieldMap($layerLink4->getHtmlId(), 'layer_link_slide')
            ->addFieldDependence($link1->getName(), $link->getName(), 'yes')
            ->addFieldDependence($link2->getName(), $link1->getName(), 'regular')
            ->addFieldDependence($link2->getName(), $link->getName(), 'yes')
            ->addFieldDependence($link3->getName(), $link1->getName(), 'regular')
            ->addFieldDependence($link3->getName(), $link->getName(), 'yes')
            ->addFieldDependence($link4->getName(), $link1->getName(), 'slide')
            ->addFieldDependence($link4->getName(), $link->getName(), 'yes')
            ->addFieldDependence($link5->getName(), $link->getName(), 'yes')
            ->addFieldDependence($bg1->getName(), $bg->getName(), 'image')
            ->addFieldDependence($bg2->getName(), $bg->getName(), 'solid')
            ->addFieldDependence($bg3->getName(), $bg->getName(), 'external')
            ->addFieldDependence($bg41->getName(), $bg4->getName(), 'percentage')
            ->addFieldDependence($bg42->getName(), $bg4->getName(), 'percentage')
            ->addFieldDependence($bg51->getName(), $bg5->getName(), 'percentage')
            ->addFieldDependence($bg52->getName(), $bg5->getName(), 'percentage')
            ->addFieldDependence($bg6->getName(), $bg->getName(), array('image', 'external'))
            ->addFieldDependence($bg62->getName(), $bg->getName(), array('image', 'external'))
            ->addFieldDependence($bg63->getName(), $bg->getName(), array('image', 'external'))
            ->addFieldDependence($bg631->getName(), $bg->getName(), array('image', 'external'))
            ->addFieldDependence($bg632->getName(), $bg->getName(), array('image', 'external'))
            ->addFieldDependence($bg64->getName(), $bg->getName(), array('image', 'external'))
            ->addFieldDependence($bg65->getName(), $bg->getName(), array('image', 'external'))
            ->addFieldDependence($bg66->getName(), $bg->getName(), array('image', 'external'))
            ->addFieldDependence($bg62->getName(), $bg6->getName(), 'yes')
            ->addFieldDependence($bg63->getName(), $bg6->getName(), 'yes')
            ->addFieldDependence($bg631->getName(), $bg6->getName(), 'yes')
            ->addFieldDependence($bg631->getName(), $bg63->getName(), 'percentage')
            ->addFieldDependence($bg632->getName(), $bg63->getName(), 'percentage')
            ->addFieldDependence($bg632->getName(), $bg6->getName(), 'yes')
            ->addFieldDependence($bg64->getName(), $bg6->getName(), 'yes')
            ->addFieldDependence($bg65->getName(), $bg6->getName(), 'yes')
            ->addFieldDependence($bg66->getName(), $bg6->getName(), 'yes')
            ->addFieldDependence('layer_link_type',     'layer_link_enable',    'yes')
            ->addFieldDependence('layer_link',          'layer_link_enable',    'yes')
            ->addFieldDependence('layer_link_slide',    'layer_link_enable',    'yes')
            ->addFieldDependence('layer_link_target',   'layer_link_enable',    'yes')
            ->addFieldDependence('layer_link',          'layer_link_type',      'regular')
            ->addFieldDependence('layer_link_target',   'layer_link_type',      'regular')
            ->addFieldDependence('layer_link_slide',    'layer_link_type',      'slide')
        );
        return parent::_prepareForm();
    }
    
}