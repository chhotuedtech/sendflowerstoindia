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



class Mirasvit_SeoSitemap_Model_Pager_Collection extends Varien_Object
{
    protected $pageSize;
    protected $currentPage;
    protected $mode;
    public function getSize()
    {
        return $this->getProductCollection()->getSize() + $this->getCategoryCollection()->getSize();
    }

    public function getLastPageNumber()
    {
    	return $this->getProductCollection()->getLastPageNumber() + $this->getCategoryCollection()->getLastPageNumber();
    }

    public function getCurPage()
    {
    	return $this->currentPage;
    }

    public function setPageSize($page)
    {
        $this->pageSize = $page;
    	$this->getProductCollection()->setPageSize($page);
    	$this->getCategoryCollection()->setPageSize($page);
    	return $this;
    }

    public function setCurPage($page)
    {
        $this->currentPage = $page;

        if ($page <= $this->getCategoryCollection()->getLastPageNumber()) {
            $this->getCategoryCollection()->setCurPage($page);
            $this->mode = 'show_categories';
        } else {
            $prodPage = $page - ceil($this->getCategoryCollection()->getSize()/$this->pageSize);

    	   $this->getProductCollection()->setCurPage($prodPage);
           $this->mode = 'show_products';
        }
    	return $this;
    }

    public function count()
    {
        return $this->getProductCollection()->count() + $this->getCategoryCollection()->count();
    }

    public function getMode()
    {
        return $this->mode;
    }
}