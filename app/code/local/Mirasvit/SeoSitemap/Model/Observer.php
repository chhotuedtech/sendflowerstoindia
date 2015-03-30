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


class Mirasvit_SeoSitemap_Model_Observer
{

	public function getConfig()
	{
		return Mage::getSingleton('seositemap/config');
	}

    public function registerUrlRewrite()
    {
        //echo $this->getConfig()->getFrontendSitemapBaseUrl();die;
    	Mage::helper('mstcore/urlrewrite')->rewriteMode('SEOSITEMAP', true);
        Mage::helper('mstcore/urlrewrite')->registerBasePath('SEOSITEMAP', $this->getConfig()->getFrontendSitemapBaseUrl());
        Mage::helper('mstcore/urlrewrite')->registerPath('SEOSITEMAP', 'MAP', '', 'seositemap_index_index');
    }
}