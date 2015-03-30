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


class Mirasvit_Helpdesk_Test_Model_TicketTest extends EcomDev_PHPUnit_Test_Case
{
    protected $_model;

    protected function setUp()
    {
        parent::setUp();
        $this->_model = Mage::getModel('helpdesk/ticket')->getCollection()
            ->addFieldToFilter('ticket_id', 2)
            ->joinFields()
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
         $this->assertEquals('Sales', $this->_model->getDepartment());
         $this->assertEquals('Open', $this->_model->getStatus());
         $this->assertEquals('High', $this->_model->getPriority());
         $this->assertEquals('John Doe', $this->_model->getCustomerName());
         $this->assertEquals('john@example.com', $this->_model->getCustomerEmail());
         $this->assertEquals('Mike Peterson', $this->_model->getUserName());
     }

    /**
     * @test
     * @loadFixture data
     *
     * @doNotIndex catalog_product_price
     */
     public function addMessageUnknownCustomerTest()
     {
         $customer = new Varien_Object();
         $customer->setName('John Doe');
         $customer->setEmail('john@example.com');

         $this->_model->addMessage('message 1', $customer, false, true);
         $message = Mage::getModel('helpdesk/message')->getCollection()->getLastItem();
         $this->assertEquals('message 1', $message->getBody());
         $this->assertEquals(2, $message->getTicketId());
         $this->assertEquals(Mirasvit_Helpdesk_Model_Config::MESSAGE_PUBLIC, $message->getType());
         $this->assertEquals('John Doe', $message->getCustomerName());
         $this->assertEquals('john@example.com', $message->getCustomerEmail());
         $this->assertEquals('John Doe', $this->_model->getLastReplyName());
     }

     /**
      * @test
      * @loadFixture data2
      *
      * @doNotIndex catalog_product_price
      */
      public function getLastMessageTest()
      {
          $this->assertEquals('Test message body', $this->_model->getLastMessage()->getBody());
          $this->assertEquals('Test message body', $this->_model->getLastMessageHtmlText());
      }

     // /**
     //  * @test
     //  * @loadFixture fields
     //  *
     //  * @doNotIndex catalog_product_price
     //  */
     //  public function getFieldTest()
     //  {
     //    $ticket = Mage::getModel('helpdesk/ticket')->load(2);
     //    $this->assertEquals('Test message body', $ticket->getFieldLogin());
     //  }
}
