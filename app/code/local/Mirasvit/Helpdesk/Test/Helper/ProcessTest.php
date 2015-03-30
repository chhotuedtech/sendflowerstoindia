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


class Mirasvit_Helpdesk_Test_Model_ProcessTest extends EcomDev_PHPUnit_Test_Case
{
    protected $helper;
    protected function setUp()
    {
        parent::setUp();
        $this->helper = Mage::helper('helpdesk/process');
        $this->mockConfigMethod(array(
            'getDefaultStatus' => 1,
            'getContactFormDefaultDepartment'=>2,
            'getDefaultPriority'=>3,
            'getNotificationIsShowCode' => true
        ));
        if (!Mage::registry('isSecureArea')) {
            Mage::register('isSecureArea', true);
        }
    }

    protected function mockConfigMethod($methods)
    {
        $config = $this->getModelMock('helpdesk/config', array_keys($methods));
        foreach ($methods as $method => $value) {
            $config->expects($this->any())
                ->method($method)
                ->will($this->returnValue($value));
        }
        $this->replaceByMock('singleton', 'helpdesk/config', $config);
    }

    /**
     * @test
     * @loadFixture data2
     */
    public function createFromPostUnregistered() {
        Mage::app()->setCurrentStore(1);
        $post = array(
            'name' => 'Ticket 1',
            'priority_id' => 1,
            'department_id' => 2,
            'message' => 'message 1',
            'customer_name' => 'John Doe',
            'customer_email' => 'john_unregistered@example.com',
        );
        $this->helper->createFromPost($post, Mirasvit_Helpdesk_Model_Config::CHANNEL_CONTACT_FORM);
        $ticket = Mage::getModel('helpdesk/ticket')->getCollection()->getLastItem();
        $message = Mage::getModel('helpdesk/message')->getCollection()->getLastItem();
        $this->assertEquals('Ticket 1', $ticket->getName());
        $this->assertEquals(1, $ticket->getPriorityId());
        $this->assertEquals(2, $ticket->getDepartmentId());
        $this->assertEquals(null, $ticket->getCustomerId());
        $this->assertEquals('John Doe', $ticket->getCustomerName());
        $this->assertEquals('john_unregistered@example.com', $ticket->getCustomerEmail());
        $this->assertEquals(null, $message->getCustomerId());
        $this->assertEquals('message 1', $message->getBody());
    }

    /**
     * @test
     * @loadFixture data2
     */
    public function createFromPostRegistered() {
        Mage::app()->setCurrentStore(1);
        $customer = Mage::getModel('customer/customer')->load(2);
        $post = array(
            'name' => 'Ticket 1',
            'priority_id' => 1,
            'department_id' => 2,
            'message' => 'message 1',
            'customer_name' => 'John Doe',
            'customer_email' => 'john@example.com',
        );
        $this->helper->createFromPost($post, Mirasvit_Helpdesk_Model_Config::CHANNEL_CONTACT_FORM);
        $ticket = Mage::getModel('helpdesk/ticket')->getCollection()->getLastItem();
        $message = Mage::getModel('helpdesk/message')->getCollection()->getLastItem();
        $this->assertEquals('Ticket 1', $ticket->getName());
        $this->assertEquals(1, $ticket->getPriorityId());
        $this->assertEquals(2, $ticket->getDepartmentId());
        $this->assertEquals(2, $ticket->getCustomerId());
        $this->assertEquals('John Doe', $ticket->getCustomerName());
        $this->assertEquals('john@example.com', $ticket->getCustomerEmail());
        $this->assertEquals(2, $message->getCustomerId());
        $this->assertEquals('message 1', $message->getBody());
    }

    /**
     * @test
     * @loadFixture data2
     */
    public function createFromPostContactForm() {
        Mage::app()->setCurrentStore(1);
        $post = array(
            'name' => 'Ticket 1',
            'message' => 'message 1',
            'customer_name' => 'John Doe',
            'customer_email' => 'john@company.com'
        );
        $this->helper->createFromPost($post, Mirasvit_Helpdesk_Model_Config::CHANNEL_CONTACT_FORM);
        $ticket = Mage::getModel('helpdesk/ticket')->getCollection()->getLastItem();
        $message = Mage::getModel('helpdesk/message')->getCollection()->getLastItem();
        $this->assertEquals('Ticket 1', $ticket->getName());
        $this->assertEquals('John Doe', $ticket->getCustomerName());
        $this->assertEquals('john@company.com', $ticket->getCustomerEmail());
        $this->assertEquals('John Doe', $ticket->getLastReplyName());
        $this->assertEquals(3, $ticket->getPriorityId());
        $this->assertEquals(2, $ticket->getDepartmentId());
        $this->assertEquals(0, $ticket->getCustomerId());
        $this->assertEquals(0, $message->getCustomerId());
        $this->assertEquals('message 1', $message->getBody());
        $this->assertEquals('John Doe', $message->getCustomerName());
        $this->assertEquals('john@company.com', $message->getCustomerEmail());
    }

    private function mockGenerateCode()
    {
        $helper = $this->getHelperMock('helpdesk/string', array('generateTicketCode'));
        $helper->expects($this->any())
                ->method('generateTicketCode')
                ->will($this->returnValue('AAA-123-45678'));
        $this->replaceByMock('helper', 'helpdesk/string', $helper);
        return $helper;
    }

    /**
     * @test
     * @loadFixture data
     * @doNotIndex catalog_product_price
     */
    public function processEmailUnregisteredCustomerTest()
    {
        $this->mockGenerateCode();
        $helper = Mage::helper('helpdesk/email');
        //mail from uknown customer
        $email = Mage::getModel('helpdesk/email')->load(2);
        $ticket = $helper->processEmail($email);

        $this->assertEquals('Ticket Subject 2', $ticket->getName());
        $this->assertEquals('John Doe', $ticket->getCustomerName());
        $this->assertEquals('John Doe', $ticket->getLastReplyName());
        $this->assertEquals(1, $ticket->getReplyCnt());
        $this->assertEquals('john@example.com', $ticket->getCustomerEmail());
        $this->assertEquals(1, $ticket->getStatusId());
        $this->assertEquals(2, $ticket->getDepartmentId());
        $this->assertEquals(3, $ticket->getPriorityId());
        $this->assertEquals(1, $ticket->getStoreId());


        // reply for first mail
        $email = Mage::getModel('helpdesk/email')->load(4);
        $ticket = $helper->processEmail($email);
        $this->assertEquals('Ticket Subject 2', $ticket->getName());
        $this->assertEquals(2, $ticket->getReplyCnt());
        $this->assertEquals(1, $email->getIsProcessed());

    }
    /**
     * @test
     * @loadFixture data2
     */
    public function processEmailRegisteredCustomerTest()
    {
        $this->mockGenerateCode();
        $helper = Mage::helper('helpdesk/email');
        //mail from known customer
        $email = Mage::getModel('helpdesk/email')->load(2);
        $ticket = $helper->processEmail($email);
        $this->assertEquals('Ticket Subject 2', $ticket->getName());
        $this->assertEquals(2, $ticket->getCustomerId());
        $this->assertEquals('John Doe', $ticket->getCustomerName());
        $this->assertEquals('john@example.com', $ticket->getCustomerEmail());
        $message = $ticket->getLastMessage();
        $this->assertEquals('John Doe', $message->getCustomerName());
        $this->assertEquals('john@example.com', $message->getCustomerEmail());
    }
    /**
     * @test
     * @loadFixture data3
     * @doNotIndex catalog_product_price
     */
    public function processEmailUserTest()
    {
        $this->mockGenerateCode();
        $helper = Mage::helper('helpdesk/email');
        //mail from uknown customer
        $email = Mage::getModel('helpdesk/email')->load(2);
        $ticket1 = $helper->processEmail($email);
        $this->assertEquals('Ticket Subject 2', $ticket1->getName());

        //reply for first mail by staff
        $email = Mage::getModel('helpdesk/email')->load(3);
        $ticket2 = $helper->processEmail($email);
        $this->assertEquals('Ticket Subject 2', $ticket2->getName());
        $this->assertEquals(2, $ticket2->getUserId());
        $this->assertEquals($ticket1->getId(), $ticket2->getId());
    }

    /**
     * @test
     * @loadFixture data2
     */
    public function processEmailWithAttachments()
    {
        $this->mockGenerateCode();
        $helper = Mage::helper('helpdesk/email');
        //mail from known customer
        $email = Mage::getModel('helpdesk/email')->load(2);
        $ticket = $helper->processEmail($email);
        $message = $ticket->getLastMessage();
        $this->assertEquals(2, $email->getAttachments()->count());
        $this->assertEquals(2, $message->getAttachments()->count());
    }

    /**
     * @test
     * @loadFixture data4
     */
    public function processHtmlEmail()
    {
        $this->mockGenerateCode();
        $helper = Mage::helper('helpdesk/email');
        //mail from known customer
        $email = Mage::getModel('helpdesk/email')->load(2);
        $ticket = $helper->processEmail($email);
        $message = $ticket->getLastMessage();
        $this->assertEquals(
'TEST MESSAGE HEAD

body, body, body www.x.com', $message->getBody());
    }

	protected function getFixt($file) {
		return file_get_contents(dirname(__FILE__)."/ProcessTest/parsebody/$file");
	}

    /**
     * @test
     * @dataProvider bodyProvider
     */
	public function parseBodyTest($input, $format, $expected) {
        return;//need fix
		$result = $this->helper->parseBody($this->getFixt($input), $format);
		$this->assertEquals($result, $this->getFixt($expected));
	}

    public function bodyProvider()
    {
        return array(
            array('email1.html', 'TEXT/PLAIN', 'email1_expected.html'),
			array('email2.html', 'TEXT/HTML', 'email2_expected.html'),
        );
    }
}

