<?php
/*------------------------------------------------------------------------
 # SM Facebook - Version 1.0.0
 # Copyright (c) 2014 YouTech Company. All Rights Reserved.
 # @license - Copyrighted Commercial Software
 # Author: YouTech Company
 # Websites: http://www.magentech.com
-------------------------------------------------------------------------*/


class Sm_Facebook_Block_List extends Mage_Catalog_Block_Product_Abstract
{
	protected $_config = null;
	protected $_storeId = null;

	public function __construct($attr){
		parent::__construct($attr);
		$this->_config = $this->_getCfg($attr);
		if(!$this->_getConfig('active',1)) return;
		$this->_storeId    = Mage::app()->getStore()->getId();
	}

	public function _getCfg($attr = null)
	{
		// get default config.xml
		$defaults = array();
		$def_cfgs = Mage::getConfig()
			->loadModulesConfiguration('config.xml')
			->getNode('default/facebook_cfg')->asArray();
		if(empty($def_cfgs)) return;
		$groups = array();
		foreach($def_cfgs as  $def_key => $def_cfg){
			$groups[] = $def_key;
			foreach($def_cfg as $_def_key =>  $cfg){
				$defaults[$_def_key] = $cfg;
			}
		}

		// get configs after change
		$_configs = (array)Mage::getStoreConfig("facebook_cfg");
		if(empty($_configs)) return;
		$cfgs = array();

		foreach($groups as $group){
			$_cfgs = Mage::getStoreConfig('facebook_cfg/'. $group.'');
			foreach($_cfgs as $_key =>  $_cfg){
				$cfgs[$_key] = $_cfg;
			}
		}

		// get output config
		$configs = array();
		foreach($defaults as $key => $def){
			if(isset($defaults[$key])){
				$configs[$key] = $cfgs[$key];
			}else{
				unset($cfgs[$key]);
			}
		}
		$this->_config =  ($attr != null) ? array_merge($configs,$attr) : $configs;
		return $this->_config;
	}

	public function _getConfig($name = null, $value_def =null){
		if (is_null($this->_config)) $this->_getCfg();
		if (!is_null($name)){
			$value_def = isset($this->_config[$name]) ? $this->_config[$name] : $value_def;
			return $value_def;
		}
		return $this->_config;
	}


	public function _setConfig($name, $value = null){
		if ( is_null( $this->_config ) ) $this->_getCfg();
		if (is_array($name)){
			$this->_config = array_merge($this->_config, $name);
			return;
		}
		if (!empty($name) && isset( $this->_config[$name] ) )
		{
			$this->_config[$name] = $value;
		}
		return true;
	}

	protected function _toHtml(){
		if(!$this->_getConfig('active',1)) return;
		return parent::_toHtml();
	}
	
	function getActivityFeed(){
		//get config from admin
		$hostdomain     = trim($this->_getConfig('hostdomain'));
		$styletheme 	= trim($this->_getConfig('theme_style'));
		$recommen 		= trim($this->_getConfig('show_recommen'));
		$facebookwidth  = '100%';
		$facebookheight = trim($this->_getConfig('facebook_height'));
		$header 		= trim($this->_getConfig('show_header'));
		
		//get data from facebook
		$afcontent = "";
		if ( !$hostdomain ){
			$afcontent .= 'Please enter valid domain.';
		} else{
			$afcontent .='<iframe src="http://www.facebook.com/plugins/activity.php?site='.$hostdomain;
			if ( $facebookheight ){
				$afcontent .= '&amp;height='.$facebookheight;
			}
			if ($header == 1){
				$afcontent .= '&amp;header=true';
			}
			if ($header == 0){
				$afcontent .= '&amp;header=false';
			}
			if ( $styletheme ){
				$afcontent .= '&amp;colorscheme='.$styletheme;
			}
			if ( $recommen ){
				$afcontent .= '&amp;recommendations=true';
			}
			$afcontent .= '" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100%;';
			if( $styletheme == 'dark'){
				$afcontent .='background:#333;';
			}
			if ( $facebookheight ){
				$afcontent .= 'height:'.$facebookheight.'px;';
			}			
			$afcontent .= '" allowTransparency="true"></iframe>';
		}
		return $afcontent;
	}

	function getFanbox(){
		//get config from admin 
		$pageid = trim($this->_getConfig('pageid'));
		$fannumber = trim($this->_getConfig('fannumber'));
		$stream = trim($this->_getConfig('show_stream'));
		$bordercolor = trim($this->_getConfig('bordercolor_like'));
		$facebookwidth = '100%';
		$facebookheight = trim($this->_getConfig('facebook_height'));
		$header = trim($this->_getConfig('show_header'));
		
		// get data from facebook
		$fbcontent = "";
		if ( !$pageid ){
			$fbcontent.='Please enter your valid Page ID.';
		} else{
			$rd = rand(1,99999);
			$fbcontent .= '<iframe src="http://www.facebook.com/connect/connect.php?href='.$pageid;
			if ( $fannumber ){
				$fbcontent .= '&amp;connections='.$fannumber;
			}
			if ( $stream ){
				$fbcontent .= '&amp;stream=true';
			}
			if ( $header == 1){
				$fbcontent .= '&amp;header=true';
			}
			if ( $header == 0){
				$fbcontent .= '&amp;header=false';
			}
			if ( $facebookheight ){
				$fbcontent .= '&amp;height='.$facebookheight.'"';
			}
			$fbcontent .= ' scrolling="no" frameborder="0" style="overflow:hidden;border:1px solid '.'#'.$bordercolor.';';
			if ( $facebookwidth ){
				$fbcontent .= 'width:'.$facebookwidth.';';
			}
			if ( $facebookheight ){
				$fbcontent .= 'height:'.$facebookheight.'px;';
			}
			$fbcontent .= '" allowTransparency="true"></iframe>';
		}
		return $fbcontent;
	}

	function getRecommendations(){
		//get config from admin 
		$recommendomain = trim($this->_getConfig('recommendomain'));
		$facebookwidth = '100%';
		$facebookheight = trim($this->_getConfig('facebook_height'));
		$header = trim($this->_getConfig('show_header'));
		$styletheme = trim($this->_getConfig('theme_style_2'));
		
		//get data from facebook
		$rcontent = "";
		if ( !$recommendomain ){
			$rcontent .= 'Please enter valid domain';
		} else{
			$rcontent .= '<iframe src="http://www.facebook.com/plugins/recommendations.php?site='.$recommendomain;
			if ( $facebookheight ){
				$rcontent .= '&amp;height='.$facebookheight;
			}			
			if ( $header == 1){
				$rcontent .= '&amp;header=true';
			}
			if ( $header == 0){
				$rcontent .= '&amp;header=false';
			}
			if ( $styletheme ){
				$rcontent .= '&amp;colorscheme='.$styletheme;
			}
			$rcontent .= '" scrolling="no" frameborder="0" allowTransparency="true" style="width:100%;border:none; overflow:hidden;';
			if( $styletheme == 'dark'){
				$rcontent .='background:#333;';
			}
			if ( $facebookheight ){
				$rcontent .= 'height:'.$facebookheight.'px;';
			}			
			$rcontent .= '" ></iframe>';
		}
		return $rcontent;
	}		

}
