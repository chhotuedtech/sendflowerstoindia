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


class Mirasvit_SeoAutolink_Helper_Data extends Mage_Core_Helper_Abstract
{
    private $links;
    protected $_sizeExplode    = 0;
    protected $_isExcludedTags = true;
    protected $_isSkipLinks;

    public function getConfig() {
        return Mage::getSingleton('seoautolink/config');
    }

    public function getLinks($text) {
        Varien_Profiler::start('seoautolink_getLinks');
        $links = Mage::getModel('seoautolink/link')
                    ->getCollection()
                    ->addActiveFilter()
                    ->addStoreFilter(Mage::app()->getStore())
                    ;

        $textArrayWithMaxSymbols = $this->splitText($text); //retrn array

        $where = array();
        foreach ($textArrayWithMaxSymbols as $splitTextVal) {
            $where[] =  "lower('" . addslashes($splitTextVal) . "') LIKE CONCAT(" . "'%'" . ", lower(keyword), " . "'%'" . ")";
        }

        $links->getSelect()->where(implode(" OR ", $where))->order('sort_order ASC');

        Varien_Profiler::stop('seoautolink_getLinks');

        return $links;
    }

    public function replaceSpecialCharacters($source) {
        // substitute some special html characters with their 'real' value
        $searchTamp = array('&amp;Eacute;',
                            '&amp;Euml;',
                            '&amp;Oacute;',
                            '&amp;eacute;',
                            '&amp;euml;',
                            '&amp;oacute;',
                            '&amp;Agrave;',
                            '&amp;Egrave;',
                            '&amp;Igrave;',
                            '&amp;Iacute;',
                            '&amp;Iuml;',
                            '&amp;Ograve;',
                            '&amp;Ugrave;',
                            '&amp;agrave;',
                            '&amp;egrave;',
                            '&amp;igrave;',
                            '&amp;iacute;',
                            '&amp;iuml;',
                            '&amp;ograve;',
                            '&amp;ugrave;',
                            '&amp;Ccedil;',
                            '&amp;ccedil;',
                            '&amp;ecirc;',
                           );
        $replaceTamp = array('Ã',
                             'Ã',
                             'Ã',
                             'Ã©',
                             'Ã«',
                             'Ã³',
                             'Ã',
                             'Ã',
                             'Ã',
                             'Ã',
                             'Ã',
                             'Ã',
                             'Ã',
                             'Ã ',
                             'Ã¨',
                             'Ã¬',
                             'Ã­',
                             'Ã¯',
                             'Ã²',
                             'Ã¹',
                             'Ã',
                             'Ã§',
                             'Ãª',
                            );
        $searchT = array('&Eacute;',
                         '&Euml;',
                         '&Oacute;',
                         '&eacute;',
                         '&euml;',
                         '&oacute;',
                         '&Agrave;',
                         '&Egrave;',
                         '&Igrave;',
                         '&Iacute;',
                         '&Iuml;',
                         '&Ograve;',
                         '&Ugrave;',
                         '&agrave;',
                         '&egrave;',
                         '&igrave;',
                         '&iacute;',
                         '&iuml;',
                         '&ograve;',
                         '&ugrave;',
                         '&Ccedil;',
                         '&ccedil;'
                        );
        $replaceT = array('Ã',
                          'Ã',
                          'Ã',
                          'Ã©',
                          'Ã«',
                          'Ã³',
                          'Ã',
                          'Ã',
                          'Ã',
                          'Ã',
                          'Ã',
                          'Ã',
                          'Ã',
                          'Ã ',
                          'Ã¨',
                          'Ã¬',
                          'Ã­',
                          'Ã¯',
                          'Ã²',
                          'Ã¹',
                          'Ã',
                          'Ã§'
                          );

      $source = $this->str_replace($searchTamp, $replaceTamp, $source);
      $source = $this->str_replace($searchT, $replaceT, $source);

      return $source;
    }

    public function addLinks($text) {
        if (strpos(Mage::helper('core/url')->getCurrentUrl(), '/checkout/onepage/')) {
            return $text;
        }

        if ($this->checkSkipLinks() === true) {
            return $text;
        }
        $text = $this->replaceSpecialCharacters($text);

        return $this->_addLinks($text, $this->getLinks($text));
    }

    public function _addLinks($text, $links, $replacementCount = false) {
        if (!$links || count($links) == 0) {
            return $text;
        }

        Varien_Profiler::start('seoautolink_addLinks');
        foreach ($links  as $link) {
            $replaceKeyword = $link->getKeyword();
            $urltitle = $link->getUrlTitle()?"title='{$link->getUrlTitle()}' ":"";
            $nofollow = $link->getIsNofollow()?'rel=\'nofollow\' ':'';
            $target = $link->getUrlTarget()?"target='{$link->getUrlTarget()}' ":"";
            $html = "<a href='{$link->getUrl()}' {$urltitle}{$target}{$nofollow}class='autolink' >{$link->getKeyword()}</a>";

            $keyword = $link->getKeyword();
            $maxReplacements = -1;
            if ($link->getMaxReplacements() > 0) {
                $maxReplacements = $link->getMaxReplacements();
            }
            if ($replacementCount) { //for tests
                $maxReplacements = $replacementCount;
            }
            $direction = 0;
            switch ($link->getOccurence()){
                case Mirasvit_SeoAutolink_Model_Config_Source_Occurence::FIRST:
                    $direction = 0;
                    break;
                case Mirasvit_SeoAutolink_Model_Config_Source_Occurence::LAST:
                    $direction = 1;
                    break;
                case Mirasvit_SeoAutolink_Model_Config_Source_Occurence::RANDOM:
                    $direction = rand(0,1);
                    break;
            }

            $text = $this->replace($keyword, $html, $text, $maxReplacements, $replaceKeyword, $direction);

        }
           Varien_Profiler::stop('seoautolink_addLinks');
        return $text;
    }
    //replace words and left the same cases
    protected function replace($find, $replace, $source, $maxReplacements, $replaceKeyword = false, $direct = false) {
        $patternsForExclude = $this->getExcludedAutoTags();

          // matches for these expressions will be replaced with a unique placeholder
        $preg_patterns = array(
              '#<!--.*?-->#s'       // html comments
            , '#<a[^>]*>.*?</a>#si' // html links
            , '#<[^>]+>#'           // generic html tag
            //~ , '#&[^;]+;#'           // special html characters
            //~ , '#[^ÃÃÃÃ©Ã«Ã³ÃÃÃÃÃÃÃÃ Ã¨Ã¬Ã­Ã¯Ã²Ã¹ÃÃ§\w\s]+#'   // all non alfanumeric characters, spaces and some special characters
        );

        if ($patternsForExclude) {
            $preg_patterns = array_merge($patternsForExclude, $preg_patterns);
        }

        $pl = new Mirasvit_TextPlaceholder($source, $preg_patterns);
        // raw text, void of any html.

        $source = $pl->get_tokenized_text();
        if($maxReplacements == -1) {
            preg_match_all('/' . $replaceKeyword . '/i', $source, $replaceKeywordVariations);
        } else {
            preg_match_all('/' . $replaceKeyword . '/i', $source, $replaceKeywordVariations, PREG_OFFSET_CAPTURE);
        }

        // we will later need this to put the html we stripped out, back in.
        $translation_table = $pl->get_translation_table();
        // reconstruct the original text (now with links)
        foreach ($translation_table as $key=>$value)
        {
            $source = $this->str_replace($key, $value, $source);
        }

        if (isset($replaceKeywordVariations[0])) {
            $keywordVariations = $replaceKeywordVariations[0];
            if (!empty($keywordVariations)) {
                if($maxReplacements == -1) {
                  $keywordVariations = array_unique($keywordVariations);
                } elseif ($direct == 1) {
                  $keywordVariations = array_slice($keywordVariations, -$maxReplacements);
                } else {
                  $keywordVariations = array_slice($keywordVariations, 0, $maxReplacements);
                }
                foreach ($keywordVariations as $keywordValue) {
                    $pl = new Mirasvit_TextPlaceholder($source, $preg_patterns);
                    $source = $pl->get_tokenized_text();

                    //replace all links variations
                    if($maxReplacements == -1) {
                        $replaceForVariation = preg_replace('/(\\<a.*?\\>)(.*?)(\\<\\/a\\>)/', $this->prepareReplacement($keywordValue), $replace);
                        // $replaceForVariation = $replace;
                        $source = $this->addLinksToSource($maxReplacements, $direct, $source, $keywordValue, $replaceForVariation);
                    } else {
                        $replaceForVariation = preg_replace('/(\\<a.*?\\>)(.*?)(\\<\\/a\\>)/', $this->prepareReplacement($keywordValue[0]), $replace);
                        // $replaceForVariation = $replace;
                        $source = $this->addLinksToSource($maxReplacements, $direct, $source, $keywordValue[0], $replaceForVariation, true);
                    }

                    $translation_table = $pl->get_translation_table();
                    foreach ($translation_table as $key=>$value)
                    {
                        $source = $this->str_replace($key, $value, $source);
                    }
                }
                $this->_sizeExplode = 0;
            }
        }

        return $source;
    }

    public function prepareReplacement($keyword)
    {
        if(is_numeric(mb_substr($keyword, 0, 1))) {
            $replacement = "$1 $keyword $3";
        } else {
            $replacement = '$1' . $keyword . '$3';
        }

        return $replacement;
    }

    public function addLinksToSource($maxReplacements, $direct, $source, $replaceKeyword, $replace, $replaceNumber = false)
    {
        if ($direct == 1) {
                $source = strrev($source);
                $replaceKeyword = strrev($replaceKeyword);
                $replace = strrev($replace);
        }

        $explodeSource = explode($replaceKeyword, $source); // explode text
        $nextSymbol= array('',' ', ',', '.', '!', '?', "\n", "\r", "\r\n");    // symbols after the word
        $prevSymbol= array(',',' ', "\n", "\r", "\r\n"); // symbols before the word


        $sizeExplodeSource = count($explodeSource);
        $size = 0;
        $prepareSourse = '';

        if($replaceNumber) { //if  $maxReplacements != -1 we make only one replace
            $replaceNumberOne = false;
        }
        foreach ($explodeSource as $keySource => $valSource) {
            $size++;
            if ($maxReplacements == -1) {
                // replace all links with written letters
                if (($size < $sizeExplodeSource)
                    && ($direct == 0)
                    && (((!empty($explodeSource[$keySource + 1][0]))
                        && (in_array($explodeSource[$keySource + 1][0], $nextSymbol)))
                            || (empty($explodeSource[$keySource + 1][0])))
                    && ((empty($explodeSource[$keySource][strlen($explodeSource[$keySource])-1]))
                        || ((!empty($explodeSource[$keySource][strlen($explodeSource[$keySource])-1]))
                            && (in_array($explodeSource[$keySource][strlen($explodeSource[$keySource])-1], $prevSymbol))))) {
                                $prepareSourse .= $valSource . $replace;
                } elseif (($size < $sizeExplodeSource)
                      && ($direct == 1)
                      && (((!empty($explodeSource[$keySource][strlen($explodeSource[$keySource])-1]))
                          && (in_array($explodeSource[$keySource][strlen($explodeSource[$keySource])-1], $nextSymbol)))
                              || (empty($explodeSource[$keySource][strlen($explodeSource[$keySource])-1])))
                      && ((empty($explodeSource[$keySource + 1][0]))
                          || ((!empty($explodeSource[$keySource + 1][0]))
                            && (in_array($explodeSource[$keySource + 1][0], $prevSymbol))))) {
                                $prepareSourse .= $valSource . $replace;
                } elseif ($size < $sizeExplodeSource) {
                    $prepareSourse .= $valSource . $replaceKeyword;
                } else {
                    $prepareSourse .= $valSource;
                }
            } else {
                // maxReplacements for written letters
                if (($size < $sizeExplodeSource)
                    && ($direct == 0)
                    && (((!empty($explodeSource[$keySource + 1][0]))
                        && (in_array($explodeSource[$keySource + 1][0], $nextSymbol)))
                            || (empty($explodeSource[$keySource + 1][0])))
                    && ((empty($explodeSource[$keySource][strlen($explodeSource[$keySource])-1]))
                        || ((!empty($explodeSource[$keySource][strlen($explodeSource[$keySource])-1]))
                            && (in_array($explodeSource[$keySource][strlen($explodeSource[$keySource])-1], $prevSymbol))))
                    && ($this->_sizeExplode < $maxReplacements)
                    && !$replaceNumberOne) {
                        $prepareSourse .= $valSource . $replace;
                        $this->_sizeExplode++;
                        $replaceNumberOne = true;
                } elseif (($size < $sizeExplodeSource)
                      && ($direct == 1)
                      && (((!empty($explodeSource[$keySource][strlen($explodeSource[$keySource])-1]))
                          && (in_array($explodeSource[$keySource][strlen($explodeSource[$keySource])-1], $nextSymbol)))
                              || (empty($explodeSource[$keySource][strlen($explodeSource[$keySource])-1])))
                      && ((empty($explodeSource[$keySource + 1][0]))
                          || ((!empty($explodeSource[$keySource + 1][0]))
                            && (in_array($explodeSource[$keySource + 1][0], $prevSymbol))))
                      && ($this->_sizeExplode < $maxReplacements)
                      && !$replaceNumberOne) {
                        $prepareSourse .= $valSource . $replace;
                        $this->_sizeExplode++;
                        $replaceNumberOne = true;
                } elseif ($size < $sizeExplodeSource) {
                    $prepareSourse .= $valSource . $replaceKeyword;
                } else {
                    $prepareSourse .= $valSource;
                }
            }
        }

        if ($direct == 1) {
                $prepareSourse = strrev($prepareSourse);
        }

        return $prepareSourse;
    }

    /*
    * split text to create the sql query
    */
    public function splitText($text) {
        $maxTextSymbols   = 1000; //number of characters for split the text
        $numberReturnWords = 5;      //number of words which will in every part of the split text
        $textSymbolsCount = iconv_strlen($text);
        if ($textSymbolsCount > $maxTextSymbols) {
            $selectNumber = ceil($textSymbolsCount/$maxTextSymbols);
        }

        $textArrayWithMaxSymbols = array();
        if (isset($selectNumber)) {
            $textArray = str_split($text, $maxTextSymbols);
            foreach($textArray as $textKey => $textVal) {
                if ($textKey == 0) {
                    $keyBefore = $textKey;
                    $textArrayWithMaxSymbols[$textKey] =  $textVal;
                } else {
                    $currentText = explode(" ", $textVal, $numberReturnWords);
                    if (count($currentText) == $numberReturnWords) {
                        $currentTextShift = $currentText;
                        array_shift($currentTextShift);
                        $textArrayWithMaxSymbols[$textKey] = implode(" ", $currentTextShift);
                        $currentTextPop = $currentText;
                        array_pop($currentTextPop);
                        $textArrayWithMaxSymbols[$keyBefore] .=  implode(" ", $currentTextPop);
                        $keyBefore = $textKey;
                    } else {
                        $textArrayWithMaxSymbols[$textKey] = implode(" ", $currentText);
                    }
                }
            }
        }

        if (empty($textArrayWithMaxSymbols)) {
            $textArrayWithMaxSymbols[] = $text;
        }

        return $textArrayWithMaxSymbols;
    }

    /*
    * analog of ctype_alnum, but with support of Cyrillic
    */
    public function is_alnum($string) {
        return (bool)preg_match("/^[a-zA-Z\p{Cyrillic}0-9]+$/u", $string);
    }

    public function strlen($string) {
        return Mage::helper('core/string')->strlen($string);
    }

    public function substr($string, $offset, $length = null) {
        return Mage::helper('core/string')->substr($string, $offset, $length);
    }

    public function substr_replace($output, $replace, $posOpen, $posClose) {
        return substr_replace($output, $replace, $posOpen, $posClose);
    }

    public function stripos($source, $find, $pos = null) {
        if (extension_loaded('mbstring')) {
            $pos = Mage::helper('core/string')->strpos(mb_convert_case($source, MB_CASE_LOWER, "UTF-8"),  mb_convert_case($find, MB_CASE_LOWER, "UTF-8"), $pos);
        } else {
            $pos = stripos($source, $find, $pos);
        }
        return $pos;
    }

    public function get_char($source, $pos) {
        if ($pos < 0 || $pos >= $this->strlen($source)) {
            return false;
        }
        return $this->substr($source, $pos, 1);
    }

    public function str_replace($search, $replace, $source) {
        return str_replace($search, $replace, $source);
    }

    public function strrev($string) {
        return Mage::helper('core/string')->strrev($string);
    }

    public function checkSkipLinks() {
        if($this->_isSkipLinks === false) {
            return false;
        }
        if(!$skipLinks = Mage::registry('skip_auto_links')) {
            $skipLinks = $this->getConfig()->getSkipLinks(Mage::app()->getStore()->getStoreId());
            if ($skipLinks) {
                Mage::register('skip_auto_links', $skipLinks);
            } else {
                $this->_isSkipLinks = false;
            }
        }
        if (Mage::helper('seoautolink/pattern')->checkArrayPattern(
                    Mage::getSingleton('core/url')->parseUrl(Mage::helper('core/url')->getCurrentUrl())->getPath(),
                    $skipLinks
                )
            ) {
            $this->_isSkipLinks = true;
            return true;
        }

        $this->_isSkipLinks = false;
        return false;
    }

    public function getExcludedAutoTags() {
      if(!Mage::registry('excluded_auto_links_tags') && $this->_isExcludedTags) {
            $excludedTags = $this->getConfig()->getExcludedTags(Mage::app()->getStore()->getId());
            if ($excludedTags) {
              Mage::register('excluded_auto_links_tags', $excludedTags);
            } else {
              $this->_isExcludedTags = false;
            }
        } elseif ($this->_isExcludedTags) {
            $excludedTags = Mage::registry('excluded_auto_links_tags');
        }

        $patternsForExclude = array();
        if (isset($excludedTags)) {
            foreach ($excludedTags as $tag) {
                $tag = str_replace(' ', '', $tag);
                $patternsForExclude[] = '#' . '<' . $tag . '[^>]*>.*?</' . $tag . '>' . '#si';
            }
            return $patternsForExclude;
        }

        return false;
    }
}

class Mirasvit_TextPlaceholder {

    var $_translation_table = array();

    function __construct($text, $patterns) {
        $this->_tokenized_text = preg_replace_callback($patterns, array($this, 'placeholder'), $text);
    }

    function get_translation_table() {
        return $this->_translation_table;
    }

    function get_tokenized_text() {
        return $this->_tokenized_text;
    }

    function placeholder($matches) {
        $sequence = count($this->_translation_table);
        $placeholder = ' xkjndsfkjnakcx' . $sequence . 'cxmkmweof329jc ';
        $this->_translation_table[$placeholder] = $matches[0];
        return $placeholder;
    }

}