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


class Mirasvit_Seo_Model_Object_Page extends Mirasvit_Seo_Model_Object_Abstract
{
	protected $_page;
	protected $_parseObjects = array();

    public function _construct()
    {
        parent::_construct();
		$this->_page = Mage::getSingleton('cms/page');

		if ($this->_page) {
			$this->_parseObjects['page'] = $this->_page;
		}
		$this->_parseObjects['store'] = Mage::getModel('seo/object_store');
		$this->init();
	}

	protected function init()
    {
		if ($this->_page->getMetaTitle()) {
			$this->setMetaTitle($this->parse($this->_page->getMetaTitle()));
		}
		if ($this->_page->getMetaKeywords()) {
			$this->setMetaKeywords($this->parse($this->_page->getMetaKeywords()));
		}
		if ($this->_page->getMetaDescription()) {
			$this->setMetaDescription($this->parse($this->_page->getMetaDescription()));
		}
        if ($this->_page->getTitle()) {
			$this->setTitle($this->parse($this->_page->getTitle()));
		}
		if ($this->_page->getDescription()) {
			$this->setDescription($this->parse($this->_page->getDescription()));
		}
	}
}