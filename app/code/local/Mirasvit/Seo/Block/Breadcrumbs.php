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


class Mirasvit_Seo_Block_Breadcrumbs extends Mage_Page_Block_Html_Breadcrumbs
{
    public function getConfig()
    {
        return Mage::getSingleton('seo/config');
    }

    function __construct()
    {
        parent::__construct();
        if (!$this->getBreadcrumbsSeparator()) {
            return;
        }
        if (Mage::registry('current_category')
            || Mage::registry('current_product')) {
            $this->_prepareBreadcrumbs();
            $this->setTemplate('seo/breadcrumbs.phtml');
        }
    }

    protected function _prepareBreadcrumbs()
    {
        $this->addCrumb('home', array(
                'label'=>Mage::helper('catalog')->__('Home'),
                'title'=>Mage::helper('catalog')->__('Go to Home Page'),
                'link'=>Mage::getBaseUrl()
            ));
        $path  = Mage::helper('seo/breadcrumbs')->getBreadcrumbPath();
        foreach ($path as $name => $breadcrumb) {
            $this->addCrumb($name, $breadcrumb);
        }
    }

    public function getBreadcrumbsSeparator()
    {
        return $this->getConfig()->getBreadcrumbsSeparator(Mage::app()->getStore()->getId());
    }

    public function checkBreadcrumbs($crumbs)
    {
        $lastProduct = false;
        foreach($crumbs as $key => $crumb) {
            if (!$lastProduct) {
                $crumbs[$key] = $crumb;
            } elseif ($lastProduct) {
                unset($crumbs[$key]);
            }
            if($key == 'product') {
                $lastProduct = true;
                $crumbs[$key]['last'] = 1;
            }
        }

        return $crumbs;
    }

}
