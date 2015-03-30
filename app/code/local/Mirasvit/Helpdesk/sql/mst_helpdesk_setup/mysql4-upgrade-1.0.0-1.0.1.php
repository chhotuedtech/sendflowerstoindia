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
if ($version == '1.0.1') {
    return;
} elseif ($version != '1.0.0') {
    die("Please, run migration Helpdesk 1.0.0");
}
$installer->startSetup();
if (Mage::registry('mst_allow_drop_tables')) {
    $sql = "
       DROP TABLE IF EXISTS `{$this->getTable('helpdesk/template')}`;
       DROP TABLE IF EXISTS `{$this->getTable('helpdesk/field')}`;
    ";
    $installer->run($sql);
}
$sql = "
CREATE TABLE IF NOT EXISTS `{$this->getTable('helpdesk/template')}` (
    `template_id` int(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL DEFAULT '',
    `template` TEXT,
    `is_active` TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`template_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `{$this->getTable('helpdesk/field')}` (
    `field_id` int(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL DEFAULT '',
    `code` VARCHAR(255) NOT NULL DEFAULT '',
    `type` VARCHAR(255) NOT NULL DEFAULT '',
    `values` TEXT,
    `description` TEXT,
    `is_active` TINYINT(1) NOT NULL DEFAULT 0,
    `sort_order` SMALLINT(5) NOT NULL DEFAULT '0',
    `is_required_staff` TINYINT(1) NOT NULL DEFAULT 0,
    `is_required_customer` TINYINT(1) NOT NULL DEFAULT 0,
    `is_visible_customer` TINYINT(1) NOT NULL DEFAULT 0,
    `is_editable_customer` TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`field_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

ALTER TABLE `{$this->getTable('helpdesk/ticket')}` ADD COLUMN `is_spam` TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE `{$this->getTable('helpdesk/ticket')}` ADD COLUMN `email_id` INT(11);

ALTER TABLE `{$this->getTable('helpdesk/department_user')}` DROP FOREIGN KEY `mst_fe34e94971142cab8a64afb510ccf40a`;
ALTER TABLE `{$this->getTable('helpdesk/department_user')}` CHANGE COLUMN `department_id` `du_department_id` INT(11) NOT NULL;
ALTER TABLE `{$this->getTable('helpdesk/department_user')}` ADD CONSTRAINT `mst_fe34e94971142cab8a64afb510ccf40a` FOREIGN KEY (`du_department_id`) REFERENCES `{$this->getTable('helpdesk/department')}` (`department_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `{$this->getTable('helpdesk/department_user')}` DROP FOREIGN KEY `mst_e46bf12f459b28fbd772ac38d6eba241`;
ALTER TABLE `{$this->getTable('helpdesk/department_user')}` CHANGE COLUMN `user_id` `du_user_id` ".(Mage::getVersion() >= '1.6.0.0'? 'int(10)': 'mediumint(11)')." unsigned;
ALTER TABLE `{$this->getTable('helpdesk/department_user')}` ADD CONSTRAINT `mst_e46bf12f459b28fbd772ac38d6eba241` FOREIGN KEY (`du_user_id`) REFERENCES `{$this->getTable('admin/user')}` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

";
$installer->run($sql);

/**                                    **/


$installer->endSetup();