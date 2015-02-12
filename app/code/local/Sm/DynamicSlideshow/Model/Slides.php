<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Model_Slides extends Mage_Core_Model_Abstract
{
    const LINK_REGULAR = 'regular';
    const LINK_SLIDE = 'slide';
    
    const TARGET_SAME = 'same';
    const TARGET_NEW = 'new';
    
    const POSITION_FRONT = 'front';
    const POSITION_BACK = 'back';
    
    const PUBLISHED = 'published';
    const UNPUBLISHED = 'unpublished';
    
    const BG_IMAGE = 'image';
    const BG_TRANS = 'trans';
    const BG_SOLID = 'solid';
    const BG_URL = 'external';
    
    const BG_COVER = 'cover';
    const BG_CONTAIN = 'contain';
    const BG_PERCENTAGE = 'percentage';
    const BG_NORMAL = 'normal';
    
    const BG_LT = 'left top';
    const BG_LC = 'left center';
    const BG_LB = 'left bottom';
    const BG_RT = 'right top';
    const BG_RC = 'right center';
    const BG_RB = 'right bottom';
    const BG_CT = 'center top';
    const BG_CR = 'center right';
    const BG_CB = 'center bottom';
    const BG_CC = 'center';
    const BG_PE = 'percentage';
    
    const EASE_1 = "Linear.easeNone";
    const EASE_2 = "Power0.easeIn";
    const EASE_3 = "Power0.easeInOut";
    const EASE_4 = "Power0.easeOut";
    const EASE_5 = "Power1.easeIn";
    const EASE_6 = "Power1.easeInOut";
    const EASE_7 = "Power1.easeOut";
    const EASE_8 = "Power2.easeIn";
    const EASE_9 = "Power2.easeInOut";
    const EASE_10 = "Power2.easeOut";
    const EASE_11 = "Power3.easeIn";
    const EASE_12 = "Power3.easeInOut";
    const EASE_13 = "Power3.easeOut";
    const EASE_14 = "Power4.easeIn";
    const EASE_15 = "Power4.easeInOut";
    const EASE_16 = "Power4.easeOut";
    const EASE_17 = "Quad.easeIn";
    const EASE_18 = "Quad.easeInOut";
    const EASE_19 = "Quad.easeOut";
    const EASE_20 = "Cubic.easeIn";
    const EASE_21 = "Cubic.easeInOut";
    const EASE_22 = "Cubic.easeOut";
    const EASE_23 = "Quart.easeIn";
    const EASE_24 = "Quart.easeInOut";
    const EASE_25 = "Quart.easeOut";
    const EASE_26 = "Quint.easeIn";
    const EASE_27 = "Quint.easeInOut";
    const EASE_28 = "Quint.easeOut";
    const EASE_29 = "Strong.easeIn";
    const EASE_30 = "Strong.easeInOut";
    const EASE_31 = "Strong.easeOut";
    const EASE_32 = "Back.easeIn";
    const EASE_33 = "Back.easeInOut";
    const EASE_34 = "Back.easeOut";
    const EASE_35 = "Bounce.easeIn";
    const EASE_36 = "Bounce.easeInOut";
    const EASE_37 = "Bounce.easeOut";
    const EASE_38 = "Circ.easeIn";
    const EASE_39 = "Circ.easeInOut";
    const EASE_40 = "Circ.easeOut";
    const EASE_41 = "Elastic.easeIn";
    const EASE_42 = "Elastic.easeInOut";
    const EASE_43 = "Elastic.easeOut";
    const EASE_44 = "Expo.easeIn";
    const EASE_45 = "Expo.easeInOut";
    const EASE_46 = "Expo.easeOut";
    const EASE_47 = "Sine.easeIn";
    const EASE_48 = "Sine.easeInOut";
    const EASE_49 = "Sine.easeOut";
    const EASE_50 = "SlowMo.ease";
    const EASE_51 = 'easeOutBack';
    const EASE_52 = 'easeInQuad';
    const EASE_53 = 'easeOutQuad';
    const EASE_54 = 'easeInOutQuad';
    const EASE_55 = 'easeInCubic';
    const EASE_56 = 'easeOutCubic';
    const EASE_57 = 'easeInOutCubic';
    const EASE_58 = 'easeInQuart';
    const EASE_59 = 'easeOutQuart';
    const EASE_60 = 'easeInOutQuart';
    const EASE_61 = 'easeInQuint';
    const EASE_62 = 'easeOutQuint';
    const EASE_63 = 'easeInOutQuint';
    const EASE_64 = 'easeInSine';
    const EASE_65 = 'easeOutSine';
    const EASE_66 = 'easeInOutSine';
    const EASE_67 = 'easeInExpo';
    const EASE_68 = 'easeOutExpo';
    const EASE_69 = 'easeInOutExpo';
    const EASE_70 = 'easeInCirc';
    const EASE_71 = 'easeOutCirc';
    const EASE_72 = 'easeInOutCirc';
    const EASE_73 = 'easeInElastic';
    const EASE_74 = 'easeOutElastic';
    const EASE_75 = 'easeInOutElastic';
    const EASE_76 = 'easeInBack';
    const EASE_77 = 'easeInOutBack';
    const EASE_78 = 'easeInBounce';
    const EASE_79 = 'easeOutBounce';
    const EASE_80 = 'easeInOutBounce';
    
    const ANI_FADE = 'tp-fade';
    const ANI_SFT = 'sft';
    const ANI_SFB = 'sfb';
    const ANI_SFR = 'sfl';
    const ANI_SFL = 'sfl';
    const ANI_LFT = 'lft';
    const ANI_LFB = 'lfb';
    const ANI_LFL = 'lfl';
    const ANI_LFR = 'lfr';
    const ANI_SKFR = 'skewfromright';
    const ANI_SKFL = 'skewfromleft';
    const ANI_SKFSR = 'skewfromrightshort';
    const ANI_SKFSL = 'skewfromleftshort';
    const ANI_RANROTATE = 'randomrotate';
    
    const CORNOR_NONE = 'nothing';
    const CORNOR_CURVED = 'curved';
    const CORNOR_REVERSED = 'reverced';
    
    const WS_NORMAL = 'normal';
    const WS_PRE = 'pre';
    const WS_NOWRAP = 'no-wrap';
    const WS_PRE_WRAP = 'pre-wrap';
    const WS_PRE_LINE = 'pre-line';
    
    const SPLIT_NONE = 'none';
    const SPLIT_CHARS = 'chars';
    const SPLIT_WORDS = 'words';
    const SPLIT_LINES = 'lines';
    
    const CSS_FONT_STYLE_1 = 'normal';
    const CSS_FONT_STYLE_2 = 'italic';
    
    const CSS_BORDER_STYLE_1 = 'none';
    const CSS_BORDER_STYLE_2 = 'dotted';
    const CSS_BORDER_STYLE_3 = 'dashed';
    const CSS_BORDER_STYLE_4 = 'solid';
    const CSS_BORDER_STYLE_5 = 'double';
    
    const CSS_DECORATION_1 = 'none';
    const CSS_DECORATION_2 = 'underline';
    const CSS_DECORATION_3 = 'overline';
    const CSS_DECORATION_4 = 'line-through';
    
    const RS_PARALLAXLEVEL_0 = 'rs-parallaxlevel-0';
    const RS_PARALLAXLEVEL_1 = 'rs-parallaxlevel-1';
    const RS_PARALLAXLEVEL_2 = 'rs-parallaxlevel-2';
    const RS_PARALLAXLEVEL_3 = 'rs-parallaxlevel-3';
    const RS_PARALLAXLEVEL_4 = 'rs-parallaxlevel-4';
    const RS_PARALLAXLEVEL_5 = 'rs-parallaxlevel-5';
    const RS_PARALLAXLEVEL_6 = 'rs-parallaxlevel-6';
    const RS_PARALLAXLEVEL_7 = 'rs-parallaxlevel-7';
    const RS_PARALLAXLEVEL_8 = 'rs-parallaxlevel-8';
    const RS_PARALLAXLEVEL_9 = 'rs-parallaxlevel-9';
    const RS_PARALLAXLEVEL_10 = 'rs-parallaxlevel-10';
    
    public function _construct()
    {
        parent::_construct();
        $this->_init( 'dynamicslideshow/slides' );
    }
    
    public function getOptLinkType()
    {
        $opt = new Varien_Object( array(
            self::LINK_REGULAR => Mage::helper( 'dynamicslideshow' )->__( 'Regular' ),
            self::LINK_SLIDE => Mage::helper( 'dynamicslideshow' )->__( 'To Slide' ) 
        ) );
        return $opt->getData();
    }
    
    public function getOptLinkTarget()
    {
        $opt = new Varien_Object( array(
             self::TARGET_SAME => Mage::helper( 'dynamicslideshow' )->__( 'Same Window' ),
            self::TARGET_NEW => Mage::helper( 'dynamicslideshow' )->__( 'New Window' ) 
        ) );
        return $opt->getData();
    }
    
    public function getOptLinkPosition()
    {
        $opt = new Varien_Object( array(
            self::POSITION_FRONT => Mage::helper( 'dynamicslideshow' )->__( 'Front' ),
            self::POSITION_BACK => Mage::helper( 'dynamicslideshow' )->__( 'Back' ) 
        ) );
        return $opt->getData();
    }
    
    public function getOptPubUnpub()
    {
        $opt = new Varien_Object( array(
             self::PUBLISHED => Mage::helper( 'dynamicslideshow' )->__( 'Published' ),
            self::UNPUBLISHED => Mage::helper( 'dynamicslideshow' )->__( 'Unpublished' ) 
        ) );
        return $opt->getData();
    }
    
    public function getOptBackgroundType()
    {
        $opt = new Varien_Object( array(
            self::BG_IMAGE => Mage::helper( 'dynamicslideshow' )->__( 'Image' ),
            self::BG_TRANS => Mage::helper( 'dynamicslideshow' )->__( 'Transparent' ),
            self::BG_SOLID => Mage::helper( 'dynamicslideshow' )->__( 'Solid Color' ),
            self::BG_URL => Mage::helper( 'dynamicslideshow' )->__( 'External URL' ) 
        ) );
        return $opt->getData();
    }
    
    public function getOptBackgroundSize()
    {
        $opt = new Varien_Object( array(
             self::BG_COVER => Mage::helper( 'dynamicslideshow' )->__( 'Cover' ),
            self::BG_CONTAIN => Mage::helper( 'dynamicslideshow' )->__( 'Contain' ),
            self::BG_PERCENTAGE => Mage::helper( 'dynamicslideshow' )->__( '(%, %)' ),
            self::BG_NORMAL => Mage::helper( 'dynamicslideshow' )->__( 'Normal' ) 
        ) );
        return $opt->getData();
    }
    
    public function getOptBackgroundPosition()
    {
        $opt = new Varien_Object( array(
            self::BG_LT => Mage::helper( 'dynamicslideshow' )->__( 'Left Top' ),
            self::BG_LC => Mage::helper( 'dynamicslideshow' )->__( 'Left Center' ),
            self::BG_LB => Mage::helper( 'dynamicslideshow' )->__( 'Left Bottom' ),
            self::BG_RT => Mage::helper( 'dynamicslideshow' )->__( 'Right Top' ),
            self::BG_RC => Mage::helper( 'dynamicslideshow' )->__( 'Right Center' ),
            self::BG_RB => Mage::helper( 'dynamicslideshow' )->__( 'Right Bottom' ),
            self::BG_CT => Mage::helper( 'dynamicslideshow' )->__( 'Center Top' ),
            self::BG_CR => Mage::helper( 'dynamicslideshow' )->__( 'Center Right' ),
            self::BG_CB => Mage::helper( 'dynamicslideshow' )->__( 'Center Bottom' ),
            self::BG_CC => Mage::helper( 'dynamicslideshow' )->__( 'Center' ),
            self::BG_PE => Mage::helper( 'dynamicslideshow' )->__( '(X%, Y%)' ) 
        ) );
        return $opt->getData();
    }
    
    public function getOptParallaxLevel()
    {
        $options = new Varien_Object( array(
            self::RS_PARALLAXLEVEL_0 => Mage::helper( 'dynamicslideshow' )->__( 'Level 0' ),
            self::RS_PARALLAXLEVEL_1 => Mage::helper( 'dynamicslideshow' )->__( 'Level 1' ),
            self::RS_PARALLAXLEVEL_2 => Mage::helper( 'dynamicslideshow' )->__( 'Level 2' ),
            self::RS_PARALLAXLEVEL_3 => Mage::helper( 'dynamicslideshow' )->__( 'Level 3' ),
            self::RS_PARALLAXLEVEL_4 => Mage::helper( 'dynamicslideshow' )->__( 'Level 4' ),
            self::RS_PARALLAXLEVEL_5 => Mage::helper( 'dynamicslideshow' )->__( 'Level 5' ),
            self::RS_PARALLAXLEVEL_6 => Mage::helper( 'dynamicslideshow' )->__( 'Level 6' ),
            self::RS_PARALLAXLEVEL_7 => Mage::helper( 'dynamicslideshow' )->__( 'Level 7' ),
            self::RS_PARALLAXLEVEL_8 => Mage::helper( 'dynamicslideshow' )->__( 'Level 8' ),
            self::RS_PARALLAXLEVEL_9 => Mage::helper( 'dynamicslideshow' )->__( 'Level 9' ),
            self::RS_PARALLAXLEVEL_10 => Mage::helper( 'dynamicslideshow' )->__( 'Level 10' ) 
        ) );
        return $options->getData();
    }
    
    public function getOptLayerEase()
    {
        $opt = new Varien_Object( array(
            self::EASE_1 => "Linear.easeNone",
            self::EASE_2 => "Power0.easeIn",
            self::EASE_3 => "Power0.easeInOut",
            self::EASE_4 => "Power0.easeOut",
            self::EASE_5 => "Power1.easeIn",
            self::EASE_6 => "Power1.easeInOut",
            self::EASE_7 => "Power1.easeOut",
            self::EASE_8 => "Power2.easeIn",
            self::EASE_9 => "Power2.easeInOut",
            self::EASE_10 => "Power2.easeOut",
            self::EASE_11 => "Power3.easeIn",
            self::EASE_12 => "Power3.easeInOut",
            self::EASE_13 => "Power3.easeOut",
            self::EASE_14 => "Power4.easeIn",
            self::EASE_15 => "Power4.easeInOut",
            self::EASE_16 => "Power4.easeOut",
            self::EASE_17 => "Quad.easeIn",
            self::EASE_18 => "Quad.easeInOut",
            self::EASE_19 => "Quad.easeOut",
            self::EASE_20 => "Cubic.easeIn",
            self::EASE_21 => "Cubic.easeInOut",
            self::EASE_22 => "Cubic.easeOut",
            self::EASE_23 => "Quart.easeIn",
            self::EASE_24 => "Quart.easeInOut",
            self::EASE_25 => "Quart.easeOut",
            self::EASE_26 => "Quint.easeIn",
            self::EASE_27 => "Quint.easeInOut",
            self::EASE_28 => "Quint.easeOut",
            self::EASE_29 => "Strong.easeIn",
            self::EASE_30 => "Strong.easeInOut",
            self::EASE_31 => "Strong.easeOut",
            self::EASE_32 => "Back.easeIn",
            self::EASE_33 => "Back.easeInOut",
            self::EASE_34 => "Back.easeOut",
            self::EASE_35 => "Bounce.easeIn",
            self::EASE_36 => "Bounce.easeInOut",
            self::EASE_37 => "Bounce.easeOut",
            self::EASE_38 => "Circ.easeIn",
            self::EASE_39 => "Circ.easeInOut",
            self::EASE_40 => "Circ.easeOut",
            self::EASE_41 => "Elastic.easeIn",
            self::EASE_42 => "Elastic.easeInOut",
            self::EASE_43 => "Elastic.easeOut",
            self::EASE_44 => "Expo.easeIn",
            self::EASE_45 => "Expo.easeInOut",
            self::EASE_46 => "Expo.easeOut",
            self::EASE_47 => "Sine.easeIn",
            self::EASE_48 => "Sine.easeInOut",
            self::EASE_49 => "Sine.easeOut",
            self::EASE_50 => "SlowMo.ease",
            self::EASE_51 => 'easeOutBack',
            self::EASE_52 => 'easeInQuad',
            self::EASE_53 => 'easeOutQuad',
            self::EASE_54 => 'easeInOutQuad',
            self::EASE_55 => 'easeInCubic',
            self::EASE_56 => 'easeOutCubic',
            self::EASE_57 => 'easeInOutCubic',
            self::EASE_58 => 'easeInQuart',
            self::EASE_59 => 'easeOutQuart',
            self::EASE_60 => 'easeInOutQuart',
            self::EASE_61 => 'easeInQuint',
            self::EASE_62 => 'easeOutQuint',
            self::EASE_63 => 'easeInOutQuint',
            self::EASE_64 => 'easeInSine',
            self::EASE_65 => 'easeOutSine',
            self::EASE_66 => 'easeInOutSine',
            self::EASE_67 => 'easeInExpo',
            self::EASE_68 => 'easeOutExpo',
            self::EASE_69 => 'easeInOutExpo',
            self::EASE_70 => 'easeInCirc',
            self::EASE_71 => 'easeOutCirc',
            self::EASE_72 => 'easeInOutCirc',
            self::EASE_73 => 'easeInElastic',
            self::EASE_74 => 'easeOutElastic',
            self::EASE_75 => 'easeInOutElastic',
            self::EASE_76 => 'easeInBack',
            self::EASE_77 => 'easeInOutBack',
            self::EASE_78 => 'easeInBounce',
            self::EASE_79 => 'easeOutBounce',
            self::EASE_80 => 'easeInOutBounce' 
        ) );
        return $opt->getData();
    }
    
    public function getOptLayerEndAnimation()
    {
        $opt = new Varien_Object( array(
            self::ANI_FADE => Mage::helper( 'dynamicslideshow' )->__( 'Fade' ),
            self::ANI_SFT => Mage::helper( 'dynamicslideshow' )->__( 'Short from Top' ),
            self::ANI_SFB => Mage::helper( 'dynamicslideshow' )->__( 'Short from Bottom' ),
            self::ANI_SFL => Mage::helper( 'dynamicslideshow' )->__( 'Short from Left' ),
            self::ANI_SFR => Mage::helper( 'dynamicslideshow' )->__( 'Short from Right' ),
            self::ANI_LFT => Mage::helper( 'dynamicslideshow' )->__( 'Long from Top' ),
            self::ANI_LFB => Mage::helper( 'dynamicslideshow' )->__( 'Long from Bottom' ),
            self::ANI_LFL => Mage::helper( 'dynamicslideshow' )->__( 'Long from Left' ),
            self::ANI_LFR => Mage::helper( 'dynamicslideshow' )->__( 'Long from Right' ),
            self::ANI_SKFR => Mage::helper( 'dynamicslideshow' )->__( 'Skew From Long Right' ),
            self::ANI_SKFL => Mage::helper( 'dynamicslideshow' )->__( 'Skew From Long Left' ),
            self::ANI_SKFSR => Mage::helper( 'dynamicslideshow' )->__( 'Skew From Short Right' ),
            self::ANI_SKFSL => Mage::helper( 'dynamicslideshow' )->__( 'Skew From Short Left' ),
            self::ANI_RANROTATE => Mage::helper( 'dynamicslideshow' )->__( 'Random Rotate' ) 
        ) );
        return $opt->getData();
    }
    
    public function getOptWhiteSpace()
    {
        $opt = new Varien_Object( array(
            self::WS_NORMAL => Mage::helper( 'dynamicslideshow' )->__( 'Normal' ),
            self::WS_PRE => Mage::helper( 'dynamicslideshow' )->__( 'Pre' ),
            self::WS_NOWRAP => Mage::helper( 'dynamicslideshow' )->__( 'No-Wrap' ),
            self::WS_PRE_WRAP => Mage::helper( 'dynamicslideshow' )->__( 'Pre-Wrap' ),
            self::WS_PRE_LINE => Mage::helper( 'dynamicslideshow' )->__( 'Pre-Line' ) 
        ) );
        return $opt->getData();
    }
    
    public function getOptLayerSplit()
    {
        $opt = new Varien_Object( array(
            self::SPLIT_NONE => Mage::helper( 'dynamicslideshow' )->__( 'No Split' ),
            self::SPLIT_CHARS => Mage::helper( 'dynamicslideshow' )->__( 'Char Based' ),
            self::SPLIT_WORDS => Mage::helper( 'dynamicslideshow' )->__( 'Word Based' ),
            self::SPLIT_LINES => Mage::helper( 'dynamicslideshow' )->__( 'Line Based' ) 
        ) );
        return $opt->getData();
    }
    
    public function getOptCornor()
    {
        $opt = new Varien_Object( array(
            self::CORNOR_NONE => Mage::helper( 'dynamicslideshow' )->__( 'No Corner' ),
            self::CORNOR_CURVED => Mage::helper( 'dynamicslideshow' )->__( 'Sharp' ),
            self::CORNOR_REVERSED => Mage::helper( 'dynamicslideshow' )->__( 'Sharp Reversed' ) 
        ) );
        return $opt->getData();
    }
    
    public function getOptCssFontStyle()
    {
        $opt = new Varien_Object( array(
            self::CSS_FONT_STYLE_1 => Mage::helper( 'dynamicslideshow' )->__( 'None' ),
            self::CSS_FONT_STYLE_2 => Mage::helper( 'dynamicslideshow' )->__( 'Italic' ) 
        ) );
        return $opt->getData();
    }
    
    public function getOptCssBorderStyle()
    {
        $opt = new Varien_Object( array(
            self::CSS_BORDER_STYLE_1 => Mage::helper( 'dynamicslideshow' )->__( 'None' ),
            self::CSS_BORDER_STYLE_2 => Mage::helper( 'dynamicslideshow' )->__( 'Dotted' ),
            self::CSS_BORDER_STYLE_3 => Mage::helper( 'dynamicslideshow' )->__( 'Dashed' ),
            self::CSS_BORDER_STYLE_4 => Mage::helper( 'dynamicslideshow' )->__( 'Solid' ),
            self::CSS_BORDER_STYLE_5 => Mage::helper( 'dynamicslideshow' )->__( 'Double' ) 
        ) );
        return $opt->getData();
    }
    
    public function getOptCssDecoration()
    {
        $opt = new Varien_Object( array(
             self::CSS_DECORATION_1 => Mage::helper( 'dynamicslideshow' )->__( 'None' ),
            self::CSS_DECORATION_2 => Mage::helper( 'dynamicslideshow' )->__( 'Underline' ),
            self::CSS_DECORATION_3 => Mage::helper( 'dynamicslideshow' )->__( 'Overline' ),
            self::CSS_DECORATION_4 => Mage::helper( 'dynamicslideshow' )->__( 'Line-Through' ) 
        ) );
        return $opt->getData();
    }
    
    public function getAllSlides( $published = false )
    {
        $collection = Mage::getModel( 'dynamicslideshow/slides' )->getCollection()->addSlidersFilter( $this )->setOrder( 'slide_order', 'asc' );
        $slides     = array();
        foreach ( $collection as $slide )
        {
            $id     = $slide->getId();
            $layers = $slide->getLayers();
            $slide->setData( Mage::helper( 'core' )->jsonDecode( $slide->getParams() ) );
            $slide->setLayers( Mage::helper( 'core' )->jsonDecode( $layers ) );
            $slide->setId( $id );
            if ( $published )
            {
                if ( $slide->getData( 'state' ) == 'published' )
                {
                    $slides[] = $slide;
                }
            }
            else
            {
                $slides[] = $slide;
            }
        }
        return $slides;
    }
    
    public function _afterLoad()
    {
        $id         = $this->getId();
        $sliderId   = $this->getSliderId();
        $slideOrder = $this->getSlideOrder();
        $layers     = Mage::helper( 'core' )->jsonDecode( $this->getLayers() );
        $this->setData( (array) Mage::helper( 'core' )->jsonDecode( $this->getParams() ) );
        $this->setData( 'slide_transition', explode( ',', $this->getData( 'slide_transition' ) ) );
        $this->setData( 'layers', $layers );
        $date = Mage::getModel( 'core/date' );
        if ( $this->getData( 'date_from' ) )
        {
            $this->setData( 'date_from', $date->date( 'm/d/Y', $date->timestamp( $this->getData( 'date_from' ) ) ) );
        }
        if ( $this->getData( 'date_to' ) )
        {
            $this->setData( 'date_to', $date->date( 'm/d/Y', $date->timestamp( $this->getData( 'date_to' ) ) ) );
        }
        $this->setId( $id );
        $this->setSliderId( $sliderId );
        $this->setSlideOrder( $slideOrder );
        return parent::_afterLoad();
    }
    
    public function _beforeSave()
    {
        if ( is_array( $this->getData( 'layers' ) ) )
        {
            $this->setData( 'layers', Mage::helper( 'core' )->jsonEncode( $this->getLayers() ) );
        }
        return parent::_beforeSave();
    }
}