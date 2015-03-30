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
if ($version == '0.1.1') {
    return;
} elseif ($version != '0.1.0') {
    die("Please, run migration 0.1.0");
}

$installer->startSetup();
if (Mage::registry('mst_allow_drop_tables')) {
    $sql = "
       DROP TABLE IF EXISTS `{$this->getTable('seo/rewrite')}`;
       DROP TABLE IF EXISTS `{$this->getTable('seo/rewrite_store')}`;
    ";
    $installer->run($sql);
}
$installer->run("
CREATE TABLE `{$this->getTable('seo/rewrite')}` (
    `rewrite_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Rewrite Id',
    `url` varchar(255) NOT NULL COMMENT 'Name',
    `title` text COMMENT 'title',
    `description` text COMMENT 'description',
    `meta_title` text COMMENT 'meta_title',
    `meta_keywords` text COMMENT 'meta_keywords',
    `meta_description` text COMMENT 'meta_description',
    `is_active` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Is Active',
    PRIMARY KEY (`rewrite_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Rewrite';

CREATE TABLE `{$this->getTable('seo/rewrite_store')}` (
    `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id',
    `rewrite_id` int(11) NOT NULL COMMENT 'Rewrite Id',
    `store_id` smallint(5) unsigned NOT NULL COMMENT 'Store Id',
    PRIMARY KEY (`id`),
    KEY `FK_SEOREWRITE_STORE_ID` (`store_id`),
    KEY `FK_SEOREWRITE_STORE_REWRITE_ID` (`rewrite_id`),
    CONSTRAINT `fk_seorewrite_store_id` FOREIGN KEY (`store_id`) REFERENCES `{$this->getTable('core/store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_seorewrite_store_rewrite_id` FOREIGN KEY (`rewrite_id`) REFERENCES `{$this->getTable('seo/rewrite')}` (`rewrite_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Rewrite 2 Stores';
");

$installer->endSetup();
