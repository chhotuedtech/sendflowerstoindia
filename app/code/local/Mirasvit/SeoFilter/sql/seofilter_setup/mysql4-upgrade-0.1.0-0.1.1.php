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
 * @package   Advanced SEO Suite
 * @version   1.1.1
 * @build     803
 * @copyright Copyright (C) 2014 Mirasvit (http://mirasvit.com/)
 */


$installer = $this;
$installer->startSetup();
$tablePrefix = (string)Mage::getConfig()->getTablePrefix();
$installer->run("
ALTER TABLE {$this->getTable('seofilter/rewrite')}
  ADD FOREIGN KEY (`option_id`) REFERENCES `{$tablePrefix}eav_attribute_option`(`option_id`) ON UPDATE CASCADE ON DELETE CASCADE;
");
$installer->endSetup();