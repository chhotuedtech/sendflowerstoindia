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
 * @package   Help Desk MX
 * @version   1.0.7
 * @build     907
 * @copyright Copyright (C) 2014 Mirasvit (http://mirasvit.com/)
 */


class Mirasvit_Helpdesk_Test_Model_MessageTest extends EcomDev_PHPUnit_Test_Case
{
    protected $_model;

    protected function setUp()
    {
        parent::setUp();
        $this->_model = Mage::getModel('helpdesk/message')->getCollection()
            ->addFieldToFilter('message_id', 2)
            ->getFirstItem();
    }

    /**
     * @test
     * @loadFixture data
     *
     * @doNotIndex catalog_product_price
     */
     public function basicTest()
     {
         $this->assertEquals('Message Body', $this->_model->getBody());
         //$this->assertEquals('John Doe', $this->_model->getCustomerName());
         $this->assertEquals('Mike Peterson', $this->_model->getUserName());
     }

}
