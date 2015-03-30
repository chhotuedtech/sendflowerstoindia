<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (http://www.amasty.com)
 * @package Amasty_Deliverydate
 */
$installer = $this;

$installer->startSetup();

$installer->run("

ALTER TABLE `{$this->getTable('amdeliverydate/deliverydate')}`
ADD KEY `IDX_DELIVERY_DATE_ORDER` (`order_id`);

ALTER TABLE `{$this->getTable('amdeliverydate/deliverydate')}`
ADD CONSTRAINT `FK_DELIVERY_DATE_ORDER` FOREIGN KEY (`order_id`) REFERENCES `{$this->getTable('sales/order')}` (`entity_id`) ON DELETE CASCADE;

");

$installer->endSetup();