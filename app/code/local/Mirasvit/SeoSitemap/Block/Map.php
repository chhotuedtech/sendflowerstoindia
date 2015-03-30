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



class Mirasvit_SeoSitemap_Block_Map extends Mage_Core_Block_Template
{
    protected $categoriesTree = array();
    protected $_itemLevelPositions = array();



    public function getConfig() {
        return Mage::getSingleton('seositemap/config');
    }

    public function getExcludeLinks() {
        return $this->getConfig()->getExcludeLinks();
    }

    protected function _prepareLayout()
    {
        if ($headBlock = $this->getLayout()->getBlock('head')) {
            $headBlock->setTitle($this->getConfig()->getFrontendSitemapMetaTitle());
            $headBlock->setKeywords($this->getConfig()->getFrontendSitemapMetaKeywords());
            $headBlock->setDescription($this->getConfig()->getFrontendSitemapMetaDescription());
        }
        $collection = Mage::getModel('seositemap/pager_collection')
            ->setProductCollection($this->getProductLimitedCollection())
            ->setCategoryCollection($this->getCategoryLimitedCollection())
            ;
        if ($this->getLimitPerPage()) {
            $pagerBlock = $this->getLayout()->createBlock('seositemap/map_pager', 'map.pager')
                            ->setShowPerPage(false)
                            ->setShowAmounts(false)
                            ;
            $pagerBlock
                ->setLimit($this->getLimitPerPage())
                ->setCollection($collection)
            ;
            $this->append($pagerBlock);
        }
        $this->setCollection($collection);
        return parent::_prepareLayout();
    }


    //BEGIN LIMITED MODE FUNCTIONS

    public function getIsFirstPage()
    {
        if (!$this->getLimitPerPage()) {
            return true;
        }
        return $this->getCollection()->getCurPage() == 1;
    }

    public function getMode()
    {
        return $this->getCollection()->getMode();
    }

    public function getLimitPerPage()
    {
        return (int)$this->getConfig()->getFrontendLinksLimit();
    }

    protected $categories;
    public function getCategoryLimitedCollection()
    {
        if (!$this->categories) {
            $this->categories = Mage::getModel('catalog/category')->getCollection()
                ->addAttributeToSelect('*')
                ->addFieldToFilter('entity_id', array('neq' => Mage::app()->getStore()->getRootCategoryId()))
                ->addFieldToFilter('is_active', true)
                ;
        }
        return $this->categories;
    }

    protected $products;
    public function getProductLimitedCollection()
    {
        if (!$this->products){
            $this->products = Mage::getResourceModel('catalog/product_collection')
                ->addStoreFilter()
                ->addAttributeToFilter('visibility', array('neq' => 1))
                ->addAttributeToFilter('status', 1)
                ->addAttributeToSelect('*')
                ->addUrlRewrite();
        }
        return $this->products;
    }

    //END LIMITED MODE FUNCTIONS

    public function getH1Title()
    {
        return $this->getConfig()->getFrontendSitemapH1();
    }



    protected function _getCategoriesTree($category, $level = 0, $isLast = false, $isFirst = false,
        $isOutermost = false, $outermostItemClass = '', $childrenWrapClass = '', $noEventAttributes = false)
    {
        if (!$category->getIsActive()) {
            return '';
        }
        $html = array();

        // get all children
        if (Mage::helper('catalog/category_flat')->isEnabled()) {
            $children = (array)$category->getChildrenNodes();
            $childrenCount = count($children);
        } else {
            $children = $category->getChildren();
            $childrenCount = $children->count();
        }
        $hasChildren = ($children && $childrenCount);

        // select active children
        $activeChildren = array();
        foreach ($children as $child) {
            if ($child->getIsActive()) {
                $activeChildren[] = $child;
            }
        }
        $activeChildrenCount = count($activeChildren);
        $hasActiveChildren = ($activeChildrenCount > 0);

        $categories = array();

        $j = 0;
        foreach ($activeChildren as $child) {
            if (!Mage::helper('seositemap')->checkArrayPattern($this->getCategoryUrl($child), $this->getExcludeLinks())) {
                $this->categoriesTree[] = $child;
            } else {
                $arrKey = count($this->categoriesTree);
                if ($arrKey > 0) $arrKey = $arrKey - 1;
                $this->categoriesTree[$arrKey . '-hidden'] = $child;
            }
            $this->_getCategoriesTree(
                $child,
                ($level + 1),
                ($j == $activeChildrenCount - 1),
                ($j == 0),
                false,
                $outermostItemClass,
                $childrenWrapClass,
                $noEventAttributes
            );
            $j++;
        }
    }

    protected function _getCategoryInstance()
    {
        if (is_null($this->_categoryInstance)) {
            $this->_categoryInstance = Mage::getModel('catalog/category');
        }
        return $this->_categoryInstance;
    }

    /**
     * Get url for category data
     *
     * @param Mage_Catalog_Model_Category $category
     * @return string
     */
    public function getCategoryUrl($category)
    {
        if ($category instanceof Mage_Catalog_Model_Category) {
            $url = $category->getUrl();
        } else {
            $url = $this->_getCategoryInstance()
                ->setData($category->getData())
                ->getUrl();
        }

        return $url;
    }

    public function getStoreCategories()
    {
        $helper = Mage::helper('catalog/category');
        return $helper->getStoreCategories();
    }

   /**
     * Return item position representation in menu tree
     *
     * @param int $level
     * @return string
     */
    protected function _getItemPosition($level)
    {
        if ($level == 0) {
            $zeroLevelPosition = isset($this->_itemLevelPositions[$level]) ? $this->_itemLevelPositions[$level] + 1 : 1;
            $this->_itemLevelPositions = array();
            $this->_itemLevelPositions[$level] = $zeroLevelPosition;
        } elseif (isset($this->_itemLevelPositions[$level])) {
            $this->_itemLevelPositions[$level]++;
        } else {
            $this->_itemLevelPositions[$level] = 1;
        }

        $position = array();
        for($i = 0; $i <= $level; $i++) {
            if (isset($this->_itemLevelPositions[$i])) {
                $position[] = $this->_itemLevelPositions[$i];
            }
        }
        return implode('-', $position);
    }


    /**
     * Render categories menu in HTML
     *
     * @param int Level number for list item class to start from
     * @param string Extra class of outermost list items
     * @param string If specified wraps children list in div with this class
     * @return string
     */
    public function getCategoriesTree($level = 0, $outermostItemClass = '', $childrenWrapClass = '')
    {
        $activeCategories = array();
        foreach ($this->getStoreCategories() as $child) {
            if ($child->getIsActive()) {
                $activeCategories[] = $child;
            }
        }
        $activeCategoriesCount = count($activeCategories);
        $hasActiveCategoriesCount = ($activeCategoriesCount > 0);

        if (!$hasActiveCategoriesCount) {
            return '';
        }

        $j = 0;
        foreach ($activeCategories as $category) {
            if (!Mage::helper('seositemap')->checkArrayPattern($this->getCategoryUrl($category), $this->getExcludeLinks())) {
                $this->categoriesTree[] = $category;
            } else {
                $arrKey = count($this->categoriesTree);
                if ($arrKey > 0) $arrKey = $arrKey - 1;
                $this->categoriesTree[$arrKey . '-hidden'] = $category;
            }
            $this->_getCategoriesTree(
                $category,
                $level,
                ($j == $activeCategoriesCount - 1),
                ($j == 0),
                true,
                $outermostItemClass,
                $childrenWrapClass,
                true
            );
            $j++;
        }

        return $this->categoriesTree;
    }

    public function getSitemapProductCollection($category) {
        $category = $this->_getCategoryInstance()
                    ->setData($category->getData());
        $collection = Mage::getResourceModel('catalog/product_collection')
            ->addStoreFilter()
            ->addCategoryFilter($category)
            ->addAttributeToFilter('visibility', array('neq' => 1))
            ->addAttributeToFilter('status', 1)
            ->addAttributeToSelect('*')
            ->addUrlRewrite();
        return $collection;
    }

    public function excludeCategory($category)
    {
        return Mage::helper('seositemap')->checkArrayPattern($category->getUrl(), $this->getExcludeLinks());
    }

    public function excludeProduct($product)
    {
        return Mage::helper('seositemap')->checkArrayPattern($product->getProductUrl(), $this->getExcludeLinks());
    }

    public function getCmsPages() {
        $ignore = $this->getConfig()->getIgnoreCmsPages();
        $collection = Mage::getModel('cms/page')->getCollection()
                         ->addStoreFilter(Mage::app()->getStore())
                         ->addFieldToFilter('is_active', true)
                         ->addFieldToFilter('identifier', array('nin' => $ignore))
                         ;
        return $collection;
    }

    public function getCmsPageUrl($page) {
        return Mage::getUrl(null, array('_direct' => $page->getIdentifier()));
    }

    public function getStores() {
        return Mage::app()->getStores();
    }

    public function getIsHidden($key) {
        if (!strpos($key, 'hidden')) {
            return false;
        }

        return true;
    }

    //AWblog
    public function getIsShowAWblog() {
        if (Mage::helper('mstcore')->isModuleInstalled('AW_Blog')) {
            return true;
        }

        return false;
    }

    public function getAWblog() {
        $collection = Mage::getModel('blog/blog')->getCollection()->addStoreFilter(Mage::app()->getStore()->getStoreId());
        Mage::getSingleton('blog/status')->addEnabledFilterToCollection($collection);

        return $collection;
    }

    protected $awblogRoute;
    public function getAWblogRoute() {
        if (!$this->awblogRoute) {
            $this->awblogRoute = Mage::getStoreConfig('blog/blog/route');
            if ($this->awblogRoute == "") {
                $this->awblogRoute = "blog";
            }
        }

        return $this->awblogRoute;
    }

    public function getAWblogUrl($blogItem) {
        $baseUrl = Mage::app()->getStore(Mage::app()->getStore()->getStoreId())->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);

        return $baseUrl . $this->getAWblogRoute() . DS . $blogItem->getIdentifier();
    }

    //Amasty_Xlanding
    public function getIsAmastyXlanding() {
        if (Mage::helper('mstcore')->isModuleInstalled('Amasty_Xlanding')) {
            return true;
        }

        return false;
    }

    public function getAmastyXlanding() {
        $select = Mage::getModel('amlanding/page')->getCollection()
                    ->addFieldToFilter('is_active', 1)
                    ->getSelect()
                    ->join(
        array('amlanding_page_store' => Mage::getSingleton('core/resource')->getTableName('amlanding/page_store')),
        'main_table.page_id = amlanding_page_store.page_id',
        array())
        ->where('amlanding_page_store.store_id IN (?)', array(Mage::app()->getStore()->getStoreId()));

        $query = Mage::getSingleton('core/resource')->getConnection('core_write')->query($select);

        $xlandingUrls = array();
        $baseUrl = Mage::app()->getStore(Mage::app()->getStore()->getStoreId())->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);
        while ($row = $query->fetch()) {
            $xlandingUrls[] = array(
                'url'  => $baseUrl . $row['identifier'] . DS,
                'name' => $row['title'],
            );

        }

        return $xlandingUrls;
    }

    //Magpleasure_Blog
    public function getIsMagpleasureBlog() {
        if (Mage::helper('mstcore')->isModuleInstalled('Magpleasure_Blog')) {
            return true;
        }

        return false;
    }

    public function getMagpleasureBlog() {
        return $this->generateLinks();
    }

    const MPBLOG_TYPE_BLOG = 'blog';
    const MPBLOG_TYPE_POST = 'post';
    const MPBLOG_TYPE_CATEGORY = 'category';
    const MPBLOG_TYPE_ARCHIVE = 'tag';

    protected function _helper()
    {
        return Mage::helper('mpblog');
    }

    public function generateLinks()
    {
        $links = array();

        $storeId = Mage::app()->getStore()->getStoreId();
        $currentDate = Mage::getSingleton('core/date')->gmtDate('Y-m-d');
        $includedParts = $this->_helper()->getSitemapIncluded($storeId ? $storeId : null);

        # Base Blog URL:
        if (in_array(self::MPBLOG_TYPE_BLOG, $includedParts)){
            $links[] = array(
                'url'  => $this->_helper()->_url($storeId)->getUrl(),
                'name' => ($blogTitle = Mage::getStoreConfig('blog/blog/title')) ? $blogTitle : 'Base Blog URL',
            );
        }

        # Import Posts
        if (in_array(self::MPBLOG_TYPE_POST, $includedParts)){

            /** @var Magpleasure_Blog_Model_Mysql4_Post_Collection $posts  */
            $posts = Mage::getModel('mpblog/post')->getCollection();
            if (!Mage::app()->isSingleStoreMode()){
                $posts->addStoreFilter($storeId);

            }

            $posts
                ->setDateOrder()
                ->addFieldToFilter('status', Magpleasure_Blog_Model_Post::STATUS_ENABLED)
            ;

            foreach ($posts as $post){

                /** @var $post Magpleasure_Blog_Model_Post */
                $post->setStoreId($storeId);
                $links[] = array(
                    'url'  => $post->getPostUrl(),
                    'name' => $post->getTitle(),
                );
            }
        }

        # Import Categories
        if (in_array(self::MPBLOG_TYPE_CATEGORY, $includedParts)){

            /** @var Magpleasure_Blog_Model_Mysql4_Category_Collection $categories  */
            $categories = Mage::getModel('mpblog/category')->getCollection();
            if (!Mage::app()->isSingleStoreMode()){
                $categories->addStoreFilter($storeId);
            }

            $categories
                ->setSortOrder('asc')
                ->addFieldToFilter('status', Magpleasure_Blog_Model_Category::STATUS_ENABLED)
            ;

            foreach ($categories as $category){

                /** @var $category Magpleasure_Blog_Model_Category */
                $category->setStoreId($storeId);
                $links[] = array(
                    'url'  => $category->getCategoryUrl(),
                    'name' => $category->getName(),
                );
            }
        }

        # Import Archives
        if (in_array(self::MPBLOG_TYPE_ARCHIVE, $includedParts)){

            /** @var array $archives  */
            $archives = Mage::getModel('mpblog/archive')->getArchives($storeId);

            foreach ($archives as $archive){
                /** @var $archive Magpleasure_Blog_Model_Archive */
                $archive->setStoreId($storeId);
                $links[] = array(
                    'url'  => $archive->getArchiveUrl(),
                    'name' => $archive->getLabel(),
                );
            }
        }

        return $links;
    }
}