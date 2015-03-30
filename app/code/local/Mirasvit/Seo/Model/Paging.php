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


class Mirasvit_Seo_Model_Paging extends Mage_Core_Model_Abstract
{
    protected $_productCollection;
    protected $_toolbar;

    public function _construct()
    {
        $this->_initProductCollection();
        parent::_construct();
    }

    protected function _initProductCollection()
    {
        if ($layer = Mage::getSingleton('catalog/layer')) {
            $this->_productCollection = $layer->getProductCollection();

            $limit = (int)$this->_getToolbar()->getLimit();
            if ($limit) {
                $this->_productCollection->setPageSize($limit);
            }
        }

        return $this;
    }

    protected function _getToolbar()
    {
        if (is_null($this->_toolbar)) {
            $this->_toolbar = Mage::app()->getLayout()->createBlock('catalog/product_list_toolbar');
        }

        return $this->_toolbar;
    }

    protected function _getPager()
    {
        return Mage::app()->getLayout()->createBlock('page/html_pager')
            ->setLimit($this->_getToolbar()->getLimit())
            ->setCollection($this->_productCollection);
    }

    public function createLinks()
    {
        $pager     = $this->_getPager();
        $numPages  = count($pager->getPages());
        $headBlock = Mage::app()->getLayout()->getBlock('head');

        $previousPageUrl = $pager->getPreviousPageUrl();
        $nextPageUrl     = $pager->getNextPageUrl();

        if (Mage::helper('mstcore')->isModuleInstalled('Amasty_Shopby')) {
            $url = Mage::helper('core/url')->getCurrentUrl();
            $url = strtok($url, '?');
            $previousPageUrl = $url.'?p='.($pager->getCurrentPage() - 1);
            $nextPageUrl = $url.'?p='.($pager->getCurrentPage() + 1);
            if ($pager->getCurrentPage() == 2) {
                $previousPageUrl = $url;
            }
        }

        //we have html_entity_decode because somehow manento encodes '&'
        if (!$pager->isFirstPage() && !$pager->isLastPage() && $numPages > 2 ) {
            $headBlock->addLinkRel('prev', html_entity_decode($previousPageUrl));
            $headBlock->addLinkRel('next', html_entity_decode($nextPageUrl));
        } elseif($pager->isFirstPage() && $numPages > 1) {
            $headBlock->addLinkRel('next', html_entity_decode($nextPageUrl));
        } elseif($pager->isLastPage() && $numPages > 1) {
            $headBlock->addLinkRel('prev', html_entity_decode($previousPageUrl));
        }

        return $this;
    }
}
