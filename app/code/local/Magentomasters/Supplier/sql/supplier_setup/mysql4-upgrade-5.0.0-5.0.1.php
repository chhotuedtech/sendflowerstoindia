<?php
$installer = $this;
$installer->getConnection()->addColumn($this->getTable("supplier_users"), "status", "int(11) NOT NULL DEFAULT '1'");
$installer->endSetup();