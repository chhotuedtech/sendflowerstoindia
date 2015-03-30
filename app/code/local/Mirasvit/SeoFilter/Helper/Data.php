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
/**
 * Helper for simple normalization of strings and translation issues
 *
 * @category Mirasvit_SeoFilter
 * @package Mirasvit_SeoFilter
 * @author Michael TÃ¼rk <tuerk@flagbit.de>
 * @copyright 2012 Flagbit GmbH & Co. KG (http://www.flagbit.de). All rights served.
 * @license http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @version 0.1.0
 * @since 0.1.0
 */
class Mirasvit_SeoFilter_Helper_Data extends Mage_Core_Helper_Abstract
{
   /**
	 * normalize Characters
	 * Example: Ã¼ -> ue
	 *
	 * @param string $string
	 * @return string
	 */
	public function normalize($string)
	{
	    $table = array(
	        'Å '=>'S',  'Å¡'=>'s',  'Ä'=>'Dj', 'Ä'=>'dj', 'Å½'=>'Z',  'Å¾'=>'z',  'Ä'=>'C',  'Ä'=>'c',  'Ä'=>'C',  'Ä'=>'c',
	        'Ã'=>'A',  'Ã'=>'A',  'Ã'=>'A',  'Ã'=>'A',  'Ã'=>'Ae', 'Ã'=>'A',  'Ã'=>'A',  'Ã'=>'C',  'Ã'=>'E',  'Ã'=>'E',
	        'Ã'=>'E',  'Ã'=>'E',  'Ã'=>'I',  'Ã'=>'I',  'Ã'=>'I',  'Ã'=>'I',  'Ã'=>'N',  'Ã'=>'O',  'Ã'=>'O',  'Ã'=>'O',
	        'Ã'=>'O',  'Ã'=>'Oe', 'Ã'=>'O',  'Ã'=>'U',  'Ã'=>'U',  'Ã'=>'U',  'Ã'=>'Ue', 'Ã'=>'Y',  'Ã'=>'B',  'Ã'=>'Ss',
	        'Ã '=>'a',  'Ã¡'=>'a',  'Ã¢'=>'a',  'Ã£'=>'a',  'Ã¤'=>'ae', 'Ã¥'=>'a',  'Ã¦'=>'a',  'Ã§'=>'c',  'Ã¨'=>'e',  'Ã©'=>'e',
	        'Ãª'=>'e',  'Ã«'=>'e',  'Ã¬'=>'i',  'Ã­'=>'i',  'Ã®'=>'i',  'Ã¯'=>'i',  'Ã°'=>'o',  'Ã±'=>'n',  'Ã²'=>'o',  'Ã³'=>'o',
	        'Ã´'=>'o',  'Ãµ'=>'o',  'Ã¶'=>'oe', 'Ã¸'=>'o',  'Ã¹'=>'u',  'Ãº'=>'u',  'Ã»'=>'u',  'Ã½'=>'y',  'Ã½'=>'y',  'Ã¾'=>'b',
	        'Ã¿'=>'y',  'Å'=>'R',  'Å'=>'r',  'Ã¼'=>'ue', '/'=>'',   '-'=>'',   '&'=>'',   ' '=>'',   '('=>'',   ')'=>''
	    );

	    $string = strtr($string, $table);
	    $string = Mage::getSingleton('catalog/product_url')->formatUrlKey($string);
	    $string = str_replace(array('-'), '', $string); //ÑÐ±Ð¸ÑÐ°ÐµÐ¼ ÑÐ¸ÑÑÐµÐ¼Ð½ÑÐµ ÑÐ°Ð·Ð´ÐµÐ»Ð¸ÑÐµÐ»Ð¸ ÑÐ°ÑÑÐµÐ¹ ÑÑÐ»Ð°
	    return $string;
	}
}