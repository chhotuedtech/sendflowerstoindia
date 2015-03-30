<?php
$installer = $this;
$installer->getConnection()->addColumn($this->getTable('supplier_users'), 'email_attachement', 'int(11) NOT NULL');
$installer->endSetup();