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
if ($version == '0.1.5') {
    return;
} elseif ($version != '0.1.4') {
    die("Please, run migration 0.1.4");
}

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->startSetup();

//PRODUCT
$setup->addAttribute('catalog_product', 'seo_category', array(
    'group'         => 'Meta Information',
    'input'                      => 'select',
    'type'                       => 'int',
    'source'                     => 'seo/system_config_source_category',
    'frontend'                   => 'seo/system_config_frontend_category',
    'label'         => 'Main Category for SEO',
    'backend'       => '',
    'visible'       => 1,
    'required'      => 0,
    'user_defined' => 1,
    'sort_order' => 100000,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));


$installer->endSetup();