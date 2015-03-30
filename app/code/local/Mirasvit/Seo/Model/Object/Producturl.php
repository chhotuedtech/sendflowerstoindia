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


class Mirasvit_Seo_Model_Object_ProductUrl extends Mirasvit_Seo_Model_Object_Abstract
{
	protected $_product;
	protected $_parseObjects = array();

    public function _construct()
    {
        parent::_construct();
		$this->init();
	}

	public function setProduct($product) {
	    $this->_product = $product;
	    $this->_parseObjects['product'] = $this->_product;
	    return $this;
	}

	public function setStore($store) {
	   $this->_parseObjects['store'] = $store;
       $this->_store = $store;
	   return $this;
	}

	protected function init()
    {

	}

	public function parse($template) {
	    return parent::parse($template);
	}
}
