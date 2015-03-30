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


class Mirasvit_Seo_Helper_DataTest extends EcomDev_PHPUnit_Test_Case
{
    public function setUp() {
        parent::setUp();
        $this->dataHelper = Mage::helper('seo');
    }

    /**
     * @test
     * @dataProvider parseProvider
     */
    public function testParse($string, $pattern,  $expectedResult) {
    	$result = $this->dataHelper->checkPattern($string, $pattern);

        $this->assertequals($expectedResult, $result);
    }

    public function parseProvider()
    {
        return array(
          array('/sneakers/basketballsasdfchuhe/?p=2', '/sneakers/', false),
          array('/sneakers/', '/sneakers', false),
          array('/sneakers', '/sneakers', true),
          array('/sneakers/mars/?p=2', '/sneakers/*', true),
          array('/sneakers/?p=2', '/sneakers*', true),
          array('/sneakers/?p=2', '*?p=*', true),
          array('/sneakers/basketballschuhe/?p=1&dir=asc&limit=24&mode=grid&order=position', '*?p=*', true),
          array('/sneakers/?p=2', '*?p=2', true),
          array('/sneakers/basketballschuhe/?p=', '*?p=*', true),
          array('/sneakers/?p=2', '?p=*', false),
          array('/sneakers/?p=2', '/?p=*', false),
          array('/basketballschuhe/?p=1&dir=asc&limit=24&mode=grid&order=position', '/?p=*', false),
          array('/sneakers/basketballschuhe/?p=2', '*?p=2', true),
          array('/sneakers/basketballschuhe/?p=2', '*?p=', false),
        );
    }

}
