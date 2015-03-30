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
if ($version == '1.0.7') {
    return;
} elseif ($version != '1.0.6') {
    die("Please, run migration 1.0.6");
}
$installer->startSetup();
if (Mage::registry('mst_allow_drop_tables')) {
    $sql = "
    ";
    $installer->run($sql);
}
$sql = "
ALTER TABLE `{$this->getTable('helpdesk/ticket')}`
	MODIFY `priority_id` INT(11),
    DROP FOREIGN KEY mst_d42e33ad7e9616a0579eeed65230435b;
ALTER TABLE `{$this->getTable('helpdesk/ticket')}` ADD CONSTRAINT mst_d42e33ad7e9616a0579eeed65230435b FOREIGN KEY (priority_id) REFERENCES `{$this->getTable('helpdesk/priority')}` (`priority_id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `{$this->getTable('helpdesk/ticket')}`
	MODIFY `status_id` INT(11),
    DROP FOREIGN KEY mst_bcdb3c8e91be43d2f574e0ec2da0814d;
ALTER TABLE `{$this->getTable('helpdesk/ticket')}` ADD CONSTRAINT mst_bcdb3c8e91be43d2f574e0ec2da0814d FOREIGN KEY (status_id) REFERENCES `{$this->getTable('helpdesk/status')}` (`status_id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `{$this->getTable('helpdesk/ticket')}`
	MODIFY `department_id` INT(11),
    DROP FOREIGN KEY mst_47d25ec0bbc461aece08fca0a592efa5;
ALTER TABLE `{$this->getTable('helpdesk/ticket')}` ADD CONSTRAINT mst_47d25ec0bbc461aece08fca0a592efa5 FOREIGN KEY (department_id) REFERENCES `{$this->getTable('helpdesk/department')}` (`department_id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `{$this->getTable('helpdesk/ticket')}`
	MODIFY `store_id` SMALLINT(5) unsigned,
    DROP FOREIGN KEY mst_853300c6e1a29a8aba170383f331d9f2;
ALTER TABLE `{$this->getTable('helpdesk/ticket')}` ADD CONSTRAINT mst_853300c6e1a29a8aba170383f331d9f2 FOREIGN KEY (store_id) REFERENCES `{$this->getTable('core/store')}` (`store_id`) ON DELETE SET NULL ON UPDATE CASCADE;
";
$installer->run($sql);

/**                                    **/


$installer->endSetup();