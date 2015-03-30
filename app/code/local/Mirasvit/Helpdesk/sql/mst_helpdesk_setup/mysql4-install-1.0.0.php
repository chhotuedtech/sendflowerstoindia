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
if ($version == '1.0.0') {
    return;
}

$installer->startSetup();

if (Mage::registry('mst_allow_drop_tables')) {
    $sql = "
       DROP TABLE IF EXISTS `{$this->getTable('helpdesk/status')}`;
       DROP TABLE IF EXISTS `{$this->getTable('helpdesk/priority')}`;
       DROP TABLE IF EXISTS `{$this->getTable('helpdesk/department')}`;
       DROP TABLE IF EXISTS `{$this->getTable('helpdesk/department_user')}`;
       DROP TABLE IF EXISTS `{$this->getTable('helpdesk/email')}`;
       DROP TABLE IF EXISTS `{$this->getTable('helpdesk/ticket')}`;
       DROP TABLE IF EXISTS `{$this->getTable('helpdesk/message')}`;
       DROP TABLE IF EXISTS `{$this->getTable('helpdesk/attachment')}`;
       DROP TABLE IF EXISTS `{$this->getTable('helpdesk/gateway')}`;
       DROP TABLE IF EXISTS `{$this->getTable('helpdesk/pattern')}`;
    ";
    $installer->run($sql);
}
$sql = "
CREATE TABLE IF NOT EXISTS `{$this->getTable('helpdesk/status')}` (
    `status_id` int(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL DEFAULT '',
    `code` VARCHAR(255) NOT NULL DEFAULT '',
    `sort_order` SMALLINT(5) NOT NULL DEFAULT '0',
    PRIMARY KEY (`status_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `{$this->getTable('helpdesk/priority')}` (
    `priority_id` int(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL DEFAULT '',
    `sort_order` SMALLINT(5) NOT NULL DEFAULT '0',
    PRIMARY KEY (`priority_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `{$this->getTable('helpdesk/department')}` (
    `department_id` int(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL DEFAULT '',
    `sender_email` VARCHAR(255) NOT NULL DEFAULT '',
    `is_active` TINYINT(1) NOT NULL DEFAULT 0,
    `signature` TEXT,
    `sort_order` SMALLINT(5) NOT NULL DEFAULT '0',
    `is_notification_enabled` TINYINT(1) NOT NULL DEFAULT 0,
    `notification_email` VARCHAR(255) NOT NULL DEFAULT '',
    PRIMARY KEY (`department_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `{$this->getTable('helpdesk/department_user')}` (
    `department_user_id` int(11) NOT NULL AUTO_INCREMENT,
    `department_id` INT(11) NOT NULL,
    `user_id` ".(Mage::getVersion() >= '1.6.0.0'? 'int(10)': 'mediumint(11)')." unsigned,
    KEY `fk_helpdesk_department_user_department_id` (`department_id`),
    CONSTRAINT `mst_fe34e94971142cab8a64afb510ccf40a` FOREIGN KEY (`department_id`) REFERENCES `{$this->getTable('helpdesk/department')}` (`department_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    KEY `fk_helpdesk_department_user_user_id` (`user_id`),
    CONSTRAINT `mst_e46bf12f459b28fbd772ac38d6eba241` FOREIGN KEY (`user_id`) REFERENCES `{$this->getTable('admin/user')}` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (`department_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `{$this->getTable('helpdesk/email')}` (
    `email_id` int(11) NOT NULL AUTO_INCREMENT,
    `from_email` VARCHAR(255) NOT NULL DEFAULT '',
    `to_email` VARCHAR(255) NOT NULL DEFAULT '',
    `subject` TEXT,
    `body` TEXT,
    `format` VARCHAR(255) NOT NULL DEFAULT '',
    `sender_name` VARCHAR(255) NOT NULL DEFAULT '',
    `message_id` VARCHAR(255) NOT NULL DEFAULT '',
    `pattern_id` INT(11) ,
    `gateway_id` INT(11) ,
    `headers` TEXT,
    `created_at` TIMESTAMP,
    `is_processed` TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`email_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `{$this->getTable('helpdesk/ticket')}` (
    `ticket_id` int(11) NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(255) NOT NULL DEFAULT '',
    `external_id` VARCHAR(255) NOT NULL DEFAULT '',
    `user_id` INT(11) ,
    `name` VARCHAR(255) NOT NULL DEFAULT '',
    `description` TEXT,
    `priority_id` INT(11) NOT NULL,
    `status_id` INT(11) NOT NULL,
    `department_id` INT(11) NOT NULL,
    `customer_id` INT(11) ,
    `customer_email` VARCHAR(255) NOT NULL DEFAULT '',
    `customer_name` VARCHAR(255) NOT NULL DEFAULT '',
    `order_id` INT(11) ,
    `last_reply_name` VARCHAR(255) NOT NULL DEFAULT '',
    `last_reply_at` TIMESTAMP,
    `reply_cnt` INT(11) ,
    `store_id` SMALLINT(5) unsigned NOT NULL,
    `created_at` TIMESTAMP,
    `updated_at` TIMESTAMP,
    KEY `fk_helpdesk_ticket_priority_id` (`priority_id`),
    CONSTRAINT `mst_d42e33ad7e9616a0579eeed65230435b` FOREIGN KEY (`priority_id`) REFERENCES `{$this->getTable('helpdesk/priority')}` (`priority_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    KEY `fk_helpdesk_ticket_status_id` (`status_id`),
    CONSTRAINT `mst_bcdb3c8e91be43d2f574e0ec2da0814d` FOREIGN KEY (`status_id`) REFERENCES `{$this->getTable('helpdesk/status')}` (`status_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    KEY `fk_helpdesk_ticket_department_id` (`department_id`),
    CONSTRAINT `mst_47d25ec0bbc461aece08fca0a592efa5` FOREIGN KEY (`department_id`) REFERENCES `{$this->getTable('helpdesk/department')}` (`department_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    KEY `fk_helpdesk_ticket_store_id` (`store_id`),
    CONSTRAINT `mst_853300c6e1a29a8aba170383f331d9f2` FOREIGN KEY (`store_id`) REFERENCES `{$this->getTable('core/store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (`ticket_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `{$this->getTable('helpdesk/message')}` (
    `message_id` int(11) NOT NULL AUTO_INCREMENT,
    `ticket_id` INT(11) NOT NULL,
    `email_id` INT(11) ,
    `user_id` INT(11) ,
    `customer_id` INT(11) ,
    `customer_email` VARCHAR(255) NOT NULL DEFAULT '',
    `customer_name` VARCHAR(255) NOT NULL DEFAULT '',
    `body` TEXT,
    `is_internal` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP,
    `updated_at` TIMESTAMP,
    KEY `fk_helpdesk_message_ticket_id` (`ticket_id`),
    CONSTRAINT `mst_de3506bde4339805f38535648bca8d06` FOREIGN KEY (`ticket_id`) REFERENCES `{$this->getTable('helpdesk/ticket')}` (`ticket_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (`message_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `{$this->getTable('helpdesk/attachment')}` (
    `attachment_id` int(11) NOT NULL AUTO_INCREMENT,
    `email_id` INT(11) ,
    `message_id` INT(11) ,
    `name` VARCHAR(255) NOT NULL DEFAULT '',
    `type` VARCHAR(255) NOT NULL DEFAULT '',
    `size` INT(11) ,
    `body` LONGBLOB,
    PRIMARY KEY (`attachment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `{$this->getTable('helpdesk/gateway')}` (
    `gateway_id` int(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL DEFAULT '',
    `email` VARCHAR(255) NOT NULL DEFAULT '',
    `login` VARCHAR(255) NOT NULL DEFAULT '',
    `password` VARCHAR(255) NOT NULL DEFAULT '',
    `is_active` TINYINT(1) NOT NULL DEFAULT 0,
    `host` VARCHAR(255) NOT NULL DEFAULT '',
    `port` INT(11) ,
    `protocol` VARCHAR(255) NOT NULL DEFAULT '',
    `encryption` VARCHAR(255) NOT NULL DEFAULT '',
    `fetch_frequency` INT(11) ,
    `fetch_max` INT(11) ,
    `department_id` INT(11) ,
    `store_id` SMALLINT(5) unsigned NOT NULL,
    `notes` TEXT,
    `fetched_at` TIMESTAMP,
    KEY `fk_helpdesk_gateway_store_id` (`store_id`),
    CONSTRAINT `mst_b9127dc8ed4182a55e5032e7a72221d0` FOREIGN KEY (`store_id`) REFERENCES `{$this->getTable('core/store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (`gateway_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `{$this->getTable('helpdesk/pattern')}` (
    `pattern_id` int(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL DEFAULT '',
    `pattern` TEXT,
    `scope` VARCHAR(255) NOT NULL DEFAULT '',
    `is_active` TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`pattern_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

";
$installer->run($sql);

$sql = "
   INSERT INTO `{$this->getTable('helpdesk/priority')}` (name,sort_order) VALUES ('High','30');
   INSERT INTO `{$this->getTable('helpdesk/priority')}` (name,sort_order) VALUES ('Medium','20');
   INSERT INTO `{$this->getTable('helpdesk/priority')}` (name,sort_order) VALUES ('Low','10');

   INSERT INTO `{$this->getTable('helpdesk/status')}` (name,code,sort_order) VALUES ('Open','open','10');
   INSERT INTO `{$this->getTable('helpdesk/status')}` (name,code,sort_order) VALUES ('In Progress','in_progress','20');
   INSERT INTO `{$this->getTable('helpdesk/status')}` (name,code,sort_order) VALUES ('Closed','closed','30');

   INSERT INTO `{$this->getTable('helpdesk/department')}` (name,sort_order,sender_email,is_notification_enabled,notification_email,is_active) VALUES ('Sales','10','sales','1','','1');
   INSERT INTO `{$this->getTable('helpdesk/department')}` (name,sort_order,sender_email,is_notification_enabled,notification_email,is_active) VALUES ('Support','20','sales','1','','1');

";
$installer->run($sql);

/**                                    **/


$installer->endSetup();