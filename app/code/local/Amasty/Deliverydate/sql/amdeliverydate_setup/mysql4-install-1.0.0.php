<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
$installer = $this;

$installer->startSetup();

$this->run("

CREATE TABLE `{$this->getTable('amdeliverydate/deliverydate')}` (
  `deliverydate_id` mediumint(8) unsigned NOT NULL auto_increment,
  `order_id`        int(10) unsigned NOT NULL,
  `increment_id`    varchar(50) NOT NULL,
  `date`            date NOT NULL default '0000-00-00',
  `time`            varchar(255) NOT NULL,
  `comment`         varchar(255) NOT NULL,
  PRIMARY KEY  (`deliverydate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE `{$this->getTable('amdeliverydate/holidays')}` (
  `holiday_id`  mediumint(8) unsigned NOT NULL auto_increment,
  `store_ids`   varchar(255) NOT NULL,
  `year`        smallint(4) NOT NULL,
  `month`       smallint(2) NOT NULL,
  `day`         smallint(2) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY  (`holiday_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE `{$this->getTable('amdeliverydate/dinterval')}` (
  `dinterval_id` mediumint(8) unsigned NOT NULL auto_increment,
  `store_ids`    varchar(255) NOT NULL,
  `from_year`    smallint(4) NOT NULL,
  `from_month`   smallint(2) NOT NULL,
  `from_day`     smallint(2) NOT NULL,
  `to_year`      smallint(4) NOT NULL,
  `to_month`     smallint(2) NOT NULL,
  `to_day`       smallint(2) NOT NULL,
  `description`  varchar(255) NOT NULL,
  PRIMARY KEY  (`dinterval_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE `{$this->getTable('amdeliverydate/tinterval')}` (
  `tinterval_id`  mediumint(8) unsigned NOT NULL auto_increment,
  `store_ids`     varchar(255) NOT NULL,
  `from`          varchar(255) NOT NULL,
  `to`            varchar(255) NOT NULL,
  `sorting_order` smallint(5) unsigned NOT NULL,
  PRIMARY KEY  (`tinterval_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

");

$installer->endSetup();