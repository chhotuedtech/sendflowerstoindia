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
if ($version == '0.1.0') {
    return;
}

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->startSetup();

//PRODUCT
$setup->addAttribute('catalog_category', 'product_meta_title_tpl', array(
    'group'         => 'SEO',
    'input'         => 'text',
    'type'          => 'text',
    'label'         => 'Child Products Meta Title',
    'backend'       => '',
    'visible'       => 1,
    'required'      => 0,
    'user_defined' => 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));

$setup->addAttribute('catalog_category', 'product_meta_description_tpl', array(
    'group'         => 'SEO',
    'input'         => 'textarea',
    'type'          => 'text',
    'label'         => 'Child Products Meta Description',
    'backend'       => '',
    'visible'       => 1,
    'required'      => 0,
    'user_defined' => 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));

$setup->addAttribute('catalog_category', 'product_meta_keywords_tpl', array(
    'group'         => 'SEO',
    'input'         => 'textarea',
    'type'          => 'text',
    'label'         => 'Child Products Meta Keywords',
    'backend'       => '',
    'visible'       => 1,
    'required'      => 0,
    'user_defined' => 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));

$setup->addAttribute('catalog_category', 'product_title_tpl', array(
    'group'         => 'SEO',
    'input'         => 'text',
    'type'          => 'text',
    'label'         => 'Child Products H1',
    'backend'       => '',
    'visible'       => 1,
    'required'      => 0,
    'user_defined' => 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));

$setup->addAttribute('catalog_category', 'product_description_tpl', array(
    'group'         => 'SEO',
    'input'         => 'textarea',
    'type'          => 'text',
    'label'         => 'Child Products SEO description',
    'backend'       => '',
    'visible'       => 1,
    'required'      => 0,
    'user_defined'  => 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));

//CATEGORY
$setup->addAttribute('catalog_category', 'category_meta_title_tpl', array(
    'group'         => 'SEO',
    'input'         => 'text',
    'type'          => 'text',
    'label'         => 'Child Categories Meta Title',
    'backend'       => '',
    'visible'       => 1,
    'required'      => 0,
    'user_defined' => 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));

$setup->addAttribute('catalog_category', 'category_meta_description_tpl', array(
    'group'         => 'SEO',
    'input'         => 'textarea',
    'type'          => 'text',
    'label'         => 'Child Categories Meta Description',
    'backend'       => '',
    'visible'       => 1,
    'required'      => 0,
    'user_defined' => 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));

$setup->addAttribute('catalog_category', 'category_meta_keywords_tpl', array(
    'group'         => 'SEO',
    'input'         => 'textarea',
    'type'          => 'text',
    'label'         => 'Child Categories Meta Keywords',
    'backend'       => '',
    'visible'       => 1,
    'required'      => 0,
    'user_defined' => 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));

$setup->addAttribute('catalog_category', 'category_title_tpl', array(
    'group'         => 'SEO',
    'input'         => 'text',
    'type'          => 'text',
    'label'         => 'Child Categories H1',
    'backend'       => '',
    'visible'       => 1,
    'required'      => 0,
    'user_defined' => 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));

$setup->addAttribute('catalog_category', 'category_description_tpl', array(
    'group'         => 'SEO',
    'input'         => 'textarea',
    'type'          => 'text',
    'label'         => 'Child Categories SEO description',
    'backend'       => '',
    'visible'       => 1,
    'required'      => 0,
    'user_defined'  => 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));

// FILTER
$setup->addAttribute('catalog_category', 'filter_meta_title_tpl', array(
    'group'         => 'SEO',
    'input'         => 'text',
    'type'          => 'text',
    'label'         => 'Layered Navigation Meta Title',
    'backend'       => '',
    'visible'       => 1,
    'required'      => 0,
    'user_defined' => 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));

$setup->addAttribute('catalog_category', 'filter_meta_description_tpl', array(
    'group'         => 'SEO',
    'input'         => 'textarea',
    'type'          => 'text',
    'label'         => 'Layered Navigation Meta Description',
    'backend'       => '',
    'visible'       => 1,
    'required'      => 0,
    'user_defined' => 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));

$setup->addAttribute('catalog_category', 'filter_meta_keywords_tpl', array(
    'group'         => 'SEO',
    'input'         => 'textarea',
    'type'          => 'text',
    'label'         => 'Layered Navigation Meta Keywords',
    'backend'       => '',
    'visible'       => 1,
    'required'      => 0,
    'user_defined' => 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));

$setup->addAttribute('catalog_category', 'filter_title_tpl', array(
    'group'         => 'SEO',
    'input'         => 'text',
    'type'          => 'text',
    'label'         => 'Layered Navigation H1',
    'backend'       => '',
    'visible'       => 1,
    'required'      => 0,
    'user_defined' => 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));

$setup->addAttribute('catalog_category', 'filter_description_tpl', array(
    'group'         => 'SEO',
    'input'         => 'textarea',
    'type'          => 'text',
    'label'         => 'Layered Navigation SEO description',
    'backend'       => '',
    'visible'       => 1,
    'required'      => 0,
    'user_defined'  => 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));

$installer->endSetup();
