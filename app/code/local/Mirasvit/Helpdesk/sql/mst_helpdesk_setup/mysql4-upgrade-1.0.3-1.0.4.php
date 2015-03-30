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
if ($version == '1.0.4') {
    return;
} elseif ($version != '1.0.3') {
    die("Please, run migration Helpdesk 1.0.3");
}
$installer->startSetup();
if (Mage::registry('mst_allow_drop_tables')) {
    $sql = "
       DROP TABLE IF EXISTS `{$this->getTable('helpdesk/rule')}`;
       DROP TABLE IF EXISTS `{$this->getTable('helpdesk/tag')}`;
       DROP TABLE IF EXISTS `{$this->getTable('helpdesk/ticket_tag')}`;
    ";
    $installer->run($sql);
}
$sql = "
CREATE TABLE IF NOT EXISTS `{$this->getTable('helpdesk/rule')}` (
    `rule_id` int(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL DEFAULT '',
    `event` VARCHAR(255) NOT NULL DEFAULT '',
    `email_subject` VARCHAR(255) NOT NULL DEFAULT '',
    `email_body` TEXT,
    `is_active` INT(11) ,
    `conditions_serialized` TEXT,
    `is_send_owner` TINYINT(1) NOT NULL DEFAULT 0,
    `is_send_department` TINYINT(1) NOT NULL DEFAULT 0,
    `is_send_user` TINYINT(1) NOT NULL DEFAULT 0,
    `other_email` VARCHAR(255) NOT NULL DEFAULT '',
    `sort_order` SMALLINT(5) NOT NULL DEFAULT '0',
    `is_stop_processing` TINYINT(1) NOT NULL DEFAULT 0,
    `priority_id` INT(11),
    `status_id` INT(11),
    `department_id` INT(11),
    `add_tags` VARCHAR(255) NOT NULL DEFAULT '',
    `remove_tags` VARCHAR(255) NOT NULL DEFAULT '',
    `is_archive` TINYINT(1) NOT NULL DEFAULT 0,
    `user_id` ".(Mage::getVersion() >= '1.6.0.0'? 'INT(10)': 'mediumint(11)')." UNSIGNED,
    KEY `fk_helpdesk_rule_priority_id` (`priority_id`),
    CONSTRAINT `mst_5400c2534ae50ddd36d9b8efa0647eac` FOREIGN KEY (`priority_id`) REFERENCES `{$this->getTable('helpdesk/priority')}` (`priority_id`) ON DELETE SET NULL ON UPDATE CASCADE,
    KEY `fk_helpdesk_rule_status_id` (`status_id`),
    CONSTRAINT `mst_b68f2c027fb03a3ff634088ec19e62cb` FOREIGN KEY (`status_id`) REFERENCES `{$this->getTable('helpdesk/status')}` (`status_id`) ON DELETE SET NULL ON UPDATE CASCADE,
    KEY `fk_helpdesk_rule_department_id` (`department_id`),
    CONSTRAINT `mst_cb6a189e971a02a885a2f0cdb8b30c31` FOREIGN KEY (`department_id`) REFERENCES `{$this->getTable('helpdesk/department')}` (`department_id`) ON DELETE SET NULL ON UPDATE CASCADE,
    KEY `fk_helpdesk_rule_user_id` (`user_id`),
    CONSTRAINT `mst_59d5c67ad6ae53646b557e44b515fd8e` FOREIGN KEY (`user_id`) REFERENCES `{$this->getTable('admin/user')}` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
    PRIMARY KEY (`rule_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `{$this->getTable('helpdesk/tag')}` (
    `tag_id` int(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL DEFAULT '',
    PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `{$this->getTable('helpdesk/ticket_tag')}` (
    `ticket_tag_id` int(11) NOT NULL AUTO_INCREMENT,
    `tt_ticket_id` INT(11) NOT NULL,
    `tt_tag_id` INT(11) NOT NULL,
    KEY `fk_helpdesk_ticket_tag_ticket_id` (`tt_ticket_id`),
    CONSTRAINT `mst_073664887fe485b0e0dfb8620f9ddb01` FOREIGN KEY (`tt_ticket_id`) REFERENCES `{$this->getTable('helpdesk/ticket')}` (`ticket_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    KEY `fk_helpdesk_ticket_tag_tag_id` (`tt_tag_id`),
    CONSTRAINT `mst_41fdd9adb1616a52c3143c4d276472e5` FOREIGN KEY (`tt_tag_id`) REFERENCES `{$this->getTable('helpdesk/tag')}` (`tag_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (`ticket_tag_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

";
$installer->run($sql);

/**                                    **/


$installer->endSetup();