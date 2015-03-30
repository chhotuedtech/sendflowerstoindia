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


class Mirasvit_Helpdesk_Helper_MailTest extends EcomDev_PHPUnit_Test_Case
{
    protected $helper;
    protected function getExpectedMail($code) {
        return file_get_contents(dirname(__FILE__)."/MailTest/expected/$code.html");
    }

    protected function setUp()
    {
        parent::setUp();
        $this->helper = Mage::helper('helpdesk/mail');
        $this->helper->emails = array();
        $this->markTestSkipped('We don"t use this testclass.');
    }


    /**
     * @test
     * @loadFixture data
     */
    public function sendNotificationNewTicketTest()
    {
        $this->helper->sendNotificationNewTicket();
        $result = Mage::helper('msttest/string')->html2txt($this->helper->emails[0]['text']);
        // echo $result;die;
        $this->assertEquals($this->getExpectedMail('notification_new_ticket_template'), $result);
        $this->assertEquals('test@example.com', $this->helper->emails[0]['recipient_email']);
        $this->assertEquals('Test Name', $this->helper->emails[0]['recipient_name']);
    }

    /**
     * @test
     * @loadFixture data
     */
    public function sendNotificationStaffNewTicketTest()
    {
        $this->helper->sendNotificationStaffNewTicket();
        $result = Mage::helper('msttest/string')->html2txt($this->helper->emails[0]['text']);
        // echo $result;die;
        $this->assertEquals($this->getExpectedMail('notification_staff_new_ticket_template'), $result);
        $this->assertEquals('test@example.com', $this->helper->emails[0]['recipient_email']);
        $this->assertEquals('Test Name', $this->helper->emails[0]['recipient_name']);
    }

    /**
     * @test
     * @loadFixture data
     */
    public function sendNotificationNewMessageTest()
    {
        $this->helper->sendNotificationNewMessage();
        $result = Mage::helper('msttest/string')->html2txt($this->helper->emails[0]['text']);
        // echo $result;die;
        $this->assertEquals($this->getExpectedMail('notification_new_message_template'), $result);
        $this->assertEquals('test@example.com', $this->helper->emails[0]['recipient_email']);
        $this->assertEquals('Test Name', $this->helper->emails[0]['recipient_name']);
    }

    /**
     * @test
     * @loadFixture data
     */
    public function sendNotificationStaffNewMessageTest()
    {
        $this->helper->sendNotificationStaffNewMessage();
        $result = Mage::helper('msttest/string')->html2txt($this->helper->emails[0]['text']);
        // echo $result;die;
        $this->assertEquals($this->getExpectedMail('notification_staff_new_message_template'), $result);
        $this->assertEquals('test@example.com', $this->helper->emails[0]['recipient_email']);
        $this->assertEquals('Test Name', $this->helper->emails[0]['recipient_name']);
    }

    /**
     * @test
     * @loadFixture data
     */
    public function sendNotificationThirdNewMessageTest()
    {
        $this->helper->sendNotificationThirdNewMessage();
        $result = Mage::helper('msttest/string')->html2txt($this->helper->emails[0]['text']);
        // echo $result;die;
        $this->assertEquals($this->getExpectedMail('notification_third_new_message_template'), $result);
        $this->assertEquals('test@example.com', $this->helper->emails[0]['recipient_email']);
        $this->assertEquals('Test Name', $this->helper->emails[0]['recipient_name']);
    }

    /**
     * @test
     * @loadFixture data
     */
    public function sendNotificationReminderTest()
    {
        $this->helper->sendNotificationReminder();
        $result = Mage::helper('msttest/string')->html2txt($this->helper->emails[0]['text']);
        // echo $result;die;
        $this->assertEquals($this->getExpectedMail('notification_reminder_template'), $result);
        $this->assertEquals('test@example.com', $this->helper->emails[0]['recipient_email']);
        $this->assertEquals('Test Name', $this->helper->emails[0]['recipient_name']);
    }

    /**
     * @test
     * @loadFixture data
     */
    public function sendNotificationRuleTest()
    {
        $this->helper->sendNotificationRule();
        $result = Mage::helper('msttest/string')->html2txt($this->helper->emails[0]['text']);
        // echo $result;die;
        $this->assertEquals($this->getExpectedMail('notification_rule_template'), $result);
        $this->assertEquals('test@example.com', $this->helper->emails[0]['recipient_email']);
        $this->assertEquals('Test Name', $this->helper->emails[0]['recipient_name']);
    }

    /**
     * @test
     * @loadFixture data
     */
    public function sendNotificationStaffNewSatisfactionTest()
    {
        $this->helper->sendNotificationStaffNewSatisfaction();
        $result = Mage::helper('msttest/string')->html2txt($this->helper->emails[0]['text']);
        // echo $result;die;
        $this->assertEquals($this->getExpectedMail('notification_staff_new_satisfaction_template'), $result);
        $this->assertEquals('test@example.com', $this->helper->emails[0]['recipient_email']);
        $this->assertEquals('Test Name', $this->helper->emails[0]['recipient_name']);
    }

}