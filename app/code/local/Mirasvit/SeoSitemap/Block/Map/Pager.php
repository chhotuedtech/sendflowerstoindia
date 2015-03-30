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



class Mirasvit_SeoSitemap_Block_Map_Pager extends Mage_Page_Block_Html_Pager
{
    protected $mode;

    public function getMode()
    {
        return $this->getCollection()->getMode();
    }

    public function setCollection($collection)
    {
        if ((int) $this->getLimit()) {
            $collection->setPageSize($this->getLimit());
        }
    	parent::setCollection($collection);
    }

    public function getPreviousPageUrl()
    {
        return $this->getPageUrl($this->getCollection()->getCurPage()-1);
    }

    public function getNextPageUrl()
    {
        return $this->getPageUrl($this->getCollection()->getCurPage()+1);
    }

    public function getPagerUrl($params=array())
    {
        $url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

        if (count($params) > 0 && $params['p'] != 1) {
            $query = http_build_query($params);
            $url .= '?'.$query;
        }
        return $url;
    }
}

