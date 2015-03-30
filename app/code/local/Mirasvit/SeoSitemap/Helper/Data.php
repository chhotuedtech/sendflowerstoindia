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


class Mirasvit_SeoSitemap_Helper_Data extends Mage_Core_Helper_Abstract
{

    /************************/

    public function getSitemapTitle() {
        return Mage::getSingleton('seositemap/config')->getFrontendSitemapH1();
    }

    public function getSitemapUrl() {
    	return Mage::helper('mstcore/urlrewrite')->getUrl('SEOSITEMAP', 'MAP');
    }

    public function checkArrayPattern($stringVal, $patternArr, $caseSensativeVal = false) {
        if (!is_array($patternArr)) {
            return false;
        }
    	foreach ($patternArr as $patternVal) {
    		if ($this->checkPattern($stringVal, $patternVal, $caseSensativeVal)) {
    			return true;
    		}
    	}

    	return false;
    }

    public function removeHostUrl($urlWithHost)
    {
        $parts = parse_url($urlWithHost);
        $url = $parts['path'];
        $url = str_replace('index.php/', '', $url);
        $url = str_replace('index.php', '', $url);
        if (isset($parts['query'])) {
            $url.= '?'.$parts['query'];
        }
        return $url;
    }

    public function checkPattern($url, $pattern, $caseSensative = false)
    {
        $string = $this->removeHostUrl($url);

        if (!$caseSensative) {
            $string  = strtolower($string);
            $pattern = strtolower($pattern);
        }

        $parts = explode('*', $pattern);
        $index = 0;

        $shouldBeFirst = true;
        $shouldBeLast  = true;

        foreach ($parts as $part) {
            if ($part == '') {
                $shouldBeFirst = false;
                continue;
            }

            $index = strpos($string, $part, $index);

            if ($index === false) {
                return false;
            }

            if ($shouldBeFirst && $index > 0) {
                return false;
            }

            $shouldBeFirst = false;
            $index += strlen($part);
        }


        if (count($parts) == 1) {
            return $string == $pattern;
        }

        $last = end($parts);
        if ($last == '') {
            return true;
        }

        if (strrpos($string, $last) === false) {
            return false;
        }

        if(strlen($string) - strlen($last) - strrpos($string, $last) > 0) {
          return false;
        }

        return true;
    }
}