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


class Mirasvit_Seo_Controller_Router extends Mage_Core_Controller_Varien_Router_Standard
{
    public function getConfig()
    {
        return Mage::getModel('seofilter/config');
    }

    public function addSeoUrlsRouter($observer)
    {
        $helper = Mage::helper('mstcore');

        if (!$helper) {
            return;
        }

        $front = $observer->getEvent()->getFront();
        $seoUrlsRouter = new Mirasvit_Seo_Controller_Router();
        $front->addRouter('seo', $seoUrlsRouter);
    }

    public function match(Zend_Controller_Request_Http $request)
    {
        if (!Mage::isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getUrl('install'))
                ->sendResponse();
            exit;
        }

        $identifier = trim($request->getPathInfo(), '/');
        $parts      = explode('/', $identifier);
        if (isset($parts[1]) && $parts[1] == 'reviews') {
            $product = Mage::getModel('catalog/product')->loadByAttribute('url_key', $parts[0]);

            if (isset($parts[2])) {
                $p  = explode('-', $parts[2]);
                $id = (int)end($p);

                $request->setRouteName('review')
                    ->setModuleName('review')
                    ->setControllerName('product')
                    ->setActionName('view')
                    ->setParam('id', $id)
                    ->setAlias(
                        Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
                        'reviews'
                    );
	            return true;
            } else {
                if ($product) {
                    $request->setRouteName('review')
                        ->setModuleName('review')
                        ->setControllerName('product')
                        ->setActionName('list')
                        ->setParam('id', $product->getId())
                        ->setAlias(
                            Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
                            'reviews'
                        );
	               return true;
                }
            }
        } elseif ($parts[0] == 'tag' && isset($parts[1])) {
            $p  = explode('-', $parts[1]);
            $id = (int)end($p);
            $request->setRouteName('tag')
                ->setModuleName('tag')
                ->setControllerName('product')
                ->setActionName('list')
                ->setParam('tagId', $id)
                ->setAlias(
                    Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
                    'tags'
                );

            return true;
        }

        return false;
    }
}
