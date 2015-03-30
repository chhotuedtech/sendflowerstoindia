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
if ($version == '1.0.10') {
    return;
} elseif ($version != '1.0.9') {
    die("Please, run migration Helpdesk 1.0.9");
}
$installer->startSetup();
if (Mage::registry('mst_allow_drop_tables')) {
    $sql = "
       DROP TABLE IF EXISTS `{$this->getTable('helpdesk/permission')}`;
       DROP TABLE IF EXISTS `{$this->getTable('helpdesk/permission_department')}`;
    ";
    $installer->run($sql);
}
$sql = "
CREATE TABLE IF NOT EXISTS `{$this->getTable('helpdesk/permission')}` (
    `permission_id` int(11) NOT NULL AUTO_INCREMENT,
    `role_id` int(10) unsigned,
    `is_ticket_remove_allowed` TINYINT(1) NOT NULL DEFAULT 0,
    KEY `fk_helpdesk_permission_role_id` (`role_id`),
    CONSTRAINT `mst_e04794c1141aa491bcbb6e5af7e85670` FOREIGN KEY (`role_id`) REFERENCES `{$this->getTable('admin/role')}` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (`permission_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `{$this->getTable('helpdesk/permission_department')}` (
    `permission_department_id` int(11) NOT NULL AUTO_INCREMENT,
    `permission_id` INT(11) NOT NULL,
    `department_id` INT(11),
    KEY `fk_helpdesk_permission_department_permission_id` (`permission_id`),
    CONSTRAINT `mst_24f8a337510173026373c03f017f0139` FOREIGN KEY (`permission_id`) REFERENCES `{$this->getTable('helpdesk/permission')}` (`permission_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    KEY `fk_helpdesk_permission_department_department_id` (`department_id`),
    CONSTRAINT `mst_287964f2ac68704845ffd0906389dac9` FOREIGN KEY (`department_id`) REFERENCES `{$this->getTable('helpdesk/department')}` (`department_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (`permission_department_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

";
$installer->run($sql);

$sql = "
   INSERT INTO `{$this->getTable('helpdesk/permission')}` (role_id,is_ticket_remove_allowed) VALUES (NULL,'1');

   INSERT INTO `{$this->getTable('helpdesk/permission_department')}` (permission_id,department_id) VALUES ('1', NULL);

";
$installer->run($sql);

/**                                    **/


$installer->endSetup();