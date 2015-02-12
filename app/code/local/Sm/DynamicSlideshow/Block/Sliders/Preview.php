<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */
 
class Sm_DynamicSlideshow_Block_Sliders_Preview extends Mage_Core_Block_Abstract implements Mage_Widget_Block_Interface
{
    protected $sliderHtmlId;
    protected $sliderHtmlIdWrapper;
    protected $numSlides;
    protected $customAnimations;
    
    protected function _construct()
    {
        parent::_construct();
	
    }
    
    protected function _prepareLayout()
    {
        $head = $this->getLayout()->getBlock( 'head' );
        $head->addItem( 'js_css', 'sm/dynamicslideshow/rs-plugin/css/settings.css' );
        $head->addLinkRel( 'stylesheet', $this->getUrl( 'dynamicslideshow/index/getCssCaptions' ) );
		if(Mage::app()->getRequest()->getActionName() == 'preview'){
			$head->addJs( 'sm/dynamicslideshow/js/jquery-1.11.1.min.js' );
			$head->addJs( 'sm/dynamicslideshow/js/jquery-noconflict.js' );
		}
        return parent::_prepareLayout();
    }
    
    protected function _toHtml()
    {
        
        $id     = $this->getData( 'id' );
        $slider = Mage::getModel( 'dynamicslideshow/sliders' )->load( $id );
        if ( $slider->getId() && $slider->getStatus() == 1 )
        {
            $dateFrom = $slider->getData( 'date_from' );
            $dateTo   = $slider->getData( 'date_to' );
            $date     = Mage::getModel( 'core/date' );
            if ( $dateFrom )
            {
                if ( $date->timestamp( $date->date( 'm/d/Y' ) ) < $date->timestamp( $dateFrom ) )
                    return;
            }
            if ( $dateTo )
            {
                if ( $date->timestamp( $date->date( 'm/d/Y' ) ) > $date->timestamp( $dateTo ) )
                    return;
            }
            
            $html = '';
            
            $scripts   = array();
			
			$incldue_jquery = $slider->getData('include_jquery');
			if(!defined('MAGENTECH_JQUERY') && $incldue_jquery == 'yes') {
				$scripts[] = Mage::getBaseUrl( 'js' ) . 'sm/dynamicslideshow/js/jquery-1.11.1.min.js';
				$scripts[] = Mage::getBaseUrl( 'js' ) . 'sm/dynamicslideshow/js/jquery-noconflict.js';
				define('MAGENTECH_JQUERY',1);
			}
            $scripts[] = Mage::getBaseUrl( 'js' ) . 'sm/dynamicslideshow/rs-plugin/js/jquery.themepunch.tools.min.js';
            $scripts[] = Mage::getBaseUrl( 'js' ) . 'sm/dynamicslideshow/rs-plugin/js/jquery.themepunch.revolution.min.js';
            
            $styles = array();
            foreach ( $scripts as $script )
            {
                $html .= "<script type='text/javascript' src='{$script}'></script>";
            }
            foreach ( $styles as $style )
            {
                $html .= "<link type='text/css' href='{$style}' rel='stylesheet'/>";
            }
            
            if ( $slider->getData( 'load_googlefont' ) == 'yes' )
            {
                $fonts = $slider->getData( 'google_font' );
                if ( is_array( $fonts ) )
                {
                    foreach ( $fonts as $font )
                    {
                        $html .= $this->getCleanFontImport( $font );
                    }
                }
                else
                    $html .= $this->getCleanFontImport( $fonts );
            }
            
            $bannerWidth               = $slider->getData( 'width' );
            $bannerHeight              = $slider->getData( 'height' );
            $this->sliderHtmlId        = "rev_slider_{$slider->getId()}";
            $this->sliderHtmlIdWrapper = "{$this->sliderHtmlId}_wrapper";
            $sliderPosition            = $slider->getData( 'position' );
            $sliderType                = $slider->getData( 'type' );
            $containerStyle            = '';
            if ( $sliderType != 'fullscreen' )
            {
                switch ( $sliderPosition )
                {
                    case 'center':
                    default:
                        $containerStyle .= 'margin:0px auto;';
                        break;
                    case 'left':
                        $containerStyle .= 'float:left;';
                        break;
                    case 'right':
                        $containerStyle .= 'float:right;';
                        break;
                }
            }
            
            if ( $backgrondColor = $slider->getData( 'background_color' ) )
            {
                $containerStyle .= "background-color:#{$backgrondColor};";
            }
           
            $containerStyle .= "padding:{$slider->getData('padding')}px;";
            if ( $sliderType != 'fullscreen' )
            {
                if ( $sliderPosition != 'center' )
                {
                    $containerStyle .= "margin-left:{$slider->getData('margin_left')}px;";
                    $containerStyle .= "margin-right:{$slider->getData('margin_right')}px;";
                }
                $containerStyle .= "margin-top:{$slider->getData('margin_top')}px;";
                $containerStyle .= "margin-bottom:{$slider->getData('margin_bottom')}px;";
            }
            
            $bannerStyle = 'display:none;';
            if ( $slider->getData( 'show_background_image' ) == 'yes' )
            {
                if ( $backgroundImage = $slider->getData( 'background_image' ) )
                {
                    
					$backgroundImage = (strpos($backgroundImage, 'http') === 0) ? $backgroundImage : Mage::getBaseUrl( 'media' ) . $backgroundImage;
					$bannerStyle .= sprintf( "background-image:url(%s);background-repeat:%s;background-size:%s;background-position:%s;", $backgroundImage, $slider->getData( 'bg_fit' ), $slider->getData( 'bg_repeat' ), $slider->getData( 'bg_position' ) );
                }
            }
            
            $sliderWrapperClass  = 'rev_slider_wrapper';
            $sliderClass         = 'rev_slider tp-banner';
            $putResponsiveStyles = false;
            switch ( $sliderType )
            {
                default:
                case 'fixed':
                    $bannerStyle .= "height:{$bannerHeight}px;width:{$bannerWidth}px;";
                    $containerStyle .= "position:relative;height:{$bannerHeight}px;width:{$bannerWidth}px;";
                    break;
                case 'responsive':
                    $putResponsiveStyles = true;
                    break;
                case 'fullwidth':
                    $sliderWrapperClass .= ' fullwidthbanner-container';
                    $sliderClass .= ' fullwidthabanner';
                    $bannerStyle .= "max-height:{$bannerHeight}px;height:{$bannerHeight}px;";
                    $containerStyle .= "max-height:{$bannerHeight}px;";
                    break;
                case 'fullscreen':
                    $sliderWrapperClass .= ' fullscreen-container';
                    $sliderClass .= ' fullscreenbanner';
                    break;
            }
            
            $htmlTimerBar = '';
			switch ( $slider->getData( 'show_timerbar' ) )
			{
				case 'top':
					$htmlTimerBar .= '<div class="tp-bannertimer"></div>';
					break;
				case 'bottom':
					$htmlTimerBar .= '<div class="tp-bannertimer tp-bottom"></div>';
					break;
				case 'hide':
					$htmlTimerBar = '<div class="tp-bannertimer tp-bottom" style="display:none; visibility: hidden !important;"></div>';
				break;	
			}
         
            if ( $slider->getData( 'padding_type' ) == 'inner' )
            {
                $sliderWrapperClass .= ' tp_inner_padding';
            }
            
            $output = '';
            if ( $putResponsiveStyles )
            {
                $output .= $this->renderResponsiveStyle( $slider );
            }
            
            $output .= $html;
            $output .= "<div id='{$this->sliderHtmlIdWrapper}' class='{$sliderWrapperClass}' style='{$containerStyle}'>";
            $output .= "<div id='{$this->sliderHtmlId}' class='{$sliderClass}' style='{$bannerStyle}'>";
            
            $output .= $this->renderSlides( $slider );
            
            $output .= $htmlTimerBar;
            $output .= "</div>";
            $output .= "</div>";
            $output .= $this->renderJs( $slider );
            return $output;
        }
    }
    
    protected function _getCustomAnimations()
    {
        if ( !$this->customAnimations )
        {
            $animations = array();
            $collection = Mage::getModel( 'dynamicslideshow/animation' )->getCollection();
            foreach ( $collection as $item )
            {
                $animations['custom-' . $item->getId()] = Mage::helper( 'core' )->jsonDecode( $item->getParams() );
            }
            $this->customAnimations = $animations;
        }
        return $this->customAnimations;
    }
    
    protected function _renderCustomAnimData( $data )
    {
        return sprintf( 'x:%d;y:%d;z:%d;rotationX:%d;rotationY:%d;rotationZ:%d;scaleX:%d;scaleY:%d;skewX:%d;skewY:%d;opacity:%d;transformPerspective:%d;transformOrigin:%s;', (int) $data['movex'], (int) $data['movey'], (int) $data['movez'], (int) $data['rotationx'], (int) $data['rotationy'], (int) $data['rotationz'], (int) $data['scalex'] / 100, (int) $data['scaley'] / 100, (int) $data['skewx'], (int) $data['skewy'], (int) $data['opacity'] / 100, (int) $data['perspective'], (int) $data['originx'] . '% ' . (int) $data['originy'] . '%' );
    }
    
    public function getCleanFontImport( $font )
    {
        if ( strpos( $font, "href=" ) === false )
        {
            return '<link href="http://fonts.googleapis.com/css?family=' . $font . '" rel="stylesheet" type="text/css" media="all" />';
        }
        else
        {
            return stripslashes( $font );
        }
    }
    
    public function renderResponsiveStyle( $slider )
    {
        $width    = (int) $slider->getData( 'width' );
        $height   = (int) $slider->getData( 'height' );
        $percent  = $height / $width;
        $w1       = (int) $slider->getData( 'responsitive_w1' );
        $w2       = (int) $slider->getData( 'responsitive_w2' );
        $w3       = (int) $slider->getData( 'responsitive_w3' );
        $w4       = (int) $slider->getData( 'responsitive_w4' );
        $w5       = (int) $slider->getData( 'responsitive_w5' );
        $w6       = (int) $slider->getData( 'responsitive_w6' );
        $sw1      = (int) $slider->getData( 'responsitive_sw1' );
        $sw2      = (int) $slider->getData( 'responsitive_sw2' );
        $sw3      = (int) $slider->getData( 'responsitive_sw3' );
        $sw4      = (int) $slider->getData( 'responsitive_sw4' );
        $sw5      = (int) $slider->getData( 'responsitive_sw5' );
        $sw6      = (int) $slider->getData( 'responsitive_sw6' );
        $arrItems = array();
        
        // add main item:
        $arr                 = array();
        $arr["maxWidth"]     = -1;
        $arr["minWidth"]     = $w1;
        $arr["sliderWidth"]  = $width;
        $arr["sliderHeight"] = $height;
        $arrItems[]          = $arr;
        
        //add item 1:
        if ( !empty( $w1 ) )
        {
            $arr                 = array();
            $arr["maxWidth"]     = $w1 - 1;
            $arr["minWidth"]     = $w2;
            $arr["sliderWidth"]  = $sw1;
            $arr["sliderHeight"] = floor( $sw1 * $percent );
            $arrItems[]          = $arr;
        }
        
        //add item 2:
        if ( !empty( $w2 ) )
        {
            $arr["maxWidth"]     = $w2 - 1;
            $arr["minWidth"]     = $w3;
            $arr["sliderWidth"]  = $sw2;
            $arr["sliderHeight"] = floor( $sw2 * $percent );
            $arrItems[]          = $arr;
        }
        
        //add item 3:
        if ( !empty( $w3 ) )
        {
            $arr["maxWidth"]     = $w3 - 1;
            $arr["minWidth"]     = $w4;
            $arr["sliderWidth"]  = $sw3;
            $arr["sliderHeight"] = floor( $sw3 * $percent );
            $arrItems[]          = $arr;
        }
        
        //add item 4:
        if ( !empty( $w4 ) )
        {
            $arr["maxWidth"]     = $w4 - 1;
            $arr["minWidth"]     = $w5;
            $arr["sliderWidth"]  = $sw4;
            $arr["sliderHeight"] = floor( $sw4 * $percent );
            $arrItems[]          = $arr;
        }
        
        //add item 5:
        if ( !empty( $w5 ) )
        {
            $arr["maxWidth"]     = $w5 - 1;
            $arr["minWidth"]     = $w6;
            $arr["sliderWidth"]  = $sw5;
            $arr["sliderHeight"] = floor( $sw5 * $percent );
            $arrItems[]          = $arr;
        }
        
        //add item 6:
        if ( !empty( $w6 ) )
        {
            $arr["maxWidth"]     = $w6 - 1;
            $arr["minWidth"]     = 0;
            $arr["sliderWidth"]  = $sw6;
            $arr["sliderHeight"] = floor( $sw6 * $percent );
            $arrItems[]          = $arr;
        }
        
        $output = "<style type='text/css'>";
        $output .= "#{$this->sliderHtmlId},#{$this->sliderHtmlIdWrapper}{width:{$width}px;height:{$height}px;}";
        foreach ( $arrItems as $item )
        {
            $strMaxWidth = '';
            if ( $item['maxWidth'] > 0 )
                $strMaxWidth = "and (max-width:{$item['maxWidth']}px)";
            $output .= "@media only screen and (min-width:{$item['minWidth']}px) {$strMaxWidth}{";
            $output .= "#{$this->sliderHtmlId},#{$this->sliderHtmlIdWrapper}{width:{$item['sliderWidth']}px;height:{$item['sliderHeight']}px;}";
            $output .= "}";
        }
        $output .= "</style>";
        return $output;
    }
    
    public function renderSlides( $slider )
    {
        $slides          = $slider->getAllSlides( true );
        $this->numSlides = count( $slides );
        $sDuration       = $slider->getData( 'delay' );
        if ( $slider && $this->numSlides )
        {
            $navigationType = $slider->getData( 'navigaion_type' );
            $isThumbsActive = ( $navigationType == 'thumb' );
            $index          = 0;
            $output         = "<ul>";
            foreach ( $slides as $slide )
            {
                $dateFrom = $slide->getData( 'date_from' );
                $dateTo   = $slide->getData( 'date_to' );
                $date     = Mage::getModel( 'core/date' );
                if ( $dateFrom )
                {
                    if ( $date->timestamp( $date->date( 'm/d/Y' ) ) < $date->timestamp( $dateFrom ) )
                        continue;
                }
                if ( $dateTo )
                {
                    if ( $date->timestamp( $date->date( 'm/d/Y' ) ) > $date->timestamp( $dateTo ) )
                        continue;
                }
                $transition = $slide->getData( 'slide_transition' );
                $slotAmount = $slide->getData( 'slot_amount' );
                
                $bgType = $slide->getData( 'background_type' );
                if ( $bgType != 'external' )
                {
                    $urlSlideImage = strpos( $slide->getData( 'image_url' ), 'http' ) === 0 ? $slide->getData( 'image_url' ) : Mage::getBaseUrl( 'media' ) . $slide->getData( 'image_url' );
                }
                else
                {
                    $urlSlideImage = $slide->getData( 'bg_external' );
                }
                
                $htmlThumb = '';
                if ( $isThumbsActive )
                {
                    if ( $urlThumb = $slide->getData( 'slide_thumb' ) )
                    {
                        $urlThumb  = strpos( $urlThumb, 'http' ) === 0 ? $urlThumb : Mage::getBaseUrl( 'media' ) . $urlThumb;
                        $htmlThumb = "data-thumb='{$urlThumb}'";
                    }
                    else
                    {
                        $htmlThumb = "data-thumb='{$urlSlideImage}'";
                    }
                }
                
                $htmlLink = '';
                if ( $slide->getData( 'enable_link' ) == 'yes' )
                {
                    switch ( $slide->getData( 'link_type' ) )
                    {
                        case 'regular':
                        default:
                            if ( $slide->getData( 'link_open_in' ) == 'new' )
                            {
                                $htmlLink .= "data-target='_blank' ";
                            }
                            $htmlLink .= "data-link='{$slide->getData('link')}' ";
                            break;
                        case 'slide':
                            $slideLink = $slide->getData( 'slide_link' );
                            if ( $slideLink && $slideLink != 'nothing' )
                            {
                                $htmlLink = "data-link='slide' data-linktoslide='{$slideLink}' ";
                            }
                            break;
                    }
                    if ( $slide->getData( 'link_pos' ) == 'back' )
                    {
                        $htmlLink .= "data-slideindex='back' ";
                    }
                }
                
                $htmlDelay = '';
                if ( is_numeric( $delay = $slide->getData( 'delay' ) ) )
                {
                    $htmlDelay .= "data-delay='{$delay}' ";
                }
                
                $htmlDuration = '';
                if ( is_numeric( $duration = $slide->getData( 'transition_duration' ) ) )
                {
                    $htmlDuration .= "data-masterspeed='{$duration}' ";
                }
                
                $htmlRotation = '';
                if ( $rotation = $slide->getData( 'transition_rotation' ) != 0 )
                {
                    $htmlRotation .= "data-rotate='" . ( $rotation < -720 ? -720 : ( $rotation > 720 && $rotation != 999 ? 720 : $rotation ) ) . "' ";
                }
                
                $htmlFirstTrans = '';
                $startWithSlide = (int) $slider->getData( 'start_with_slide' ) - 1;
                $startWithSlide = $startWithSlide < 0 ? 0 : ( $startWithSlide >= $this->numSlides ? 0 : $startWithSlide );
                if ( $index == $startWithSlide )
                {
                    if ( $slider->getData( 'first_transition_active' ) == 'on' )
                    {
                        $htmlFirstTrans .= " data-fstransition='{$slider->getData('first_transition_type')}' ";
                        $htmlFirstTrans .= " data-fsmasterspeed='{$slider->getData('first_transition_duration')}' ";
                        $htmlFirstTrans .= " data-fsslotamount='{$slider->getData('first_transition_slot_amount')}' ";
                    }
                }
				
				$htmlTitle = $slide->getData('title');
				$htmlTitle = " data-title='{$htmlTitle}' ";
                
                $htmlParams = $htmlTitle.$htmlDuration . $htmlLink . $htmlThumb . $htmlDelay . $htmlRotation . $htmlFirstTrans;
                $styleImage = '';
                switch ( $slide->getData( 'background_type' ) )
                {
                    case 'trans':
                        $urlSlideImage = Mage::getBaseUrl( 'js' ) . 'sm/dynamicslideshow/rs-plugin/images/transparent.png';
                        break;
                    case 'solid':
                        $urlSlideImage = Mage::getBaseUrl( 'js' ) . 'sm/dynamicslideshow/rs-plugin/images/transparent.png';
                        $styleImage .= "style='background-color:#{$slide->getData('slide_bg_color')}'";
                        break;
                }
                
                $imageAddParams = '';
                if ( $slider->getData( 'lazy_load' ) == 'on' )
                {
                    $imageAddParams .= "data-lazyload='{$urlSlideImage}' ";
                    $urlSlideImage = Mage::getBaseUrl( 'js' ) . 'sm/dynamicslideshow/rs-plugin/images/dummy.png';
                }
                
                $bgFit       = $slide->getData( 'bg_fit' );
                $bgFitX      = intval( $slide->getData( 'bg_fit_x' ) );
                $bgFitY      = intval( $slide->getData( 'bg_fit_y' ) );
                $bgPosition  = $slide->getData( 'bg_position' );
                $bgPositionX = intval( $slide->getData( 'bg_position_x' ) );
                $bgPositionY = intval( $slide->getData( 'bg_position_y' ) );
                $bgRepeat    = $slide->getData( 'bg_repeat' );
                
                if ( $bgPosition == 'percentage' )
                {
                    $imageAddParams .= "data-bgposition='{$bgPositionX}% {$bgPositionY}%' ";
                }
                else
                {
                    $imageAddParams .= "data-bgposition='{$bgPosition}' ";
                }
                
                $kb_pz          = '';
                $kenburn_effect = $slide->getData( 'kenburn_effect' );
                if ( $kenburn_effect == 'on' )
                {
                    $kb_duration  = $slide->getData( 'kb_duration' ) ? $slide->getData( 'kb_duration' ) : $sDuration;
                    $kb_ease      = $slide->getData( 'kb_easing' );
                    $kb_start_fit = $slide->getData( 'kb_start_fit' ) ? $slide->getData( 'kb_start_fit' ) : 100;
                    $kb_end_fit   = $slide->getData( 'kb_end_fit' ) ? $slide->getData( 'kb_end_fit' ) : 100;
                    if ( $bgType == 'image' || $bgType == 'external' )
                    {
                        $kb_pz .= " data-kenburns='on'";
                        $kb_pz .= " data-duration='{$kb_duration}'";
                        $kb_pz .= " data-ease='{$kb_ease}'";
                        $kb_pz .= " data-bgfit='{$kb_start_fit}'";
                        $kb_pz .= " data-bgfitend='{$kb_end_fit}'";
                        
                        $bgEndPosition = $slide->getData( 'bg_end_position' );
                        if ( $bgEndPosition == 'percentage' )
                        {
                            $bgEndPositionX = (int) $slide->getData( 'bg_end_position_x' );
                            $bgEndPositionY = (int) $slide->getData( 'bg_end_position_y' );
                            $kb_pz .= " data-bgpositionend='{$bgEndPositionX}% {$bgEndPositionY}%'";
                        }
                        else
                        {
                            $kb_pz .= " data-bgpositionend='{$bgEndPosition}'";
                        }
                    }
                }
                else
                {
                    if ( $bgFit == 'percentage' )
                    {
                        $imageAddParams .= " data-bgfit='{$bgFitX}% {$bgFitY}%'";
                    }
                    else
                    {
                        $imageAddParams .= " data-bgfit='{$bgFit}'";
                    }
                }
                $alt = $slide->getData('title');
				$alt = " alt='{$alt}' ";
                $imageAddParams .= " data-bgrepeat='{$bgRepeat}' ";
                $output .= "<li data-transition='{$transition}' data-slotamount='{$slotAmount}' {$htmlParams}>";
                $output .= "<img src='{$urlSlideImage}' {$styleImage} {$imageAddParams} {$kb_pz} {$alt}/>";
                $output .= $this->renderLayers( $slide );
                $output .= "</li>";
                $index++;
            }
            $output .= "</ul>";
        }
        else
        {
            $output = '<div class="no-slides-text">';
            $output .= $this->__( 'No slides found, please add some slides' );
            $output .= '</div>';
        }
        
        return $output;
    }
    
    public function getHtml5LayerHtml( $layer )
    {
        $data       = $layer->getData( 'video_data' );
        $ids        = $layer->getData( 'id' );
        $ids        = $ids ? " id='{$ids}' " : '';
        $classes    = $layer->getData( 'classes' );
        $classes    = $classes ? " {$classes} " : '';
        $title      = $layer->getData( 'title' );
        $title      = $title ? " title='{$title}' " : '';
        $rel        = $layer->getData( 'rel' );
        $rel        = $rel ? " rel='{$rel}' " : '';
        $urlPoster  = $data['urlPoster'];
        $urlMp4     = $data['urlMp4'];
        $urlWebm    = $data['urlWebm'];
        $urlOgv     = $data['urlOgv'];
        $fullwidth  = $data['fullwidth'];
        $videoloop  = $data['videoloop'];
        $videoloop  = $videoloop ? ' loop ' : '';
        $control    = $data['control'];
        $control    = $control ? ' controls ' : '';
        $width      = $fullwidth == 1 ? '100%' : $data['width'];
        $height     = $fullwidth == 1 ? '100%' : $data['height'];
        $htmlPoster = $urlPoster ? "poster='{$urlPoster}'" : '';
        $htmlMp4    = $urlMp4 ? "<source src='{$urlMp4}' type='video/mp4'/>" : '';
        $htmlWebm   = $urlWebm ? "<source src='{$urlWebm}' type='video/webm'/>" : '';
        $htmlOgv    = $urlOgv ? "<source src='{$urlOgv}' type='video/ogg'/>" : '';
        $html       = "<video class='video-js vjs-default-skin {$classes}' {$ids} {$title} {$rel} {$videoloop} {$control} preload='none' width='{$width}' height='{$height}' {$htmlPoster} data-setup='{}'>";
        $html .= $htmlMp4;
        $html .= $htmlWebm;
        $html .= $htmlOgv;
        $html .= '</video>';
        return $html;
    }
    
    public function is_ssl()
    {
        if ( isset( $_SERVER['HTTPS'] ) )
        {
            if ( 'on' == strtolower( $_SERVER['HTTPS'] ) )
                return true;
            if ( '1' == $_SERVER['HTTPS'] )
                return true;
        }
        elseif ( isset( $_SERVER['SERVER_PORT'] ) && '443' == $_SERVER['SERVER_PORT'] )
        {
            return true;
        }
        return false;
    }
    
    public function renderLayers( $slide )
    {
        if ( !$slide->getLayers() )
            return '';
        $output           = '';
        $zIndex           = 2;
        $customAnimations = $this->_getCustomAnimations();
        foreach ( $slide->getLayers() as $layer )
        {
            $layer = new Varien_Object( $layer );
            
            $type = $layer->getData( 'type' );
            
            $class         = trim( $layer->getData( 'style' ) );
            $custom        = trim( $layer->getData( 'style_custom' ) );
            $animation     = trim( $layer->getData( 'animation' ) );
            $parallaxLevel = trim( $layer->getData( 'parallaxLevels' ) );
            
            if ( $animation == 'fade' )
                $animation = 'tp-fade';
            
            $customin = '';
            if ( array_key_exists( $animation, $customAnimations ) )
            {
                $customAnimData = $this->_renderCustomAnimData( $customAnimations[$animation] );
                $customin       = "data-customin='{$customAnimData}' ";
                $animation      = 'customin';
            }
            if ( strpos( $animation, 'custom-' ) !== false )
                $animation = "tp-fade";
            
            $oClass = "tp-caption {$class} {$animation} {$custom} {$parallaxLevel}";
            $left   = $layer->getData( 'left' );
            $top    = $layer->getData( 'top' );
            $speed  = $layer->getData( 'speed' );
            $time   = $layer->getData( 'time' );
            $easing = $layer->getData( 'easing' );
            $text   = $layer->getData( 'text' );
            
            $ids     = $layer->getData( 'id' );
            $ids     = $ids ? " id='{$ids}' " : '';
            $classes = $layer->getData( 'classes' );
            $oClass .= $classes ? " {$classes} " : '';
            $title = $layer->getData( 'title' );
            $title = $title ? " title='{$title}' " : '';
            $rel   = $layer->getData( 'rel' );
            $rel   = $rel ? " rel='{$rel}' " : '';
            
            $html           = '';
            $htmlVideo      = '';
            $videoFullWidth = false;
            switch ( $type )
            {
                case 'text':
                default:
                    if ( $layer->getData( 'link_enable' ) == 'yes' )
                    {
					
                        $link = $layer->getData( 'link' );
                        if ( $layer->getData( 'link_open_in' ) == 'new' )
                        {
                            $html = "<a href='{$link}' target='_blank'>{$text}</a>";
                        }
                        else
                        {
                            $html = "<a href='{$link}'>{$text}</a>";
							
                        }
                    }
                    else
                    {
                        $html = $text;
                    }
                    break;
                case 'image':
                    $urlImage = $layer->getData( 'image_url' );
                    $urlImage = strpos( $urlImage, 'http' ) === 0 ? $urlImage : Mage::getBaseUrl( 'media' ) . $urlImage;
                    $alt      = $layer->getData( 'alt' ) ? $layer->getData( 'alt' ) : 'Image' ;
                    
                    $additional = '';
                    $scaleX     = $layer->getData( 'scaleX' );
                    $scaleY     = $layer->getData( 'scaleY' );
                    $additional .= $scaleX ? " data-ww='{$scaleX}' " : '';
                    $additional .= $scaleY ? " data-hh='{$scaleY}' " : '';
                    if ( $this->is_ssl() )
                    {
                        $urlImage = str_replace( "http://", "https://", $urlImage );
                    }
                    
                    $html = "<img src='{$urlImage}' alt='{$alt}' {$additional}/>";
                    if ( $layer->getData( 'link_enable' ) == 'yes' )
                    {
                        if ( $layer->getData( 'link_type' ) == 'regular' )
                        {
                            $link = $layer->getData( 'link' );
                            if ( $layer->getData( 'link_open_in' ) == 'new' )
                            {
                                $html = "<a href='{$link}' target='_blank'>{$html}</a>";
                            }
                            else
                            {
                                $html = "<a href='{$link}'>{$html}</a>";
                            }
                        }
                    }
                    break;
                case 'video':
                    $videoType      = $layer->getData( 'video_type' );
                    $videoId        = $layer->getData( 'video_id' );
                    $videoW         = $layer->getData( 'video_width' );
                    $videoH         = $layer->getData( 'video_height' );
                    $videoData      = $layer->getData( 'video_data' );
                    $videoArgs      = $videoData['args'];
                    $rewind         = $videoData['force_rewind'];
                    $videoFullWidth = $videoData['fullwidth'];
					$videoControls  =  $videoData['control'];
                    if ( $videoFullWidth == 1 )
                    {
                        $videoH = '100%';
                        $videoW = '100%';
                    }
                    $setBase = (strpos($_SERVER['REQUEST_SCHEME'], 'https' ) !== false ) ? "https://" : "http://";
                    switch ( $videoType )
                    {
                        case 'youtube':
                           if ( empty( $videoArgs ) )
                                $videoArgs = 'hd=1&amp;wmode=opaque&amp;controls=1&amp;showinfo=0;rel=0;';
								
							if ( strpos( $videoId, 'http' ) !== false )
                            {
                                //we have full URL, split it to ID
                                parse_str( parse_url( $videoId, PHP_URL_QUERY ), $my_v_ret );
                                $videoId = $my_v_ret[ 'v' ];
                            }	
							$ytBase = 'https://';
							//$videoArgs .= ';origin=' . $setBase . $_SERVER[ 'SERVER_NAME' ] . ';';
							if ( $videoControls )
                                $videoArgs =  (strpos( $videoArgs, 'controls=1' ) !== false ) ? str_replace('controls=1','controls=0',$videoArgs) : $videoArgs;
							  
							 $html = "<iframe src='" . $ytBase . "www.youtube.com/embed/" . $videoId . "?enablejsapi=1&amp;html5=1&amp;" . $videoArgs . "' width='" . $videoW . "' height='" . $videoH . "' style='width:" . $videoW . "px;height:" . $videoH . "px;'></iframe>";
                            break;
                        case 'vimeo':
                            if ( !$videoArgs )
                                $videoArgs = 'title=0&amp;byline=0&amp;portrait=0;api=1;';
							if ( strpos( $videoId, 'http' ) !== false )
                            {
                                //we have full URL, split it to ID
                                $videoId = (int) substr( parse_url( $videoId, PHP_URL_PATH ), 1 );
                            }	
							$html = "<iframe src='" . $setBase . "player.vimeo.com/video/" . $videoId . "?" . $videoArgs . "' width='" . $videoW . "' height='" . $videoH . "' style='width:" . $videoW . "px;height:" . $videoH . "px;'></iframe>";
							
                            break;
                        case 'html5':
                            $html = $this->getHtml5LayerHtml( $layer );
                            break;
                    }
                    if ( $videoData['autoplay'] == 1 )
                        $htmlVideo .= " data-autoplay='true' ";
                    if ( $videoData['nextslide'] == 1 )
                        $htmlVideo .= " data-nextslideatend='true' ";
                    if ( $videoData['autoplayonlyfirsttime'] == 1 )
                        $htmlVideo .= " data-autoplayonlyfirsttime='true' ";
                    if ( $videoData['force_rewind'] == 1 )
                        $htmlVideo .= " data-forcerewind='on' ";
                    if ( $videoData['mute'] == 1 )
                        $htmlVideo .= " data-volume='mute' ";
                    break;
                    
            }
            
            $endTime = $layer->getData( 'endtime' );
            
            $htmlEnd = '';
            if ( $endTime )
            {
                $htmlEnd .= "data-end='{$endTime}' ";
            }
            if ( $endSpeed = $layer->getData( 'endspeed' ) )
            {
                $htmlEnd .= "data-endspeed='{$endSpeed}' ";
            }
            if ( $endEasing = $layer->getData( 'endeasing' ) )
            {
                if ( $endEasing != 'nothing' )
                    $htmlEnd .= "data-endeasing='{$endEasing}' ";
            }
            $customout = '';
            if ( $endAnimation = $layer->getData( 'endanimation' ) )
            {
                if ( $endAnimation == 'fade' )
                    $endAnimation = 'tp-fade';
                
                if ( array_key_exists( $endAnimation, $customAnimations ) )
                {
                    $customEndAnimData = $this->_renderCustomAnimData( $customAnimations[$endAnimation] );
                    $customout         = "data-customout='{$customEndAnimData}' ";
                    $endAnimation      = 'customout';
                }
                
                if ( strpos( $endAnimation, 'custom-' ) !== false )
                    $endAnimation = "";
                if ( $endAnimation != 'auto' )
                    $oClass .= " {$endAnimation} ";
            }
            
            $htmlLink = '';
            if ( $layer->getData( 'link_enable' ) == 'yes' )
            {
             
			   $slideLink = $layer->getData( 'link_slide' );
                if ( $slideLink && $slideLink != 'nothing' && $slideLink != 'scroll_under' )
                {
                    $htmlLink .= "data-linktoslide='{$slideLink}' ";
                }
                if ( $slideLink == 'scroll_under' )
                {
                    $oClass .= ' tp-scrollbelowslider';
                    if ( $scrollUnderOffset = $layer->getData( 'scrollunder_offset' ) )
                    {
                        $htmlLink .= "data-scrolloffset='{$scrollUnderOffset}' ";
                    }
                }
				
            }
            
            $htmlHidden  = '';
            $layerHidden = $layer->getData( 'hiddenunder' );
            if ( $layerHidden == true || $layerHidden == '1' )
            {
			  $htmlHidden .= "data-captionhidden='on' ";
            }
			
            
            $htmlParams = $htmlEnd . $htmlLink . $htmlVideo . $htmlHidden . $customin . $customout;
            
            $alignHor  = $layer->getData( 'align_hor' );
            $alignVert = $layer->getData( 'align_vert' );
            $htmlPosX  = '';
            $htmlPosY  = '';
            switch ( $alignHor )
            {
                case 'left':
                default:
                    $htmlPosX .= "data-x='{$left}' ";
                    break;
                case 'center':
                    $htmlPosX .= "data-x='center' data-hoffset='{$left}' ";
                    break;
                case 'right':
                    $left = (int) $left * -1;
                    $htmlPosX .= "data-x='right' data-hoffset='{$left}' ";
                    break;
            }
            switch ( $alignVert )
            {
                case 'top':
                default:
                    $htmlPosY .= "data-y='{$top}' ";
                    break;
                case 'middle':
                    $htmlPosY .= "data-y='center' data-voffset='{$top}' ";
                    break;
                case 'bottom':
                    $top = (int) $top * -1;
                    $htmlPosY .= "data-y='bottom' data-voffset='{$top}' ";
                    break;
            }
            
            $htmlCorner = '';
            if ( $type == 'text' )
            {
                $cLeft  = $layer->getData( 'corner_left' );
                $cRight = $layer->getData( 'corner_right' );
                switch ( $cLeft )
                {
                    case 'curved':
                        $htmlCorner .= "<div class='frontcorner'></div>";
                        break;
                    case 'reversed':
                        $htmlCorner .= "<div class='frontcornertop'></div>";
                        break;
                }
                switch ( $cRight )
                {
                    case 'curved':
                        $htmlCorner .= "<div class='backcorner'></div>";
                        break;
                    case 'reversed':
                        $htmlCorner .= "<div class='backcornertop'></div>";
                        break;
                }
                if ( $layer->getData( 'resizeme' ) == true )
                {
                    $oClass .= ' tp-resizeme ';
                }
            }
            
            if ( $videoFullWidth == 1 )
            {
                $htmlPosY = "data-y='0'";
                $htmlPosX = "data-x='0'";
                $oClass .= ' fullscreenvideo';
            }
            
            $output .= "<div {$ids}  {$title} class='{$oClass}' {$htmlPosX} {$htmlPosY} data-speed='{$speed}' data-start='{$time}' data-easing='{$easing}' {$htmlParams} style='z-index:{$zIndex};'>";
            $output .= $html;
            $output .= $htmlCorner;
            $output .= "</div>";
            $zIndex++;
        }
        
        return $output;
    }
    
    public function renderJs( $slider )
    {
        $delay       = (int) $slider->getData( 'delay' );
        $startwidth  = (int) $slider->getData( 'width' );
        $startheight = (int) $slider->getData( 'height' );
        $type        = $slider->getData( 'type' );
        
        $fullWidth  = $type == 'fullwidth' ? 'on' : 'off';
        $fullScreen = 'off';
        if ( $type == 'fullscreen' )
        {
            $fullScreen = 'on';
            $fullWidth  = 'off';
        }
        
        $thumbAmount = (int) $slider->getData( 'thumb_amount' );
        $thumbWidth  = (int) $slider->getData( 'thumb_width' );
        $thumbHeight = (int) $slider->getData( 'thumb_height' );
        if ( $thumbAmount > $this->numSlides )
		{
            $thumbAmount = $this->numSlides;
        }
		if ( $thumbAmount <= 0 )
		{
			$thumbAmount = 1;
		}
	    $bannerWidth               = $slider->getData( 'width' );
		$thumbWidth = ($type = 'fixed') ?  floor($bannerWidth/$thumbAmount) : $thumbWidth;
		
        $stopSlider    = $slider->getData( 'stop_slider' );
		$stopSlider = ($stopSlider == 'yes') ? 'on' : 'off';
        $stopAfterLoop = (int) $slider->getData( 'stop_after_loops' );
        $stopAtSlide   = (int) $slider->getData( 'stop_at_slide' );
        if ( $stopSlider == 'off' )
        {
            $stopAfterLoop = -1;
            $stopAtSlide   = -1;
        }
        
        $hideThumb = (int) $slider->getData( 'hide_thumbs' );
        $hideThumb = $hideThumb < 10 ? 10 : $hideThumb;
        
        $alwayOn = $slider->getData( 'navigaion_always_on' );
        if ( $alwayOn == 'yes' )
            $hideThumb = 0;
        
        $hideSliderAtLimit = (int) $slider->getData( 'hide_slider_under' );
        if ( !empty( $hideSliderAtLimit ) )
            $hideSliderAtLimit++;
        if ( $type == 'fullwidth' )
            $hideSliderAtLimit = 0;
        
        $hideCaptionAtLimit = (int) $slider->getData( 'hide_defined_layers_under' );
        if ( !empty( $hideCaptionAtLimit ) )
            $hideCaptionAtLimit++;
        
        $hideAllCaptionAtLimit = (int) $slider->getData( 'hide_all_layers_under' );
        if ( !empty( $hideAllCaptionAtLimit ) )
            $hideAllCaptionAtLimit++;
        
        $startWithSlide = (int) $slider->getData( 'start_with_slide' ) - 1;
        $startWithSlide = $startWithSlide < 0 ? 0 : ( $startWithSlide >= $this->numSlides ? 0 : $startWithSlide );
        
        $arrowType = $slider->getData( 'navigation_arrows' );
        $hideThumbsOnMobile        = $slider->getData( 'hide_thumbs_on_mobile' );
        $hideBulletsOnMobile       = $slider->getData( 'hide_bullets_on_mobile' );
        $hideArrowsOnMobile        = $slider->getData( 'hide_arrows_on_mobile' );
        $hideThumbsUnderResolution = (int) $slider->getData( 'hide_thumbs_under_resolution' );
        
        $videoJsUrl = Mage::getBaseUrl( 'js' ) . 'sm/dynamicslideshow/rs-plugin/videojs/';
        $shadow     = (int) $slider->getData( "shadow_type" );
        
        $autoHeight           = $type == 'fullwidth' ? $slider->getData( 'auto_height' ) : 'off';
        $forceFullWidth       = $type == 'fullwidth' || $type == 'fullscreen' ? $slider->getData( 'force_full_width' ) : 'off';
        $fullScreenAlignForce = $type == 'fullscreen' ? $slider->getData( 'full_screen_align_force' ) : 'off';
        $minFullScreenHeight  = $type == 'fullscreen' ? (int) $slider->getData( 'fullscreen_min_height' ) : 0;
        $dottedOverlay        = $slider->getData( 'background_dotted_overlay' ) ? $slider->getData( 'background_dotted_overlay' ) : 'none';
        
        $soloArrowLeftHOffset = (int) $slider->getData( "leftarrow_offset_hor" );
        $soloArrowLeftVOffset = (int) $slider->getData( "leftarrow_offset_vert" );
        
        $soloArrowRightHOffset = (int) $slider->getData( "rightarrow_offset_hor" );
        $soloArrowRightVOffset = (int) $slider->getData( "rightarrow_offset_vert" );
        
        $navigationHOffset   = (int) $slider->getData( "navigaion_offset_hor" );
        $navigationVOffset   = (int) $slider->getData( "navigaion_offset_vert" );
        $swipe_velocity      = $slider->getData( 'swipe_velocity' ) ? $slider->getData( 'swipe_velocity' ) : 0.7;
        $swipe_min_touches   = $slider->getData( 'swipe_min_touches' ) ? $slider->getData( 'swipe_min_touches' ) : 1;
        $swipe_max_touches   = $slider->getData( 'swipe_max_touches' ) ? $slider->getData( 'swipe_max_touches' ) : 1;
        $drag_block_vertical = $slider->getData( 'drag_block_vertical' ) ? $slider->getData( 'drag_block_vertical' ) : 'false';
        $parallax            = $slider->getData( 'parallax' ) ? $slider->getData( 'parallax' ) : 'mouse';
        $parallaxBgFreeze    = $slider->getData( 'parallaxBgFreeze' ) ? $slider->getData( 'parallaxBgFreeze' ) : 'on';
        $parallaxDisableOnMobile =  $slider->getData('parallaxDisableOnMobile') ? $slider->getData('parallaxDisableOnMobile') : 'off';
		return "<script type='text/javascript'>
        jQuery(document).ready(function(){
            jQuery('#{$this->sliderHtmlId}').show().revolution({
                dottedOverlay: '{$dottedOverlay}',
                delay: {$delay},
                startwidth: {$startwidth},
                startheight: {$startheight},

                hideThumbs: {$hideThumb},
                thumbWidth: {$thumbWidth},
                thumbHeight: {$thumbHeight},
                thumbAmount: {$thumbAmount},

                navigationType: '{$slider->getData("navigaion_type")}',
                navigationArrows: '{$arrowType}',
                navigationStyle: '{$slider->getData("navigation_style")}',

                touchenabled: '{$slider->getData("touch")}',
                onHoverStop: '{$slider->getData("pause")}',
				swipe_velocity: {$swipe_velocity},
				swipe_min_touches: {$swipe_min_touches},
				swipe_max_touches: {$swipe_max_touches},

				parallax:'{$parallax}',
				parallaxBgFreeze:'{$parallaxBgFreeze}',
				parallaxLevels:[10,20,30,40,50,60,70,80,90,100],
				parallaxDisableOnMobile:'{$parallaxDisableOnMobile}',
                navigationHAlign: '{$slider->getData("navigaion_align_hor")}',
                navigationVAlign: '{$slider->getData("navigaion_align_vert")}',
                navigationHOffset: {$navigationHOffset},
                navigationVOffset: {$navigationVOffset},
                soloArrowLeftHalign: '{$slider->getData("leftarrow_align_hor")}',
                soloArrowLeftValign: '{$slider->getData("leftarrow_align_vert")}',
                soloArrowLeftHOffset: {$soloArrowLeftHOffset},
                soloArrowLeftVOffset: {$soloArrowLeftVOffset},

                soloArrowRightHalign: '{$slider->getData("rightarrow_align_hor")}',
                soloArrowRightValign: '{$slider->getData("rightarrow_align_vert")}',
                soloArrowRightHOffset: {$soloArrowRightHOffset},
                soloArrowRightVOffset: {$soloArrowRightVOffset},

                shadow: {$shadow},
                fullWidth: '{$fullWidth}',
                fullScreen: '{$fullScreen}',

                stopLoop: '{$stopSlider}',
                stopAfterLoops: {$stopAfterLoop},
                stopAtSlide: {$stopAtSlide},

                shuffle: '{$slider->getData("shuffle")}',
                autoHeight: '{$autoHeight}',
                forceFullWidth: '{$forceFullWidth}',
                fullScreenAlignForce: '{$fullScreenAlignForce}',
                minFullScreenHeight: {$minFullScreenHeight},

                hideThumbsOnMobile: '{$hideThumbsOnMobile}',
                hideBulletsOnMobile: '{$hideBulletsOnMobile}',
                hideArrowsOnMobile: '{$hideArrowsOnMobile}',
                hideThumbsUnderResolution: {$hideThumbsUnderResolution},

                hideSliderAtLimit: {$hideSliderAtLimit},
                hideCaptionAtLimit: {$hideCaptionAtLimit},
                hideAllCaptionAtLilmit: {$hideAllCaptionAtLimit},
                startWithSlide: {$startWithSlide},
                videoJsPath: '{$videoJsUrl}',
                fullScreenOffsetContainer: '{$slider->getData("fullscreen_offset_container")}'
            });
        });
        </script>";
    }
}