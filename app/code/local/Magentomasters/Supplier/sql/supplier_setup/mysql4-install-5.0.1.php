<?php
$installer = $this;
$installer->startSetup();
$installer->run("
CREATE TABLE IF NOT EXISTS `{$this->getTable ("supplier_users")}` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL DEFAULT '1',
  `name` varchar(255) DEFAULT NULL,
  `surname` varchar(255) DEFAULT NULL,
  `email1` varchar(255) DEFAULT NULL,
  `email2` varchar(255) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL DEFAULT '999999',
  `xml_name` varchar(255) DEFAULT NULL,
  `email_enabled` int(11) NOT NULL,
  `email_template` int(11) NOT NULL,
  `email_subject` varchar(255) NOT NULL,
  `pdf_enabled` int(11) NOT NULL,
  `pdf_template` int(11) NOT NULL,
  `xml_enabled` int(11) NOT NULL,
  `xml_type` int(11) NOT NULL,
  `xml_ftp` int(11) NOT NULL,
  `xml_ftp_type` int(11) NOT NULL,
  `xml_ftp_host` varchar(255) NOT NULL,
  `xml_ftp_path` varchar(255) NOT NULL,
  `xml_ftp_port` int(11) NOT NULL,
  `xml_ftp_user` varchar(255) NOT NULL,
  `xml_ftp_password` varchar(255) NOT NULL,
  `xml_template` int(11) NOT NULL,
  `csv_delimeter` varchar(11) NOT NULL,
  `xml_csv` int(111) NOT NULL,
  `show_custom_attr` int(11) NOT NULL,
  `shipping_method` int(11) NOT NULL,
  `shipping_cost` varchar(11) NOT NULL,
  `shipping_cost_free` varchar(11) NOT NULL,
  `shipping_file` varchar(255) NOT NULL,
  `shipping_condition` varchar(255) NOT NULL,
  `attributes_show` int(11) NOT NULL,
  `attributes` text NOT NULL,
  `email_header` text NOT NULL,
  `email_message` text NOT NULL,
  `email_attachement` int (11) NOT NULL,
  `pdf_header` text NOT NULL,
  `pdf_message` text NOT NULL,
  `custom1` text NOT NULL,
  `custom2` text NOT NULL,
  `custom3` text NOT NULL,
  `custom4` text NOT NULL,
  `address1` text NOT NULL,
  `address2` text NOT NULL,
  `city` text NOT NULL,
  `postalcode` text NOT NULL,
  `country` text NOT NULL,
  `state` text NOT NULL,
  `phone` text NOT NULL,
  `contact` text NOT NULL,
  `company` text NOT NULL,
  `schedule_enabled` int(11) NOT NULL DEFAULT '1',
  `schedule` varchar(1000) NOT NULL,
  `schedule_dropship_date` datetime NOT NULL,
  `schedule_import_stock_enabled` int(11) NOT NULL,
  `schedule_import_stock` varchar(1000) NOT NULL,
  `schedule_import_stock_date` datetime NOT NULL,
  `schedule_import_stock_url` varchar(255) NOT NULL,
  `schedule_import_stock_type` int(11) NOT NULL,
  `schedule_import_stock_qty` varchar(255) NOT NULL,
  `schedule_import_stock_sku` varchar(255) NOT NULL,
  `schedule_import_stock_divider` varchar(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `{$this->getTable ("supplier_dropship_items")}`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dropship_id` int(255) NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_number` int(11) NOT NULL,
  `order_item_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `supplier_name` text NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` text NOT NULL,
  `sku` varchar(255) NOT NULL,
  `qty` varchar(255) NOT NULL,
  `cost` decimal(12,4) NOT NULL,
  `price` decimal(12,4) NOT NULL,
  `status` int(11) NOT NULL,
  `method` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `{$this->getTable ("supplier_templates")}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `header` text COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `item` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `{$this->getTable ("supplier_tablerate")}` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key',
  `supplier_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Supplier Id',
  `dest_country_id` varchar(4) NOT NULL DEFAULT '0' COMMENT 'Destination coutry ISO/2 or ISO/3 code',
  `dest_region_id` varchar(255) NOT NULL DEFAULT '*' COMMENT 'Destination Region Id',
  `dest_zip` varchar(10) NOT NULL DEFAULT '*' COMMENT 'Destination Post Code (Zip)',
  `condition_name` varchar(20) NOT NULL COMMENT 'Rate Condition name',
  `condition_value` decimal(12,4) NOT NULL DEFAULT '0.0000' COMMENT 'Rate condition value',
  `price` decimal(12,4) NOT NULL DEFAULT '0.0000' COMMENT 'Price',
  `cost` decimal(12,4) NOT NULL DEFAULT '0.0000' COMMENT 'Cost',
  PRIMARY KEY (`id`),
  UNIQUE KEY `48BE887067000D0743CFF9EEEDDE3C42` (`supplier_id`,`dest_country_id`,`dest_region_id`,`dest_zip`,`condition_name`,`condition_value`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Supplier Tablerate' AUTO_INCREMENT=1;

");

// Add Sales order item attributes
$installer->getConnection()->addColumn($this->getTable('sales_flat_order_item'), 'qty_dropshipped', 'decimal(12,4) NOT NULL');

// Add Attribute Option
$installer->getConnection()->addColumn($this->getTable('catalog/eav_attribute'),'edit_by_supplier',"int(11) NULL DEFAULT 0");

// Add Supplier Attribute
$attribute = Mage::getResourceModel('catalog/setup', 'catalog_setup');
$attribute->startSetup();
$attribute->addAttribute('catalog_product', 'supplier', array(
    'type'             => 'int',
    'input'            => 'select',
    'label'            => 'Supplier',
    'user_defined'     => 1,
    'group'            => 'General',
    'is_configurable'  => 0,
    'visible_on_front' => 1,
    'required'         => 0,
    'system'		   => 1,
    'used_in_product_listing'=> 1,
    'is_global'			=> 1,
    'source' 		=> 'eav/entity_attribute_source_table'
));
$attribute->endSetup();

// Save edit_by_supplier for basic attributes
$defaultEditAttributes = array('name','sku','status','visibility','thumbnail','image','small_image','price','media_gallery');
foreach($defaultEditAttributes as $defaultEditAttribute){
	$attrEdit = Mage::getSingleton('eav/config')->getAttribute('catalog_product', $defaultEditAttribute);
	if($attrEdit->getId()){
		$attrEdit->setEditBySupplier(1);
		$attrEdit->save();
	}
}