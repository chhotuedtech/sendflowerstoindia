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
if ($version == '1.0.16') {
    return;
} elseif ($version != '1.0.15') {
    die("Please, run migration Helpdesk 1.0.15");
}
$installer->startSetup();
if (Mage::registry('mst_allow_drop_tables')) {
    $sql = "
    ";
    $installer->run($sql);
}
$sql = "
ALTER TABLE `{$this->getTable('helpdesk/attachment')}` ADD COLUMN `external_id` VARCHAR(255) NOT NULL DEFAULT '';
";
$installer->run($sql);

/**                                    **/


$installer->endSetup();