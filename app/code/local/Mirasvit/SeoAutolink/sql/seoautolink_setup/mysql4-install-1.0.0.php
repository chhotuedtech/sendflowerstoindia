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
if ($version == '1.0.0') {
    return;
}

$installer->startSetup();

$installer->run("
CREATE TABLE `{$this->getTable('seoautolink/link')}` (
    `link_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Link Id',
    `keyword` varchar(255) NOT NULL COMMENT 'Keyword',
    `url` text NOT NULL COMMENT 'URL',
    `url_target` varchar(255) DEFAULT NULL COMMENT 'url',
    `is_nofollow` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Is Nofollow',
    `max_replacements` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Max Number of replacements',
    `occurence` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Occurence',
    `is_active` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Is Active',
    `active_from` timestamp NULL DEFAULT NULL COMMENT 'active_from',
    `active_to` timestamp NULL DEFAULT NULL COMMENT 'active_to',
    `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Created Time',
    `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Updated Time',
    PRIMARY KEY (`link_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Link'
");
$installer->run("
CREATE TABLE `{$this->getTable('seoautolink/link_store')}` (
    `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id',
    `link_id` int(11) NOT NULL COMMENT 'Link Id',
    `store_id` smallint(5) unsigned NOT NULL COMMENT 'Store Id',
    PRIMARY KEY (`id`),
    KEY `FK_SEOAUTOLINK_STORE_ID` (`store_id`),
    KEY `FK_SEOAUTOLINK_LINK_ID` (`link_id`),
    CONSTRAINT `fk_seoautolink_store_id` FOREIGN KEY (`store_id`) REFERENCES `{$this->getTable('core/store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_seoautolink_store_link_id` FOREIGN KEY (`link_id`) REFERENCES `{$this->getTable('seoautolink/link')}` (`link_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Link 2 Stores'
");
$installer->endSetup();
