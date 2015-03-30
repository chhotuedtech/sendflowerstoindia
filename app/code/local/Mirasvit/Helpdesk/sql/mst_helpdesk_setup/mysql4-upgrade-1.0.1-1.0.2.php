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
if ($version == '1.0.2') {
    return;
} elseif ($version != '1.0.1') {
    die("Please, run migration Helpdesk 1.0.1");
}
$installer->startSetup();
if (Mage::registry('mst_allow_drop_tables')) {
    $sql = "
       DROP TABLE IF EXISTS `{$this->getTable('helpdesk/satisfaction')}`;
       DROP TABLE IF EXISTS `{$this->getTable('helpdesk/ticket_aggregated')}`;
    ";
    $installer->run($sql);
}
$sql = "
CREATE TABLE IF NOT EXISTS `{$this->getTable('helpdesk/satisfaction')}` (
    `satisfaction_id` int(11) NOT NULL AUTO_INCREMENT,
    `ticket_id` INT(11) NOT NULL,
    `message_id` INT(11) ,
    `user_id` ".(Mage::getVersion() >= '1.6.0.0'? 'int(10)': 'mediumint(11)')." unsigned,
    `customer_id` int(10) unsigned,
    `store_id` SMALLINT(5) unsigned NOT NULL,
    `rate` INT(11) ,
    `comment` TEXT,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    KEY `fk_helpdesk_satisfaction_ticket_id` (`ticket_id`),
    CONSTRAINT `mst_efbb6125e0e2329946295872ded9f036` FOREIGN KEY (`ticket_id`) REFERENCES `{$this->getTable('helpdesk/ticket')}` (`ticket_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    KEY `fk_helpdesk_satisfaction_message_id` (`message_id`),
    CONSTRAINT `mst_60508edf881434d3a89a72717d42bb21` FOREIGN KEY (`message_id`) REFERENCES `{$this->getTable('helpdesk/message')}` (`message_id`) ON DELETE SET NULL ON UPDATE CASCADE,
    KEY `fk_helpdesk_satisfaction_user_id` (`user_id`),
    CONSTRAINT `mst_715801c548cad586443c921fea22f591` FOREIGN KEY (`user_id`) REFERENCES `{$this->getTable('admin/user')}` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    KEY `fk_helpdesk_satisfaction_customer_id` (`customer_id`),
    CONSTRAINT `mst_d0532616888f8e2d970b984b74091585` FOREIGN KEY (`customer_id`) REFERENCES `{$this->getTable('customer/entity')}` (`entity_id`) ON DELETE SET NULL ON UPDATE CASCADE,
    KEY `fk_helpdesk_satisfaction_store_id` (`store_id`),
    CONSTRAINT `mst_4168dbb09f81a65b189b0e52aa224711` FOREIGN KEY (`store_id`) REFERENCES `{$this->getTable('core/store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (`satisfaction_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

ALTER TABLE `{$this->getTable('helpdesk/message')}` ADD COLUMN `uid` VARCHAR(255) NOT NULL DEFAULT '';
ALTER TABLE `{$this->getTable('helpdesk/ticket')}` ADD COLUMN `first_reply_at` TIMESTAMP NULL;
ALTER TABLE `{$this->getTable('helpdesk/ticket')}` ADD COLUMN `first_solved_at` TIMESTAMP NULL;
ALTER TABLE `{$this->getTable('helpdesk/gateway')}` ADD COLUMN `last_fetch_result` TEXT;
CREATE TABLE IF NOT EXISTS `{$this->getTable('helpdesk/ticket_aggregated')}` (
    `ticket_aggregated_id` int(11) NOT NULL AUTO_INCREMENT,
    `period` DATE,
    `store_id` SMALLINT(5) NOT NULL DEFAULT '0',
    `user_id` INT(11) ,
    `new_ticket_cnt` INT(11) ,
    `solved_ticket_cnt` INT(11) ,
    `total_ticket_cnt` INT(11) ,
    `first_reply_time` INT(11) ,
    `first_resolution_time` INT(11) ,
    `full_resolution_time` INT(11) ,
    PRIMARY KEY (`ticket_aggregated_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

";
$installer->run($sql);

/**                                    **/


$installer->endSetup();