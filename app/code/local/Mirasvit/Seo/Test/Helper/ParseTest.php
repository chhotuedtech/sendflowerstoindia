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




class Mirasvit_Seo_Test_Helper_ParseTest extends EcomDev_PHPUnit_Test_Case
{
    public function setUp() {
        parent::setUp();
        $this->parseHelper = Mage::helper('seo/parse');
        //we load product from collection, because we need to load its attributes
        $collection = Mage::getModel('catalog/product')->getCollection()
        				->addFieldToFilter('entity_id', 1)
        				->addAttributeToSelect('*')
        				;
        $product = $collection->getFirstItem();
    	$category = Mage::getModel('catalog/category')->load(2);
    	$store = Mage::getModel('core/store')->load(2);

      $category->setMetaTitle('Category meta title');

    	$this->objects = array(
    		'product'=>$product,
    		'category'=>$category,
    		'store'=>$store,
		);

    }

    /**
     * @test
     * @loadFixture products
     * @doNotIndex catalog_product_price
     * @dataProvider parseProvider
     */
    public function testParse($template, $expectedResult) {
    	$result = $this->parseHelper->parse($template, $this->objects);

        $this->assertequals($expectedResult, $result);
    }

    public function parseProvider()
    {
        return array(
          array('[product_name][, sku: {product_sku}]', 'HTC Touch Diamond [ru, uk] {ru, uk}, sku: sku1'),
          array('[product_name][, sku: {product_unknown}]', 'HTC Touch Diamond [ru, uk] {ru, uk}'),
          array('[product_name][product_unknown]', 'HTC Touch Diamond [ru, uk] {ru, uk}'),
          array('price [product_price]', 'price $750.00'),
          array('[category_name]', 'Default Category'),
          array('[category_meta_title]', 'Category meta title'),
        );
    }

}
