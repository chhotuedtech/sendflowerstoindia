<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Model_Sliders extends Mage_Core_Model_Abstract
{
    // Status Disable|Enable
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 2;
    
    // Slider Type 
    const TYPE_FIXED = 'fixed';
    const TYPE_CUSTOM = 'responsive';
    const TYPE_AUTO = 'fullwidth';
    const TYPE_FULL = 'fullscreen';
    
    // On|Off
    const OPTION_ON = 'on';
    const OPTION_OFF = 'off';
    
    // True|False
    const OPTION_YES = 'yes';
    const OPTION_NO = 'no';
    
    // Left|Center|Right|Bottom|Top
    const OPTION_LEFT = 'left';
    const OPTION_CENTER = 'center';
    const OPTION_RIGHT = 'right';
    const OPTION_TOP = 'top';
    const OPTION_BOTTOM = 'bottom';
    
    // Type Shadow
    const TYPE_SHADOW_0 = 0;
    const TYPE_SHADOW_1 = 1;
    const TYPE_SHADOW_2 = 2;
    const TYPE_SHADOW_3 = 3;
    
    // Timer line
    const TIMER_LINE_TOP = 'top';
    const TIMER_LINE_BOTTOM = 'bottom';
    const TIMER_LINE_HIDE = 'hide';
    
    // Dotted Overlay
    const DOTTED_OVERLAY_0 = 'none';
    const DOTTED_OVERLAY_1 = 'twoxtwo';
    const DOTTED_OVERLAY_2 = 'twoxtwowhite';
    const DOTTED_OVERLAY_3 = 'threexthree';
    const DOTTED_OVERLAY_4 = 'threexthreewhite';
    
    // Background Fit
    const BGR_FIT_COVER = 'cover';
    const BGR_FIT_CONTAIN = 'contain';
    const BGR_FIT_NORMAL = 'normal';
    
    // Background Repeat
    const BGR_REPEAT_NO = 'no-repeat';
    const BGR_REPEAT_XY = 'repeat';
    const BGR_REPEAT_X = 'repeat-x';
    const BGR_REPEAT_Y = 'repeat-y';
    
    // Background Position
    const BGR_POSITION_LT = 'left top';
    const BGR_POSITION_LC = 'left center';
    const BGR_POSITION_LB = 'left bottom';
    const BGR_POSITION_RT = 'right top';
    const BGR_POSITION_RC = 'right center';
    const BGR_POSITION_RB = 'right bottom';
    const BGR_POSITION_CT = 'center top';
    const BGR_POSITION_CR = 'center right';
    const BGR_POSITION_CB = 'center bottom';
    const BGR_POSITION_CC = 'center';
    
    // Navigation Type
    const NAV_TYPE_NONE = 'none';
    const NAV_TYPE_BULLET = 'bullet';
    const NAV_TYPE_THUMB = 'thumb';
    const NAV_TYPE_BOTH = 'both';
    
    // Navigation Arrows
    const NAV_ARROWS_BULLET = 'nexttobullets';
    const NAV_ARROWS_SOLO = 'solo';
    const NAV_ARROWS_NONE = 'none';
    
    // Navigation Style
    const NAV_STYLE_PREV1 = 'preview1';
    const NAV_STYLE_PREV2 = 'preview2';
    const NAV_STYLE_PREV3 = 'preview3';
    const NAV_STYLE_PREV4 = 'preview4';
    const NAV_STYLE_ROUND = 'round';
    const NAV_STYLE_NAVBAR = 'navbar';
    const NAV_STYLE_ROUND_OLD = 'round-old';
    const NAV_STYLE_SQUARE_OLD = 'square-old';
    const NAV_STYLE_NAVBAR_OLD = 'navbar-old';
    
    // Transition 
    const TRANSITION_1 = 'random-static';
    const TRANSITION_2 = 'random-premium';
    const TRANSITION_3 = 'random';
    const TRANSITION_4 = 'slideup';
    const TRANSITION_5 = 'slidedown';
    const TRANSITION_6 = 'slideright';
    const TRANSITION_7 = 'slideleft';
    const TRANSITION_8 = 'slidehorizontal';
    const TRANSITION_9 = 'slidevertical';
    const TRANSITION_10 = 'boxslide';
    const TRANSITION_11 = 'slotslide-horizontal';
    const TRANSITION_12 = 'slotslide-vertical';
    const TRANSITION_13 = 'notransition';
    const TRANSITION_14 = 'fade';
    const TRANSITION_15 = 'boxfade';
    const TRANSITION_16 = 'slotfade-horizontal';
    const TRANSITION_17 = 'slotfade-vertical';
    const TRANSITION_18 = 'fadefromright';
    const TRANSITION_19 = 'fadefromleft';
    const TRANSITION_20 = 'fadefromtop';
    const TRANSITION_21 = 'fadefrombottom';
    const TRANSITION_22 = 'fadetoleftfadefromright';
    const TRANSITION_23 = 'fadetorightfadefromleft';
    const TRANSITION_24 = 'fadetotopfadefrombottom';
    const TRANSITION_25 = 'fadetobottomfadefromtop';
    const TRANSITION_26 = 'parallaxtoright';
    const TRANSITION_27 = 'parallaxtoleft';
    const TRANSITION_28 = 'parallaxtotop';
    const TRANSITION_29 = 'parallaxtobottom';
    const TRANSITION_30 = 'scaledownfromright';
    const TRANSITION_31 = 'scaledownfromleft';
    const TRANSITION_32 = 'scaledownfromtop';
    const TRANSITION_33 = 'scaledownfrombottom';
    const TRANSITION_34 = 'zoomout';
    const TRANSITION_35 = 'zoomin';
    const TRANSITION_36 = 'slotzoom-horizontal';
    const TRANSITION_37 = 'slotzoom-vertical';
    const TRANSITION_38 = 'curtain-1';
    const TRANSITION_39 = 'curtain-2';
    const TRANSITION_40 = 'curtain-3';
    const TRANSITION_41 = '3dcurtain-horizontal';
    const TRANSITION_42 = '3dcurtain-vertical';
    const TRANSITION_43 = 'cube';
    const TRANSITION_44 = 'cube-horizontal';
    const TRANSITION_45 = 'incube';
    const TRANSITION_46 = 'incube-horizontal';
    const TRANSITION_47 = 'turnoff';
    const TRANSITION_48 = 'turnoff-vertical';
    const TRANSITION_49 = 'papercut';
    const TRANSITION_50 = 'flyin';
    
    public function _construct()
    {
        parent::_construct();
        $this->_init( 'dynamicslideshow/sliders' );
    }
    
    public function getHeading( $name = null, $title = null )
    {
        return array(
            'onclick' => "",
            'onchange' => "",
            'name' => $name,
            'style' => "border:10px; background:none repeat scroll 0 0 #6F8992; font-weight:bold; padding:4px 3px; color:#FFF; text-align:center",
            'value' => $this->getData( $name ) ? $this->getData( $name ) : $title,
            'readonly' => true 
        );
    }
    
    // Get Type Slider
    public function getTypeSlider()
    {
        $type = new Varien_Object( array(
            self::TYPE_FIXED => Mage::helper( 'dynamicslideshow' )->__( 'Fixed' ),
            self::TYPE_CUSTOM => Mage::helper( 'dynamicslideshow' )->__( 'Custom' ),
            self::TYPE_AUTO => Mage::helper( 'dynamicslideshow' )->__( 'Responsive' ),
            self::TYPE_FULL => Mage::helper( 'dynamicslideshow' )->__( 'Full Screen' ) 
        ) );
        return $type->getData();
    }
    
    // Get Option On|Off
    public function getOptOnOff()
    {
        $opt = new Varien_Object( array(
             array(
                 'value' => self::OPTION_ON,
                'label' => Mage::helper( 'dynamicslideshow' )->__( 'On' ) 
            ),
            array(
                 'value' => self::OPTION_OFF,
                'label' => Mage::helper( 'dynamicslideshow' )->__( 'Off' ) 
            ) 
        ) );
        return $opt->getData();
    }
    
    // Get Option Yes|No
    public function getOptYesNo()
    {
        $opt = new Varien_Object( array(
            self::OPTION_YES => Mage::helper( 'dynamicslideshow' )->__( 'Yes' ),
            self::OPTION_NO => Mage::helper( 'dynamicslideshow' )->__( 'No' ) 
        ) );
        return $opt->getData();
    }
    
    // Get Status
    public function getOptStatus()
    {
        $opt = new Varien_Object( array(
            self::STATUS_ENABLED => Mage::helper( 'dynamicslideshow' )->__( 'Enable' ),
            self::STATUS_DISABLED => Mage::helper( 'dynamicslideshow' )->__( 'Disable' ) 
        ) );
        return $opt->getData();
    }
    
    // Get Option Left|Right|Center
    public function getOptLCR()
    {
        $options = new Varien_Object( array(
            self::OPTION_LEFT => Mage::helper( 'dynamicslideshow' )->__( 'Left' ),
            self::OPTION_CENTER => Mage::helper( 'dynamicslideshow' )->__( 'Center' ),
            self::OPTION_RIGHT => Mage::helper( 'dynamicslideshow' )->__( 'Right' ) 
        ) );
        return $options->getData();
    }
    
    // Get Option Top|Center|Bottom
    public function getOptTCB()
    {
        $options = new Varien_Object( array(
            self::OPTION_TOP => Mage::helper( 'dynamicslideshow' )->__( 'Top' ),
            self::OPTION_CENTER => Mage::helper( 'dynamicslideshow' )->__( 'Center' ),
            self::OPTION_BOTTOM => Mage::helper( 'dynamicslideshow' )->__( 'Bottom' ) 
        ) );
        return $options->getData();
    }
    
    // Get Type Shadow
    public function getTypeShadow()
    {
        $type_shadow = new Varien_Object( array(
            self::TYPE_SHADOW_0 => Mage::helper( 'dynamicslideshow' )->__( 'No Shadow' ),
            self::TYPE_SHADOW_1 => Mage::helper( 'dynamicslideshow' )->__( '1' ),
            self::TYPE_SHADOW_2 => Mage::helper( 'dynamicslideshow' )->__( '2' ),
            self::TYPE_SHADOW_3 => Mage::helper( 'dynamicslideshow' )->__( '3' ) 
        ) );
        return $type_shadow->getData();
    }
    
    // Get Timer Line
    public function getTimerLine()
    {
        $timer_line = new Varien_Object( array(
            self::TIMER_LINE_TOP => Mage::helper( 'dynamicslideshow' )->__( 'Top' ),
            self::TIMER_LINE_BOTTOM => Mage::helper( 'dynamicslideshow' )->__( 'Bottom' ),
            self::TIMER_LINE_HIDE => Mage::helper( 'dynamicslideshow' )->__( 'Hide' ) 
        ) );
        return $timer_line->getData();
    }
    
    // Get Background Dotted Overlay
    public function getOptBgrDottedOverlay()
    {
        $bg_dotted = new Varien_Object( array(
            self::DOTTED_OVERLAY_0 => Mage::helper( 'dynamicslideshow' )->__( 'None' ),
            self::DOTTED_OVERLAY_1 => Mage::helper( 'dynamicslideshow' )->__( '2 x 2 Black' ),
            self::DOTTED_OVERLAY_2 => Mage::helper( 'dynamicslideshow' )->__( '2 x 2 White' ),
            self::DOTTED_OVERLAY_3 => Mage::helper( 'dynamicslideshow' )->__( '3 x 3 Black' ),
            self::DOTTED_OVERLAY_4 => Mage::helper( 'dynamicslideshow' )->__( '3 x 3 White' ) 
        ) );
        return $bg_dotted->getData();
    }
    
    // Get Background Fit
    public function getOptBgrFit()
    {
        $bg_fit = new Varien_Object( array(
            self::BGR_FIT_COVER => Mage::helper( 'dynamicslideshow' )->__( 'Cover' ),
            self::BGR_FIT_CONTAIN => Mage::helper( 'dynamicslideshow' )->__( 'Contain' ),
            self::BGR_FIT_NORMAL => Mage::helper( 'dynamicslideshow' )->__( 'Normal' ) 
        ) );
        return $bg_fit->getData();
    }
    
    // Get Background Repeat
    public function getOptBgrRepeat()
    {
        $bg_repeat = new Varien_Object( array(
            self::BGR_REPEAT_NO => Mage::helper( 'dynamicslideshow' )->__( 'No-Repeat' ),
            self::BGR_REPEAT_XY => Mage::helper( 'dynamicslideshow' )->__( 'Repeat' ),
            self::BGR_REPEAT_X => Mage::helper( 'dynamicslideshow' )->__( 'Repeat-X' ),
            self::BGR_REPEAT_Y => Mage::helper( 'dynamicslideshow' )->__( 'Repeat-Y' ) 
        ) );
        return $bg_repeat->getData();
    }
    
    // Get Background Position
    public function getOptBgrPosition()
    {
        $bg_position = new Varien_Object( array(
            self::BGR_POSITION_LT => Mage::helper( 'dynamicslideshow' )->__( 'Left Top' ),
            self::BGR_POSITION_LC => Mage::helper( 'dynamicslideshow' )->__( 'Left Center' ),
            self::BGR_POSITION_LB => Mage::helper( 'dynamicslideshow' )->__( 'Left Bottom' ),
            self::BGR_POSITION_RT => Mage::helper( 'dynamicslideshow' )->__( 'Right Top' ),
            self::BGR_POSITION_RC => Mage::helper( 'dynamicslideshow' )->__( 'Right Center' ),
            self::BGR_POSITION_RB => Mage::helper( 'dynamicslideshow' )->__( 'Right Bottom' ),
            self::BGR_POSITION_CT => Mage::helper( 'dynamicslideshow' )->__( 'Center Top' ),
            self::BGR_POSITION_CR => Mage::helper( 'dynamicslideshow' )->__( 'Center Right' ),
            self::BGR_POSITION_CB => Mage::helper( 'dynamicslideshow' )->__( 'Center Bottom' ),
            self::BGR_POSITION_CC => Mage::helper( 'dynamicslideshow' )->__( 'Center' ) 
        ) );
        return $bg_position->getData();
    }
    
    // Get Navigation Type
    public function getTypeNavigation()
    {
        $type_nav = new Varien_Object( array(
            self::NAV_TYPE_NONE => Mage::helper( 'dynamicslideshow' )->__( 'None' ),
            self::NAV_TYPE_BULLET => Mage::helper( 'dynamicslideshow' )->__( 'Bullet' ),
            self::NAV_TYPE_THUMB => Mage::helper( 'dynamicslideshow' )->__( 'Thumb' ),
            //self::NAV_TYPE_BOTH => Mage::helper( 'dynamicslideshow' )->__( 'Both' ) 
        ) );
        return $type_nav->getData();
    }
    
    // Get Navigation Arrows
    public function getOptNavArrows()
    {
        $nav_arrows = new Varien_Object( array(
            self::NAV_ARROWS_BULLET => Mage::helper( 'dynamicslideshow' )->__( 'With Bullets' ),
            self::NAV_ARROWS_SOLO => Mage::helper( 'dynamicslideshow' )->__( 'Solo' ),
            self::NAV_ARROWS_NONE => Mage::helper( 'dynamicslideshow' )->__( 'None' ) 
        ) );
        return $nav_arrows->getData();
    }
    
    // Get Navigation Navigation Style
    public function getOptStyleNav()
    {
        $options = new Varien_Object( array(
            // self::NAV_STYLE_PREV1 => Mage::helper( 'dynamicslideshow' )->__( 'Preview1' ),
            // self::NAV_STYLE_PREV2 => Mage::helper( 'dynamicslideshow' )->__( 'Preview2' ),
            // self::NAV_STYLE_PREV3 => Mage::helper( 'dynamicslideshow' )->__( 'Preview3' ),
            // self::NAV_STYLE_PREV4 => Mage::helper( 'dynamicslideshow' )->__( 'Preview4' ),
            self::NAV_STYLE_ROUND => Mage::helper( 'dynamicslideshow' )->__( 'Round' ),
            self::NAV_STYLE_NAVBAR => Mage::helper( 'dynamicslideshow' )->__( 'Navbar' ),
            self::NAV_STYLE_ROUND_OLD => Mage::helper( 'dynamicslideshow' )->__( 'Old Round' ),
            self::NAV_STYLE_SQUARE_OLD => Mage::helper( 'dynamicslideshow' )->__( 'Old Square' ),
            self::NAV_STYLE_NAVBAR_OLD => Mage::helper( 'dynamicslideshow' )->__( 'Old Navbar' ) 
        ) );
        return $options->getData();
    }
    
    
    public function getOptLinkSlide( $exclude = array() )
    {
        $options = new Varien_Object( array(
             'nothing' => Mage::helper( 'dynamicslideshow' )->__( '-- Not Chosen --' ),
            'next' => Mage::helper( 'dynamicslideshow' )->__( '-- Next Slide --' ),
            'prev' => Mage::helper( 'dynamicslideshow' )->__( '-- Previous Slide --' ) 
        ) );
        $slides  = $this->getAllSlides( true );
        foreach ( $slides as $k => $slide )
        {
            if ( !in_array( $slide->getId(), $exclude ) )
            {
                $options->setData( $k + 1, $slide->getTitle() ? $slide->getTitle() : "ID: {$slide->getId()}" );
            }
        }
        return $options->getData();
    }
    
    // Get Transition
    public function getOptTransistion()
    {
        return array(
             array(
                 'label' => Mage::helper( 'dynamicslideshow' )->__( 'Random' ),
                'value' => array(
                     array(
                         'value' => self::TRANSITION_1,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Random Flat' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_2,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Random Premium' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_3,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Random Flat and Premium' ) 
                    ) 
                ) 
            ),
            array(
                 'label' => Mage::helper( 'dynamicslideshow' )->__( 'Slide' ),
                'value' => array(
                     array(
                         'value' => self::TRANSITION_4,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Slide To Top' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_5,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Slide To Bottom' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_6,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Slide To Right' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_7,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Slide To Left' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_8,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Slide Horizontal (depending on Next/Previous)' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_9,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Slide Vertical (depending on Next/Previous)' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_10,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Slide Boxes' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_11,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Slide Slots Horizontal' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_12,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Slide Slots Vertical' ) 
                    ) 
                ) 
            ),
            array(
                 'label' => Mage::helper( 'dynamicslideshow' )->__( 'Fade' ),
                'value' => array(
                     array(
                         'value' => self::TRANSITION_13,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'No Transition' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_14,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Fade' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_15,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Fade Boxes' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_16,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Fade Slots Horizontal' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_17,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Fade Slots Vertical' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_18,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Fade and Slide from Right' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_19,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Fade and Slide from Left' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_20,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Fade and Slide from Top' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_21,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Fade and Slide from Bottom' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_22,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Fade To Left and Fade From Right' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_23,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Fade To Right and Fade From Left' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_24,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Fade To Top and Fade From Bottom' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_25,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Fade To Bottom and Fade From Top' ) 
                    ) 
                ) 
            ),
            array(
                 'label' => Mage::helper( 'dynamicslideshow' )->__( 'Parallax' ),
                'value' => array(
                     array(
                         'value' => self::TRANSITION_26,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Parallax to Right' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_27,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Parallax to Left' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_28,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Parallax to Top' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_29,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Parallax to Bottom' ) 
                    ) 
                ) 
            ),
            array(
                 'label' => Mage::helper( 'dynamicslideshow' )->__( 'Zoom' ),
                'value' => array(
                     array(
                         'value' => self::TRANSITION_30,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Zoom Out and Fade From Right' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_31,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Zoom Out and Fade From Left' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_32,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Zoom Out and Fade From Top' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_33,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Zoom Out and Fade From Bottom' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_34,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'ZoomOut' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_35,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'ZoomIn' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_36,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Zoom Slots Horizontal' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_37,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Zoom Slots Vertical' ) 
                    ) 
                ) 
            ),
            array(
                 'label' => Mage::helper( 'dynamicslideshow' )->__( 'Curtain' ),
                'value' => array(
                     array(
                         'value' => self::TRANSITION_38,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Curtain from Left' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_39,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Curtain from Right' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_40,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Curtain from Middle' ) 
                    ) 
                ) 
            ),
            array(
                 'label' => Mage::helper( 'dynamicslideshow' )->__( 'Premium' ),
                'value' => array(
                     array(
                         'value' => self::TRANSITION_41,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( '3D Curtain Horizontal' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_42,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( '3D Curtain Vertical' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_43,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Cube Vertical' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_44,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Cube Horizontal' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_45,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'In Cube Vertical' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_46,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'In Cube Horizontal' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_47,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'TurnOff Horizontal' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_48,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'TurnOff Vertical' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_49,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Paper Cut' ) 
                    ),
                    array(
                         'value' => self::TRANSITION_50,
                        'label' => Mage::helper( 'dynamicslideshow' )->__( 'Fly In' ) 
                    ) 
                ) 
            ) 
        );
    }
    
    public function _afterLoad()
    {
        $storeIds = $this->getStoreIds();
        $id       = $this->getId();
        $this->setData( (array) Mage::helper( 'core' )->jsonDecode( $this->getParams() ) );
        $date = Mage::getModel( 'core/date' );
        if ( $this->getData( 'date_from' ) )
        {
            $this->setData( 'date_from', $date->date( 'm/d/Y', $date->timestamp( $this->getData( 'date_from' ) ) ) );
        }
        if ( $this->getData( 'date_to' ) )
        {
            $this->setData( 'date_to', $date->date( 'm/d/Y', $date->timestamp( $this->getData( 'date_to' ) ) ) );
        }
        $this->setStoreIds( $storeIds );
        $this->setId( $id );
        $this->setSliderid( $id );
        //$this->setTitle($storeIds);
        return parent::_afterLoad();
    }
    
    public function _beforeSave()
    {
        if ( is_array( $this->getData( 'params' ) ) )
        {
            $this->setData( 'params', Mage::helper( 'core' )->jsonEncode( $this->getParams() ) );
        }
        return parent::_beforeSave();
    }
    
    public function getSlideCount( $sliders )
    {
        if ( $sliders )
        {
            $collection = Mage::getModel( 'dynamicslideshow/slides' )->getCollection()->addSlidersFilter( $sliders )->setOrder( 'slide_order', 'asc' );
            return $collection->getSize();
        }
        return 0;
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
}