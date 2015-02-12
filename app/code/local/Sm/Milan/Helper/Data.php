<?php
/*------------------------------------------------------------------------
 # SM Milan - Version 1.0
 # Copyright (c) 2014 The YouTech Company. All Rights Reserved.
 # @license - Copyrighted Commercial Software
 # Author: YouTech Company
 # Websites: http://www.magentech.com
-------------------------------------------------------------------------*/

class Sm_Milan_Helper_Data extends Mage_Core_Helper_Abstract{

	public function __construct(){
		$this->defaults = array(
			/* general options */
			'layout_styles'				 => '1',
			'color'						 => 'red',
			'body_font_family'			 => 'Arial',
			'body_font_size'			 => '12px',
			'google_font'				 => 'Anton',
			'google_font_targets'		 => '',
			'direction'                  => '1',
			'body_link_color'			 => '#686868',
			'body_link_hover_color'		 => '#686868',
			'body_text_color'			 => '#686868',
			'body_background_color'		 => '#ffffff',			
			'body_background_image'		 => '',
			'use_customize_image'		 => '',
			'background_customize_image' => '',
			'background_repeat'		     => '',			
			'background_position'		 => '',
			'menu_styles'                => '1',
			'menu_ontop'		         => '1',			
			'responsive_menu'		     => '3',			
			/* detail milan */
			'show_imagezoom'		     => '',
			'zoom_mode'		 			 => '',
			'show_related' 				 => '',
			'related_number'		     => '',			
			'show_upsell'		 		 => '',
			'upsell_number'              => '',
			'show_customtab'		     => '',			
			'customtab_name'		     => '',
			'customtab_content'		     => '',	
			/*Rich Snippets*/
			'use_rich_snippet'   		 => '1',
			'set_breadcrumbs'   		 => '1',
			'google_plus_href'   		 => 'https://plus.google.com/u/0/+Smartaddons',
			/* advanced */
			'show_cpanel'		     	 => '1',
			'use_ajaxcart'		 		 => '1',
			'show_addtocart' 			 => '1',
			'show_wishlist'		     	 => '1',			
			'show_compare'		 		 => '1',
			'show_quickview'             => '1',
			'custom_copyright'		     => '',			
			'copyright'		     		 => '',
			'custom_css'		     	 => '',	
			'custom_js'		     		 => '',	
			'compress_css_js'		     => '',		
			'enable_yuicompressor'       => '',
		);
	}

	function get($attributes=array()){
		$data           = $this->defaults;
		$general        = Mage::getStoreConfig("milan_cfg/general");
		$detail_milan = Mage::getStoreConfig("milan_cfg/detail_milan");
		$rich_snippets_setting = Mage::getStoreConfig("milan_cfg/rich_snippets_setting");
		$social_milan = Mage::getStoreConfig("milan_cfg/social_milan");
		$advanced 	    = Mage::getStoreConfig("milan_cfg/advanced");
		if (!is_array($attributes)) {
			$attributes = array($attributes);
		}
		if (is_array($general))	
		$data = array_merge($data, $general);
		if (is_array($detail_milan)) 				
		$data = array_merge($data, $detail_milan);
		if (is_array($rich_snippets_setting)) 				
		$data = array_merge($data, $rich_snippets_setting);
		if (is_array($social_milan)) 				
		$data = array_merge($data, $social_milan);
		if (is_array($advanced)) 				
		$data = array_merge($data, $advanced);
		
		return array_merge($data, $attributes);
	}
	
}
	 