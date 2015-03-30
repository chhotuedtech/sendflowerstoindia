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


/**
* This file is part of the Mirasvit_SeoFilter project.
* Install Script for seofilter rewrite table.
*
* Mirasvit_SeoFilter is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License version 3 as
* published by the Free Software Foundation.
*
* This script is distributed in the hope that it will be useful, but WITHOUT
* ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
* FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
*
* PHP version 5
*
* @category Mirasvit_SeoFilter
* @package Mirasvit_SeoFilter
* @author Michael TÃ¼rk <tuerk@flagbit.de>
* @copyright 2012 Flagbit GmbH & Co. KG (http://www.flagbit.de). All rights served.
* @license http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
* @version 0.1.0
* @since 0.1.0
*/
$installer = $this;

$installer->startSetup();

$installer->run("
-- DROP TABLE IF EXISTS {$this->getTable('seofilter/rewrite')};
CREATE TABLE IF NOT EXISTS {$this->getTable('seofilter/rewrite')} (
  `rewrite_id` int(10) unsigned NOT NULL auto_increment,
  `attribute_code` varchar(60) NOT NULL default '',
  `option_id` int(10) unsigned NOT NULL,
  `rewrite` varchar(60) NOT NULL default '',
  `store_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY  (`rewrite_id`),
  CONSTRAINT `UNQ_FILTERURLS_REWRITE_ATTRIBUTECODE_OPTIONID_STOREID` UNIQUE (`attribute_code`, `option_id`, `store_id`),
  CONSTRAINT `UNQ_FILTERURLS_REWRITE_REWRITE_STOREID` UNIQUE (`rewrite`, `store_id`),
  CONSTRAINT `FK_FILTERURLS_REWRITE_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$this->getTable('core/store')}` (`store_id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Rewrite values for attribute options' AUTO_INCREMENT=1;
");

$installer->endSetup();

?>