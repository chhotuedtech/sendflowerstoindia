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



class Mirasvit_SEO_Test_Helper_DataTest extends EcomDev_PHPUnit_Test_Case
{
    public function setUp() {
        parent::setUp();
        $this->parseHelper = Mage::helper('seoautolink');
    }

    /**
     * @test
     * @loadFixture data
     */
    public function getLinksTest() {
      $links = $this->parseHelper->getLinks('text text keyword text');
      $this->assertequals(1, count($links));

      $links = $this->parseHelper->getLinks('text text keyword 2 text');
      $this->assertequals(2, count($links));

      $links = $this->parseHelper->getLinks('ÑÐµÐºÑÑ ÑÐµÐºÑÑ ÐºÐµÐ¹Ð²Ð¾ÑÐ´ ÑÐµÐºÑÑ');
      $this->assertequals(1, count($links));

      $links = $this->parseHelper->getLinks('ÑÐµÐºÑÑ ÑÐµÐºÑÑ \' " \\ ÐºÐµÐ¹Ð²Ð¾ÑÐ´ ÑÐµÐºÑÑ');
      $this->assertequals(1, count($links));
    }


    /**
     * @test
     */
    public function isAlnumTest() {
      $this->assertequals(true, $this->parseHelper->is_alnum('3'));
      $this->assertequals(true, $this->parseHelper->is_alnum('f'));
      $this->assertequals(true, $this->parseHelper->is_alnum('Ñ'));
      $this->assertequals(true, $this->parseHelper->is_alnum('0'));
      $this->assertequals(false, $this->parseHelper->is_alnum(' '));
      $this->assertequals(false, $this->parseHelper->is_alnum('-'));
      $this->assertequals(8, $this->parseHelper->strlen('ÑÐ¿Ð¸Ð½Ð½Ð¸Ð½Ð³'));
      $this->assertequals(8, $this->parseHelper->strlen('spinning'));
      // $this->assertequals('ÐÑÑÑÑÑÑÐ¸Ð½Ð½Ð¸Ð½Ð³Ð¸ ÑÐ»ÑÑÑÐ°Ð»Ð°Ð¹Ñ', $this->parseHelper->substr_replace('ÐÑÑÑÐ¸Ðµ ÑÐ¿Ð¸Ð½Ð½Ð¸Ð½Ð³Ð¸ ÑÐ»ÑÑÑÐ°Ð»Ð°Ð¹Ñ', 'ÑÑÑÑ', 3, 8));
      $this->assertequals('ÐÑÑÑÐ¸Ðµ ÑÑÑÑÐ½Ð¸Ð½Ð³Ð¸ ÑÐ»ÑÑÑÐ°Ð»Ð°Ð¹Ñ', $this->parseHelper->str_replace('ÑÐ¿Ð¸Ð½', 'ÑÑÑÑ', 'ÐÑÑÑÐ¸Ðµ ÑÐ¿Ð¸Ð½Ð½Ð¸Ð½Ð³Ð¸ ÑÐ»ÑÑÑÐ°Ð»Ð°Ð¹Ñ'));
      $this->assertequals('Ð', $this->parseHelper->get_char('ÐÑÑÑÐ¸Ðµ', 0));
      $this->assertequals('Ñ', $this->parseHelper->get_char('ÐÑÑÑÐ¸Ðµ', 3));
      $this->assertequals(false, $this->parseHelper->get_char('ÐÑÑÑÐ¸Ðµ', 6));
      $this->assertequals(false, $this->parseHelper->get_char('Link2', -1));
    }

    /**
     * @test
     * @dataProvider parseProvider
     */
    public function parseTest($text, $links, $expectedResult, $replacementCount = false) {
        $result = $this->parseHelper->_addLinks($text, $links, $replacementCount);

        $this->assertequals($expectedResult, $result);
    }

    public function parseProvider()
    {

        $link1 = new Varien_Object(array(
            'keyword' => 'link1',
            'url' => 'http://link1.com',
            ));
        $link2 = new Varien_Object(array(
            'keyword' => 'link2',
            'url' => 'http://link2.com',
            ));
        $link3 = new Varien_Object(array(
            'keyword' => 'link2 link3',
            'url' => 'http://link3.com',
            ));
        $link4 = new Varien_Object(array(
            'keyword' => 'ÑÐ¿Ð¸Ð½Ð½Ð¸Ð½Ð³',
            'url' => 'http://spinning.com',
            ));
        $link5 = new Varien_Object(array(
            'keyword' => 'spinning',
            'url' => 'http://spinning.com',
            ));
        $link6 = new Varien_Object(array(
            'keyword' => 'solid',
            'url' => 'http://solid.com',
            ));
        $link7 = new Varien_Object(array(
            'keyword' => 'à¹à¸à¸à¸²à¸£à¸¥à¹à¸­à¸à¸­à¸´à¸à¹à¸à¸·à¹à¸­à¸ªà¸¡à¸±à¸à¸£à¸ªà¸¡à¸²à¸à¸´à¸à¸ªà¸²à¸¡à¸²à¸£à¸à¸³à¹à¸à¹2à¸§à¸´à¸à¸µ',
            'url' => 'http://thai.com',
            ));
        $link8 = new Varien_Object(array(
            'keyword' => '123link',
            'url' => 'http://123link.com',
            ));
        return array(
          array('link1 link2', array($link1, $link2, $link3), "<a href='http://link1.com' class='autolink' >link1</a> <a href='http://link2.com' class='autolink' >link2</a>"),
          array('link1 link2 link3', array($link1, $link2, $link3), "<a href='http://link1.com' class='autolink' >link1</a> <a href='http://link2.com' class='autolink' >link2</a> link3"),
          array("<a href='http://link1.com' class='autolink' >link1 aaaa</a>", array($link1, $link3, $link2), "<a href='http://link1.com' class='autolink' >link1 aaaa</a>"),
          array('link2 link3', array($link3, $link2), "<a href='http://link3.com' class='autolink' >link2 link3</a>"),
          array('Link2', array($link3, $link2), "<a href='http://link2.com' class='autolink' >Link2</a>"),
          array('Best spinnings ultra', array($link5), "Best spinnings ultra"),
          array('ÐÑÑÑÐ¸Ðµ ÑÐ¿Ð¸Ð½Ð½Ð¸Ð½Ð³Ð¸ ÑÐ»ÑÑÑÐ°Ð»Ð°Ð¹Ñ', array($link4), "ÐÑÑÑÐ¸Ðµ ÑÐ¿Ð¸Ð½Ð½Ð¸Ð½Ð³Ð¸ ÑÐ»ÑÑÑÐ°Ð»Ð°Ð¹Ñ"),

          array('link1, Link2', array($link1, $link2), "<a href='http://link1.com' class='autolink' >link1</a>, <a href='http://link2.com' class='autolink' >Link2</a>"),
          array('link2', array($link2), "<a href='http://link2.com' class='autolink' >link2</a>"),
          array('link2text', array($link2), "link2text"),
          array('textlink2', array($link2), "textlink2"),
          array('textlink2text', array($link2), "textlink2text"),
          array(',link2,', array($link2), ",<a href='http://link2.com' class='autolink' >link2</a>,"),
          array(',link2text', array($link2), ",link2text"),
          array('textlink2,', array($link2), "textlink2,"),
          array('link2,', array($link2), "<a href='http://link2.com' class='autolink' >link2</a>,"),
          array(',link2', array($link2), ",<a href='http://link2.com' class='autolink' >link2</a>"),
          array('Link2', array($link2), "<a href='http://link2.com' class='autolink' >Link2</a>"),
          array('Link2text', array($link2), "Link2text"),
          array('textLink2', array($link2), "textLink2"),
          array('textLink2text', array($link2), "textLink2text"),
          array(',Link2,', array($link2), ",<a href='http://link2.com' class='autolink' >Link2</a>,"),
          array(',Link2text', array($link2), ",Link2text"),
          array('textLink2,', array($link2), "textLink2,"),
          array('Link2,', array($link2), "<a href='http://link2.com' class='autolink' >Link2</a>,"),
          array(',Link2', array($link2), ",<a href='http://link2.com' class='autolink' >Link2</a>"),
          array('link1 â â â â Link2', array($link2), "link1 â â â â <a href='http://link2.com' class='autolink' >Link2</a>"),
          array('Pinot Noir, link1 and Pinot Meunier link1', array($link1), "Pinot Noir, <a href='http://link1.com' class='autolink' >link1</a> and Pinot Meunier link1", 1),
          array('Pinot Noir, link1 and Pinot Meunier Link1', array($link1), "Pinot Noir, <a href='http://link1.com' class='autolink' >link1</a> and Pinot Meunier Link1", 1),
          array('à¸à¸±à¹à¸à¸à¸­à¸à¸à¸²à¸£à¸ªà¸¡à¸±à¸à¸£à¸ªà¸¡à¸²à¸à¸´à¸ à¹à¸à¸à¸²à¸£à¸¥à¹à¸­à¸à¸­à¸´à¸à¹à¸à¸·à¹à¸­à¸ªà¸¡à¸±à¸à¸£à¸ªà¸¡à¸²à¸à¸´à¸à¸ªà¸²à¸¡à¸²à¸£à¸à¸³à¹à¸à¹2à¸§à¸´à¸à¸µ à¹à¸¡à¸·à¹à¸­à¸à¹à¸²à¸à¹à¸à¹à¸²à¸ªà¸¹à¹à¸«à¸à¹à¸²à¹à¸®à¸¡à¹à¸à¸à¸à¸­à¸à¹à¸£à¸²à¸«à¸²à¸à¸à¹à¸²à¸',
            array($link7),
            "à¸à¸±à¹à¸à¸à¸­à¸à¸à¸²à¸£à¸ªà¸¡à¸±à¸à¸£à¸ªà¸¡à¸²à¸à¸´à¸ <a href='http://thai.com' class='autolink' >à¹à¸à¸à¸²à¸£à¸¥à¹à¸­à¸à¸­à¸´à¸à¹à¸à¸·à¹à¸­à¸ªà¸¡à¸±à¸à¸£à¸ªà¸¡à¸²à¸à¸´à¸à¸ªà¸²à¸¡à¸²à¸£à¸à¸³à¹à¸à¹2à¸§à¸´à¸à¸µ</a> à¹à¸¡à¸·à¹à¸­à¸à¹à¸²à¸à¹à¸à¹à¸²à¸ªà¸¹à¹à¸«à¸à¹à¸²à¹à¸®à¸¡à¹à¸à¸à¸à¸­à¸à¹à¸£à¸²à¸«à¸²à¸à¸à¹à¸²à¸"),
array(
'With durable solid, wood solidp framing, generous padding and plush stain-resistant microfiber asdsolid. aaaaSolid. upholstery. Solid solid djaslkd asdkjklas ssolid, solid
solid,
solid
Solid.
Solid',
array($link6),
"With durable <a href='http://solid.com' class='autolink' >solid</a>, wood solidp framing, generous padding and plush stain-resistant microfiber asdsolid. aaaaSolid. upholstery. <a href='http://solid.com' class='autolink' >Solid</a> <a href='http://solid.com' class='autolink' >solid</a> djaslkd asdkjklas ssolid, <a href='http://solid.com' class='autolink' >solid</a>
<a href='http://solid.com' class='autolink' >solid</a>,
<a href='http://solid.com' class='autolink' >solid</a>
<a href='http://solid.com' class='autolink' >Solid</a>.
<a href='http://solid.com' class='autolink' >Solid</a>"
),
          array('text 123link text,', array($link8), "text <a href='http://123link.com' class='autolink' > 123link </a> text,"),
         );
    }

}
