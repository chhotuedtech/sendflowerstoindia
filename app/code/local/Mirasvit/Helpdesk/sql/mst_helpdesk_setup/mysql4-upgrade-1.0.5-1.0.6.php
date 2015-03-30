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
if ($version == '1.0.6') {
    return;
} elseif ($version != '1.0.5') {
    die("Please, run migration Helpdesk 1.0.5");
}
$installer->startSetup();
if (Mage::registry('mst_allow_drop_tables')) {
    $sql = "
    ";
    $installer->run($sql);
}
$sql = "
ALTER TABLE `{$this->getTable('helpdesk/status')}` ADD COLUMN `color` VARCHAR(255) NOT NULL DEFAULT '';
ALTER TABLE `{$this->getTable('helpdesk/priority')}` ADD COLUMN `color` VARCHAR(255) NOT NULL DEFAULT '';

UPDATE `{$this->getTable('helpdesk/status')}` SET color='green' WHERE status_id=1;
UPDATE `{$this->getTable('helpdesk/status')}` SET color='red' WHERE status_id=2;
UPDATE `{$this->getTable('helpdesk/status')}` SET color='blue' WHERE status_id=3;
UPDATE `{$this->getTable('helpdesk/priority')}` SET color='red' WHERE priority_id=1;
UPDATE `{$this->getTable('helpdesk/priority')}` SET color='orange' WHERE priority_id=2;
UPDATE `{$this->getTable('helpdesk/priority')}` SET color='yellow' WHERE priority_id=3;
";
$installer->run($sql);

/**                                    **/


$installer->endSetup();