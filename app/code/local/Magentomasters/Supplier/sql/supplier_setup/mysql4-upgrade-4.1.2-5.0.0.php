<?php
$installer = $this;

// Add Sales order item attributes
$installer->getConnection()->addColumn($this->getTable('sales_flat_order_item'), 'qty_dropshipped', 'decimal(12,4) NOT NULL');

// Add Attribute Option
$installer->getConnection()->addColumn($this->getTable('catalog/eav_attribute'),'edit_by_supplier',"int(11) NULL DEFAULT 0");

// Save edit_by_supplier for basic attributes
$defaultEditAttributes = array('name','sku','status','visibility','thumbnail','image','small_image','price','media_gallery');
foreach($defaultEditAttributes as $defaultEditAttribute){
	$attrEdit = Mage::getSingleton('eav/config')->getAttribute('catalog_product', $defaultEditAttribute);
	if($attrEdit->getId()){
		$attrEdit->setEditBySupplier(1);
		$attrEdit->save();
	}
}

$installer->endSetup();