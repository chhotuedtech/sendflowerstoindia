<?php
/**
 * @copyright  Amasty (http://www.amasty.com)
 */

$this->startSetup();

$this->getConnection()->addColumn(
    $this->getTable('amgiftwrap/design'),
    'sort',
    "INT(11) COMMENT 'Sort position'"
);


$this->getConnection()->addColumn(
    $this->getTable('amgiftwrap/cards'),
    'sort',
    "INT(11) COMMENT 'Sort position'"
);

$this->endSetup();