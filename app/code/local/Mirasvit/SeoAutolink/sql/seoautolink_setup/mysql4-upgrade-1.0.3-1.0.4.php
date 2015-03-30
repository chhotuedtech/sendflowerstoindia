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
$version = Mage::helper('mstcore/version')->getModuleVersionFromDb('seoautolink');
if ($version == '1.0.4') {
    return;
} elseif ($version != '1.0.3') {
    die("Please, run migration 1.0.3");
}

$installer->startSetup();

$installer->run("
    ALTER TABLE `{$this->getTable('seoautolink/link')}` ADD `sort_order` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Sort order' AFTER `max_replacements` ;
");
$installer->endSetup();

