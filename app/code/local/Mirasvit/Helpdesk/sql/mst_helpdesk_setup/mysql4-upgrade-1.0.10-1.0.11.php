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
 * @package   Help Desk MX
 * @version   1.0.7
 * @build     907
 * @copyright Copyright (C) 2014 Mirasvit (http://mirasvit.com/)
 */


$installer = $this;
$version = Mage::helper('mstcore/version')->getModuleVersionFromDb('mst_helpdesk');
if ($version == '1.0.11') {
    return;
} elseif ($version != '1.0.10') {
    die("Please, run migration Helpdesk 1.0.10");
}
$installer->startSetup();
if (Mage::registry('mst_allow_drop_tables')) {
    $sql = "
       DROP TABLE IF EXISTS `{$this->getTable('helpdesk/template_store')}`;
       DROP TABLE IF EXISTS `{$this->getTable('helpdesk/field_store')}`;
    ";
    $installer->run($sql);
}
$sql = "
ALTER TABLE `{$this->getTable('helpdesk/ticket')}` ADD COLUMN `third_party_email` VARCHAR(255) NOT NULL DEFAULT '';
ALTER TABLE `{$this->getTable('helpdesk/message')}` ADD COLUMN `type` VARCHAR(255) NOT NULL DEFAULT '';
ALTER TABLE `{$this->getTable('helpdesk/message')}` ADD COLUMN `third_party_email` VARCHAR(255) NOT NULL DEFAULT '';
ALTER TABLE `{$this->getTable('helpdesk/message')}` ADD COLUMN `third_party_name` VARCHAR(255) NOT NULL DEFAULT '';
ALTER TABLE `{$this->getTable('helpdesk/message')}` ADD COLUMN `triggered_by` VARCHAR(255) NOT NULL DEFAULT '';
ALTER TABLE `{$this->getTable('helpdesk/message')}` ADD COLUMN `is_read` TINYINT(1) NOT NULL DEFAULT 0;
CREATE TABLE IF NOT EXISTS `{$this->getTable('helpdesk/template_store')}` (
    `template_store_id` int(11) NOT NULL AUTO_INCREMENT,
    `ts_template_id` INT(11) NOT NULL,
    `ts_store_id` SMALLINT(5) unsigned NOT NULL,
    KEY `fk_helpdesk_template_store_template_id` (`ts_template_id`),
    CONSTRAINT `mst_06e5294fae5d6dc6d47bb187744b12b9` FOREIGN KEY (`ts_template_id`) REFERENCES `{$this->getTable('helpdesk/template')}` (`template_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    KEY `fk_helpdesk_template_store_store_id` (`ts_store_id`),
    CONSTRAINT `mst_900964ab8d5d1ddb39b53bd082df9e5c` FOREIGN KEY (`ts_store_id`) REFERENCES `{$this->getTable('core/store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (`template_store_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `{$this->getTable('helpdesk/field_store')}` (
    `field_store_id` int(11) NOT NULL AUTO_INCREMENT,
    `fs_field_id` INT(11) NOT NULL,
    `fs_store_id` SMALLINT(5) unsigned NOT NULL,
    KEY `fk_helpdesk_field_store_field_id` (`fs_field_id`),
    CONSTRAINT `mst_62a75fac9b6ca425f506da972525451c` FOREIGN KEY (`fs_field_id`) REFERENCES `{$this->getTable('helpdesk/field')}` (`field_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    KEY `fk_helpdesk_field_store_store_id` (`fs_store_id`),
    CONSTRAINT `mst_323d06b333e47c2c725d9d27b7406090` FOREIGN KEY (`fs_store_id`) REFERENCES `{$this->getTable('core/store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (`field_store_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

";
$installer->run($sql);

/**                                    **/


$installer->endSetup();