<?php
/**
 * @copyright  Amasty (http://www.amasty.com)
 */
$installer = $this;

/**
 * Create table 'amgiftwrap/wrapping'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('amgiftwrap/design'))
    ->addColumn('design_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary'  => true,
        ), 'Wrapping Design Id'
    )
    ->addColumn('enabled', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array('unsigned' => true, 'nullable' => false,), 'Enabled')
    ->addColumn('sort', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array('unsigned' => true, 'nullable' => false,), 'Sort')
    ->addColumn('price', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array('nullable' => false,), 'Price')
    ->addColumn('image', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Image')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Name')
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, 4000, array(), 'Description')
    ->addColumn('stores', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Stores')
    ->addIndex(('enabled'), array('enabled'), array('enabled'))
    ->addIndex(('sort'), array('sort'), array('sort'))
    ->setComment('Amasty Gift Wrapping Design Table');

$installer->getConnection()->createTable($table);

/**
 * Create table 'amgiftwrap/wrapping'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('amgiftwrap/cards'))
    ->addColumn('cards_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary'  => true,
        ), 'Wrapping Id'
    )
    ->addColumn('enabled', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array('unsigned' => true, 'nullable' => false,), 'Enabled')
    ->addColumn('sort', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array('unsigned' => true, 'nullable' => false,), 'Sort')
    ->addColumn('price', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array('nullable' => false,), 'Price')
    ->addColumn('image', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Image')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Name')
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, 4000, array(), 'Description')
    ->addColumn('stores', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Stores')
    ->addIndex(('enabled'), array('enabled'), array('enabled'))
    ->addIndex(('sort'), array('sort'), array('sort'))
    ->setComment('Amasty Gift Wrapping Design Table');

$installer->getConnection()->createTable($table);


/**
 * Add gift wrapping attributes for sales entities
 */
$entityAttributesCodes = array(
    'amgiftwrap_design_id' => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'amgiftwrap_card_id'   => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'amgiftwrap_separate_wrap' => Varien_Db_Ddl_Table::TYPE_SMALLINT,
);
foreach ($entityAttributesCodes as $code => $type) {
    $installer->addAttribute('quote', $code, array('type' => $type, 'visible' => false));
    $installer->addAttribute('order', $code, array('type' => $type, 'visible' => false));
}


$entityAttributesCodes = array(
    'amgiftwrap_amount' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
    'base_amgiftwrap_amount' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
);
foreach ($entityAttributesCodes as $code => $type) {
    $installer->addAttribute('creditmemo', $code, array('type' => $type, 'visible' => false));
    $installer->addAttribute('quote_address', $code, array('type' => $type, 'visible' => false));
    $installer->addAttribute('order', $code, array('type' => $type, 'visible' => false));
    $installer->addAttribute('invoice', $code, array('type' => $type, 'visible' => false));
}

$entityAttributesCodes = array(
    'amgiftwrap_amount_refunded' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
    'amgiftwrap_amount_invoiced' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
    'base_amgiftwrap_amount_refunded' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
    'base_amgiftwrap_amount_invoiced' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
);
foreach ($entityAttributesCodes as $code => $type) {
    $installer->addAttribute('order', $code, array('type' => $type, 'visible' => false));
}


$this->endSetup(); 