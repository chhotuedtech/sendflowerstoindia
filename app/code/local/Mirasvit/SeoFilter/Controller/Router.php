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


/**
* This file is part of the Mirasvit_SeoFilter project.
*
* Mirasvit_SeoFilter is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License version 3 as
* published by the Free Software Foundation.
*
* This script is distributed in the hope that it will be useful, but WITHOUT
* ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
* FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
*
* PHP version 5
*
* @category Mirasvit_SeoFilter
* @package Mirasvit_SeoFilter
* @author Michael TÃ¼rk <tuerk@flagbit.de>
* @copyright 2012 Flagbit GmbH & Co. KG (http://www.flagbit.de). All rights served.
* @license http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
* @version 0.1.0
* @since 0.1.0
*/
/**
* Router that translates FilterUrls URLs into the default Zend_Framework router's version.
* FilterUrls URLs have a pre-defined structure
* <category-rewrite-without-suffix>/<option_label_1>-<option_label_2><url-suffix>
*
* The router tries to parse the given pathinfo using the parser model and sets the parameters if the parsing was
* successful. On success the whole request is dispatched and the routing process is complete.
*
* @category Mirasvit_SeoFilter
* @package Mirasvit_SeoFilter
* @author Michael TÃ¼rk <tuerk@flagbit.de>
* @copyright 2012 Flagbit GmbH & Co. KG (http://www.flagbit.de). All rights served.
* @license http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
* @version 0.1.0
* @since 0.1.0
*/
class Mirasvit_SeoFilter_Controller_Router extends Mage_Core_Controller_Varien_Router_Standard
{

    public function getConfig() {
        return Mage::getModel('seofilter/config');
    }

    /**
     * Helper function to register the current router at the front controller.
     *
     * @param Varien_Event_Observer $observer The event observer for the controller_front_init_routers event
     * @event controller_front_init_routers
     */
    public function addFilterUrlsRouter($observer)
    {
        if (!$this->getConfig()->isEnabled()) {
            return;
        }

        $front = $observer->getEvent()->getFront();
        $filterUrlsRouter = new Mirasvit_SeoFilter_Controller_Router();
        $front->addRouter('seofilter', $filterUrlsRouter);
    }

    /**
     * Rewritten function of the standard controller. Tries to match the pathinfo on url parameters.
     *
     * @see Mage_Core_Controller_Varien_Router_Standard::match()
     * @param Zend_Controller_Request_Http $request The http request object that needs to be mapped on Action Controllers.
     */
    public function match(Zend_Controller_Request_Http $request)
    {
        if (!Mage::isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getUrl('install'))
                ->sendResponse();
            exit;
        }

        $identifier = urldecode(trim($request->getPathInfo(), '/'));

        // try to gather url parameters from parser.
        $parser = Mage::getModel('seofilter/parser');
        $parsedRequestInfo = $parser->parseFilterInformationFromRequest($identifier, Mage::app()->getStore()->getId());

        if (!$parsedRequestInfo) {
            return false;
        }

        // if successfully gained url parameters, use them and dispatch ActionController action
        $request->setRouteName('catalog')
            ->setModuleName('catalog')
            ->setControllerName('category')
            ->setActionName('view')
            ->setParam('id', $parsedRequestInfo['categoryId']);
        $pathInfo = 'catalog/category/view/id/' . $parsedRequestInfo['categoryId'];
        $requestUri = '/' . $pathInfo . '?';

        foreach ($parsedRequestInfo['additionalParams'] as $paramKey => $paramValue) {
            //@dva fix
            if (is_array($paramValue)) {
                $paramValue = implode($paramValue, '_');
            }
            $requestUri .= $paramKey . '=' . $paramValue . '&';
        }

        $controllerClassName = $this->_validateControllerClassName('Mage_Catalog', 'category');
        $controllerInstance = Mage::getControllerInstance($controllerClassName, $request, $this->getFront()->getResponse());

        $request->setRequestUri(substr($requestUri, 0, -1));
        $request->setPathInfo($pathInfo);

        // dispatch action
        $request->setDispatched(true);
        $controllerInstance->dispatch('view');

        $request->setAlias(
            Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
            $identifier
        );

        return true;
    }
}