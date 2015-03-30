<?php
$installer = $this;
$installer->getConnection()->addColumn($this->getTable('supplier_users'), 'email_subject', 'varchar(255) NOT NULL');
$installer->getConnection()->addColumn($this->getTable('supplier_users'), 'shipping_method', 'int(11) NOT NULL');
$installer->getConnection()->addColumn($this->getTable('supplier_users'), 'shipping_file', 'varchar(255) NOT NULL');
$installer->getConnection()->addColumn($this->getTable('supplier_users'), 'shipping_condition', 'varchar(255) NOT NULL');
$installer->startSetup();
$installer->run("
CREATE TABLE IF NOT EXISTS `{$this->getTable ("supplier_tablerate")}` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key',
  `supplier_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Supplier Id',
  `dest_country_id` varchar(4) NOT NULL DEFAULT '0' COMMENT 'Destination coutry ISO/2 or ISO/3 code',
  `dest_region_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Destination Region Id',
  `dest_zip` varchar(10) NOT NULL DEFAULT '*' COMMENT 'Destination Post Code (Zip)',
  `condition_name` varchar(20) NOT NULL COMMENT 'Rate Condition name',
  `condition_value` decimal(12,4) NOT NULL DEFAULT '0.0000' COMMENT 'Rate condition value',
  `price` decimal(12,4) NOT NULL DEFAULT '0.0000' COMMENT 'Price',
  `cost` decimal(12,4) NOT NULL DEFAULT '0.0000' COMMENT 'Cost',
  PRIMARY KEY (`id`),
  UNIQUE KEY `48BE887067000D0743CFF9EEEDDE3C42` (`supplier_id`,`dest_country_id`,`dest_region_id`,`dest_zip`,`condition_name`,`condition_value`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Supplier Tablerate' AUTO_INCREMENT=1;


");
$installer->endSetup();