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


class Mirasvit_Helpdesk_Test_Model_NotificationTest extends EcomDev_PHPUnit_Test_Case
{
    protected $helper;

	protected function getExpectedMail($code) {
		return file_get_contents(dirname(__FILE__)."/NotificationTest/expected/$code.html");
	}

    protected function setUp()
    {
        parent::setUp();
		$this->helper = Mage::helper('helpdesk/notification');
		$this->helper->emails = array();
        Mage::helper('msttest/mock')->mockSingletonMethod('helpdesk/config', array(
            'getNotificationHistoryRecordsNumber' => 3,
        ));

    }

    // /**
    //  * @test
    //  * @loadFixture data4
    //  * @doNotIndex catalog_product_price
    //  */
    // public function newEvent() {
    //     $customer = Mage::getModel('customer/customer')->load(2);
    //     $ticket = Mage::getModel('helpdesk/ticket')->load(2);
    //     $this->helper->newEvent(Mirasvit_Helpdesk_Model_Config::NOTIFICATION_EVENT_NEW_TICKET, $ticket);
    //     $result = Mage::helper('msttest/string')->html2txt($this->helper->emails[0]);
    //     $this->assertEquals(2, count($this->helper->emails));
    // }


    /**
     * @test
     * @loadFixture data3
     * @doNotIndex catalog_product_price
     */
    public function newTicketByCustomer() {
		$customer = Mage::getModel('customer/customer')->load(2);
 		$ticket = Mage::getModel('helpdesk/ticket')->load(2);
 		$this->helper->newTicket($ticket, $customer, false, Mirasvit_Helpdesk_Model_Config::CUSTOMER, Mirasvit_Helpdesk_Model_Config::MESSAGE_PUBLIC);
        $result = Mage::helper('msttest/string')->html2txt($this->helper->emails[0]);
        // echo $result;die;
		$this->assertEquals($this->getExpectedMail('new_ticket'), $result);
        $result = Mage::helper('msttest/string')->html2txt($this->helper->emails[1]);
       // echo $result;die;
		$this->assertEquals($this->getExpectedMail('staff_new_ticket'), $result);
    }

    /**
     * @test
     * @loadFixture data3
     * @doNotIndex catalog_product_price
     */
    public function newMessageByCustomer() {
		$customer = Mage::getModel('customer/customer')->load(2);
 		$ticket = Mage::getModel('helpdesk/ticket')->load(2);
 		$this->helper->newMessage($ticket, $customer, false, Mirasvit_Helpdesk_Model_Config::CUSTOMER, Mirasvit_Helpdesk_Model_Config::MESSAGE_PUBLIC);
        $result = Mage::helper('msttest/string')->html2txt($this->helper->emails[0]);
        // echo $result;die;
		$this->assertEquals($this->getExpectedMail('staff_new_message'), $result);
    }

    /**
     * @test
     * @loadFixture data3
     * @doNotIndex catalog_product_price
     */
    public function newMessageByUser() {
		$customer = Mage::getModel('customer/customer')->load(2);
		$user = Mage::getModel('admin/user')->load(2);
 		$ticket = Mage::getModel('helpdesk/ticket')->load(2);
 		$this->helper->newMessage($ticket, $customer, $user, Mirasvit_Helpdesk_Model_Config::USER, Mirasvit_Helpdesk_Model_Config::MESSAGE_PUBLIC);
        $result = Mage::helper('msttest/string')->html2txt($this->helper->emails[0]);
        // echo $result;die;
		$this->assertEquals($this->getExpectedMail('new_message'), $result);
    }
}

