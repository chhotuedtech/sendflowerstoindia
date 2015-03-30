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


class Mirasvit_SeoSitemap_Helper_DataTest extends EcomDev_PHPUnit_Test_Case
{
    protected $helper;
    protected function setUp()
    {
        parent::setUp();
        $this->helper = Mage::helper('seositemap/data');
    }

    /**
     * @test
     */
    public function removeHostUrl() {
        $result = $this->helper->removeHostUrl('http://example.com/page.html?query=1');
        $this->assertEquals('/page.html?query=1', $result);

        $result = $this->helper->removeHostUrl('/furniture/living-room.html');
        $this->assertEquals('/furniture/living-room.html', $result);

        $result = $this->helper->removeHostUrl('http://example.com/index.php/page.html?query=1');
        $this->assertEquals('/page.html?query=1', $result);

        $result = $this->helper->removeHostUrl('http://seo.dva/index.php/page.html?query=1');
        $this->assertEquals('/page.html?query=1', $result);
    }


    /**
     * @test
     * @dataProvider checkPatternProvider
     */
    public function checkPatternTest($expected, $string, $pattern) {
        $result = $this->helper->checkPattern($string, $pattern);
        $this->assertEquals($expected, $result);
    }

    public function checkPatternProvider()
    {
        return array(
            array(true, '/furniture/living-room.html', '/furniture/*'),
            array(true, 'http://seo.dva/furniture/living-room.html', '/furniture/*'),
        );
    }
}