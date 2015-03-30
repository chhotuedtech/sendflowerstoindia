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
if ($version == '1.0.12') {
    return;
} elseif ($version != '1.0.11') {
    die("Please, run migration Helpdesk 1.0.11");
}
$installer->startSetup();
if (Mage::registry('mst_allow_drop_tables')) {
    $sql = "
       DROP TABLE IF EXISTS `{$this->getTable('helpdesk/history')}`;
    ";
    $installer->run($sql);
}
$sql = "
CREATE TABLE IF NOT EXISTS `{$this->getTable('helpdesk/history')}` (
    `history_id` int(11) NOT NULL AUTO_INCREMENT,
    `ticket_id` INT(11) NOT NULL,
    `triggered_by` VARCHAR(255) NOT NULL DEFAULT '',
    `name` VARCHAR(255) NOT NULL DEFAULT '',
    `message` VARCHAR(255) NOT NULL DEFAULT '',
    `created_at` TIMESTAMP NULL,
    KEY `fk_helpdesk_history_ticket_id` (`ticket_id`),
    CONSTRAINT `mst_e42da9cbff6ab0ce7b3b360f01d3d68b` FOREIGN KEY (`ticket_id`) REFERENCES `{$this->getTable('helpdesk/ticket')}` (`ticket_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (`history_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

";
$installer->run($sql);

/**                                    **/


$installer->endSetup();