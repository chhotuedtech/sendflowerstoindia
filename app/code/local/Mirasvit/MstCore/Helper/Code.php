<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at http://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   Advanced SEO Suite
 * @version   1.1.1
 * @build     803
 * @copyright Copyright (C) 2014 Mirasvit (http://mirasvit.com/)
 */


class Mirasvit_MstCore_Helper_Code extends Mage_Core_Helper_Data { const EE_EDITION = 'EE'; const CE_EDITION = 'CE'; protected static $_edition = false; protected $k; protected $s; protected $l; protected $v; protected $b; protected $d; public function getStatus($spb854e6 = null) { if ($spb854e6) { $sp24c1e2 = $this->spcd5ffa($spb854e6); $sp943e5b = $this->sp6292fe($sp24c1e2); if ($sp943e5b) { if (get_class($sp943e5b) !== 'Mirasvit_MstCore_Helper_Code') { return $sp943e5b->getStatus(null); } else { return true; } } else { return 'Wrong extension package!'; } } else { return $this->sp5e5904(); } return true; } public function getOurExtensions() { $sp793674 = array(); foreach (Mage::getConfig()->getNode('modules')->children() as $sp816d7c => $sp24c1e2) { if ($sp24c1e2->active != 'true') { continue; } if (strpos($sp816d7c, 'Mirasvit_') === 0) { if ($sp816d7c == 'Mirasvit_MstCore' || $sp816d7c == 'Mirasvit_MCore') { continue; } $sp2d0d68 = explode('_', $sp816d7c); if ($sp943e5b = $this->sp6292fe($sp2d0d68[1])) { if (method_exists($sp943e5b, '_sku') && method_exists($sp943e5b, '_version') && method_exists($sp943e5b, '_build') && method_exists($sp943e5b, '_key')) { $sp793674[] = array('s' => $sp943e5b->_sku(), 'v' => $sp943e5b->_version(), 'r' => $sp943e5b->_build(), 'k' => $sp943e5b->_key()); } } } } return $sp793674; } private function sp6292fe($sp1234d0) { $spa5a1a7 = Mage::getBaseDir() . '/app/code/local/Mirasvit/' . $sp1234d0 . '/Helper/Code.php'; if (file_exists($spa5a1a7)) { $sp943e5b = Mage::helper(strtolower($sp1234d0) . '/code'); return $sp943e5b; } return false; } private function spcd5ffa($spb854e6) { if (is_object($spb854e6)) { $spb854e6 = get_class($spb854e6); } $spb854e6 = explode('_', $spb854e6); if (isset($spb854e6[1])) { return $spb854e6[1]; } return false; } private function sp5e5904() { $sp15fe08 = false; $spff9696 = $this->sp8c850d(); $sp9d0a89 = $this->sp7b2ffb(); if (strpos($spff9696, '127.') !== false || strpos($spff9696, '192.') !== false) { $sp15fe08 = true; } $spd656bc = $this->sp09859d(); if (!$spd656bc) { $this->spe05fe4(); $spd656bc = $this->sp09859d(); } if ($spd656bc && isset($spd656bc['status'])) { if ($spd656bc['status'] === 'active') { if (abs(time() - $spd656bc['time']) > 24 * 60 * 60) { $this->spe05fe4(); } $sp15fe08 = true; } else { $this->spe05fe4(); $sp15fe08 = $spd656bc['message']; } } return $sp15fe08; } private function sp09859d() { $sp3ae407 = 'mstcore_' . $this->spefcd96(); $sp751266 = Mage::getModel('core/flag'); $sp751266->load($sp3ae407, 'flag_code'); if ($sp751266->getFlagData()) { $spd656bc = @unserialize(@base64_decode($sp751266->getFlagData())); if (is_array($spd656bc)) { return $spd656bc; } } return false; } private function sp71d437($spd656bc) { $sp3ae407 = 'mstcore_' . $this->spefcd96(); $sp751266 = Mage::getModel('core/flag'); $sp751266->load($sp3ae407, 'flag_code'); $spd656bc = base64_encode(serialize($spd656bc)); $sp751266->setFlagCode($sp3ae407)->setFlagData($spd656bc); $sp751266->getResource()->save($sp751266); return $this; } private function spe05fe4() { $spb32b99 = array(); $spb32b99['v'] = 3; $spb32b99['d'] = $this->sp7b2ffb(); $spb32b99['ip'] = $this->sp8c850d(); $spb32b99['mv'] = Mage::getVersion(); $spb32b99['me'] = $this->sp0c10e5(); $spb32b99['l'] = $this->spefcd96(); $spb32b99['k'] = $this->_key(); $spb32b99['uid'] = $this->spe8c56e(); $spedea58 = @unserialize($this->spba5cae('http://mirasvit.com/lc/check/', $spb32b99)); if (isset($spedea58['status'])) { $spedea58['time'] = time(); $this->sp71d437($spedea58); } return $this; } private function spba5cae($sp65b556, $spb32b99) { $spe211a4 = new Varien_Http_Adapter_Curl(); $spe211a4->write(Zend_Http_Client::POST, $sp65b556, '1.1', array(), http_build_query($spb32b99, '', '&')); $spd656bc = $spe211a4->read(); $spd656bc = preg_split('/^\\r?$/m', $spd656bc, 2); $spd656bc = trim($spd656bc[1]); return $spd656bc; } private function sp8c850d() { return Mage::helper('core/http')->getServerAddr(false); } private function sp7b2ffb() { return Mage::helper('core/url')->getCurrentUrl(); } private function sp0c10e5() { if (!self::$_edition) { $sp42f68f = BP . DS . 'app' . DS . 'etc' . DS . 'modules' . DS . 'Enterprise' . '_' . 'Enterprise' . '.xml'; $sp5850eb = BP . DS . 'app' . DS . 'code' . DS . 'core' . DS . 'Enterprise' . DS . 'Enterprise' . DS . 'etc' . DS . 'config.xml'; $sp6d2285 = !file_exists($sp42f68f) || !file_exists($sp5850eb); if ($sp6d2285) { self::$_edition = self::CE_EDITION; } else { self::$_edition = self::EE_EDITION; } } return self::$_edition; } public function _key() { return $this->k; } public function _sku() { return $this->s; } private function spefcd96() { return $this->l; } public function _version() { return $this->v; } public function _build() { return $this->b; } private function sp15d8a9() { return $this->d; } private function spe8c56e() { $sp6f1ce2 = Mage::getConfig()->getResourceConnectionConfig('core_read'); return md5($sp6f1ce2->dbname . $sp6f1ce2->dbhost); } public function onControllerActionPredispatch($spcaf9a5) { } public function onModelSaveBefore($spcaf9a5) { } public function onCoreBlockAbtractToHtmlAfter($spcaf9a5) { $sp1088e6 = $spcaf9a5->getBlock(); if (is_object($sp1088e6) && substr(get_class($sp1088e6), 0, 9) == 'Mirasvit_') { $sp15fe08 = $this->getStatus(get_class($sp1088e6)); if ($sp15fe08 !== true) { $spcaf9a5->getTransport()->setHtml("<ul class='messages'><li class='error-msg'><ul><li>{$sp15fe08}</li></ul></li></ul>"); } } } }