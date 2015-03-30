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



$installer = $this;
$version = Mage::helper('mstcore/version')->getModuleVersionFromDb('seo');
if ($version == '0.1.4') {
    return;
} elseif ($version != '0.1.3') {
    die("Please, run migration 0.1.3");
}

$installer->startSetup();
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$setup->updateAttribute('catalog_product', 'url_key', 'note', 'Leave blank to use Product URL Key Template');
$installer->endSetup();
Mage::getSingleton('catalog/url')->refreshRewrites();