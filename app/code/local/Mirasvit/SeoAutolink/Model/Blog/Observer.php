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


class Mirasvit_SeoAutolink_Model_Blog_Observer extends Varien_Object {
    protected $_isBlogPage  = false;

    public function __construct() {
        $blogAction = array('blog_index_list', 'blog_cat_view', 'blog_post_view');
        if(in_array(Mage::app()->getFrontController()->getAction()->getFullActionName(), $blogAction)) {
            $this->_isBlogPage = true;
        }
    }

    public function addAutoLinksToBlog($e) {
        if ($this->_isBlogPage
            && Mage::getSingleton('seoautolink/config')->getIsEnableLinksForBlog(Mage::app()->getStore()->getStoreId())) {
                if ($e->getData('block')->getNameInLayout() == "content") {
                    $html = $e->getData('transport')->getHtml();
                    $callback = new Mirasvit_SeoAutolink_Model_Blog_Observer_Callback();
                    $html = preg_replace_callback('/(<div class="postContent">(.*?)<div class="tags">)/ims',
                        array($callback, 'callback'),
                        $html
                    );
                    $e->getData('transport')->setHtml($html);
                }
        }
    }
}

class Mirasvit_SeoAutolink_Model_Blog_Observer_Callback {
    public function callback($matches) {
       return Mage::helper('seoautolink')->addLinks($matches[0]);
    }
}