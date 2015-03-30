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


// require 'Mirasvit/imap/vendor/autoload.php';
class Mirasvit_Helpdesk_Test_Model_FetchTest extends EcomDev_PHPUnit_Test_Case
{
	protected static $connection;
    protected $helper;
	protected $mailbox;

    public static function setUpBeforeClass()
    {
        $server = new Mirasvit_Ddeboer_Imap_Server('imap.gmail.com');
        static::$connection = $server->authenticate('support2@mirasvit.com.ua', '6Vl5gxZmxpeE');
    }

    protected static function getConnection()
    {
        return static::$connection;
    }

    protected function createMailbox($name)
    {
        $uniqueName = $name . uniqid();
        //
        // try {
        //     $mailbox = static::getConnection()->getMailbox($uniqueName);
        //     $this->deleteMailbox($mailbox);
        // } catch (MailboxDoesNotExistException $e) {
        //     // Ignore mailbox not found
        // }

        return static::getConnection()->createMailbox($uniqueName);
    }

    protected function deleteMailbox($mailbox)
    {
        $mailbox->delete();
    }

    protected function createTestMessage(
        $mailbox,
        $subject = 'Don\'t panic!',
        $contents = 'Don\'t forget your towel',
        $from = 'someone@there.com',
        $to = 'me@here.com'
    ) {
        $message = "From: $from\r\n"
            . "To: $to\r\n"
            . "Subject: $subject\r\n"
            . "\r\n"
            . "$contents";

        $mailbox->addMessage($message);
    }


    protected function setUp()
    {
        parent::setUp();
        $resource = Mage::getSingleton('core/resource');
        $con = $resource->getConnection('core_write');
        $con->query("delete from {$resource->getTableName('helpdesk/email')}");
        $this->helper = Mage::helper('helpdesk/fetch');
	    $this->mailbox = $this->createMailbox('test-message');
    }

    public function tearDown()
    {
        $this->deleteMailbox($this->mailbox);
    }

    protected function getFixt($file) {
		return file_get_contents(dirname(__FILE__)."/FetchTest/fixtures/$file");
	}

    public function testCreateEmailInlineImageAttachment3() {
        $message = $this->getFixt('email_attachment_inline_image3.eml');
        $this->mailbox->addMessage($message);

        $message = $this->mailbox->getMessage(1);
        $email = $this->helper->createEmail($message);
        $attachments = array();
        foreach ($email->getAttachments() as $attachment) {
            $attachments[] = $attachment;
        }
        $this->assertEquals(1, count($attachments));

        $a = $attachments[0];
        $a->getSubtype();die;
        print_r($a);die;
        $this->assertEquals('', $a->getName());
        $this->assertEquals('image', $a->getType());
    }

    public function testCreateEmailHtml5() {
        $message = $this->getFixt('email_attachment_txt.eml');
        $this->mailbox->addMessage($message);

        $message = $this->mailbox->getMessage(1);
        // echo $message->getFrom()->getName();
        // echo $message->getBodyHtml();
        // echo $message->getBodyText();
        // die;
        $email = $this->helper->createEmail($message);
        $attachments = array();
        foreach ($email->getAttachments() as $attachment) {
            $attachments[] = $attachment;
        }
        $this->assertEquals(1, count($attachments));

        $a = $attachments[0];
        $this->assertEquals('Today 10-18.txt', $a->getName());
        $this->assertEquals('text', $a->getType());
        $this->assertEquals('asfasdf', $a->getBody());
        $this->assertEquals('TEXT/HTML', $email->getFormat());
        $this->assertNotEquals(0, strlen($email->getBody()));
    }



	public function testCreateEmailPlain() {
		$message = $this->getFixt('email_plain.eml');
		$this->mailbox->addMessage($message);
		$message = $this->mailbox->getMessage(1);
		$email = $this->helper->createEmail($message);

	    $this->assertEquals('<CAE6S9wiAJ9rELsoZdG7rBp70Tsj5EG+Lg7mSaf_32ANvKt7qGw@mail.gmail.com>', $email->getMessageId());
	    $this->assertEquals('Test Email', $email->getSubject());
	    $this->assertEquals('terry@mirasvit.com.ua', $email->getFromEmail());
	    $this->assertEquals('john@mirasvit.com.ua', $email->getToEmail());
        $this->assertEquals('body', trim($email->getBody()));
	    $this->assertEquals('TEXT/PLAIN', $email->getFormat());
	    $this->assertEquals('Terry Bib', $email->getSenderName());
	    $this->assertNotEmpty($email->getHeaders());
    }

	public function testCreateEmailHtml() {
		$message = $this->getFixt('email_html.eml');
		$this->mailbox->addMessage($message);

		$message = $this->mailbox->getMessage(1);
		$email = $this->helper->createEmail($message);
        //$this->assertEquals(
//'<div dir="ltr">Test Email Body<div class="gmail_default" style="font-family:&#39;arial narrow&#39;,sans-serif;display:inline"></div><div><br></div><div><b>bold text<div class="gmail_default" style="font-family:&#39;arial narrow&#39;,sans-serif;display:inline">
//</div></b></div><div><br></div><div><div class="gmail_default" style="display:inline"><font face="arial black, sans-serif" color="#ff0000"><u><i>werwerwerwerwer</i></u></font></div></div><ul><li>aaaa<br></li><li>bbbb<br>
//</li><li>ccc<div class="gmail_default" style="font-family:&#39;arial narrow&#39;,sans-serif;display:inline">c</div><br></li></ul></div>'
//, trim($email->getBody()));
	    $this->assertEquals('TEXT/HTML', $email->getFormat());
    }

    public function testCreateEmailHtml2() {
        $message = $this->getFixt('email_html2.eml');
        $this->mailbox->addMessage($message);

        $message = $this->mailbox->getMessage(1);
        // echo $message->getFrom()->getName();
        // echo $message->getBodyHtml();
        // echo $message->getBodyText();
        $email = $this->helper->createEmail($message);
        // echo $email->getSenderName();
        // echo $email->getBody();
        $this->assertEquals('contact@liq9.com', $email->getFromEmail());
        $this->assertEquals('à¸à¹à¸²à¸¢à¸à¸£à¸´à¸à¸²à¸£à¸¥à¸¹à¸à¸à¹à¸² | LIQ9.com', $email->getSenderName());
        $this->assertEquals('TEXT/HTML', $email->getFormat());
        $this->assertNotEquals(0, strlen($email->getBody()));
    }

    public function testCreateEmailHtml3() {
        $message = $this->getFixt('email_html3.eml');
        $this->mailbox->addMessage($message);

        $message = $this->mailbox->getMessage(1);
        // echo $message->getFrom()->getName();
        // echo $message->getSubject();
        // echo $message->getBodyHtml();
        // echo $message->getBodyText();
        $email = $this->helper->createEmail($message);
        // echo $email->getSenderName();
        // echo $email->getSubject();
        // echo $email->getBody();
        $this->assertEquals('detazeta@gmail.com', $email->getFromEmail());// Ð¼Ñ Ð´Ð¾Ð»Ð¶Ð½Ñ Ð±ÑÐ°ÑÑ Ð¸Ð· reply to ÐµÑÐ»Ð¸ Ð¾Ð½Ð¾ ÐµÑÑÑ
        $this->assertEquals('Offline Message from Sittidet: à¸à¸à¸ªà¸­à¸à¸ªà¹à¸à¸à¹à¸²à¸ zopim...', $email->getSubject());
        $this->assertEquals('TEXT/HTML', $email->getFormat());

        $this->assertEquals(false, $this->helper->createEmail($message));
    }

    public function testCreateEmailHtml4() {
        $this->markTestIncomplete(
          'Some error is here.'
        );
        $message = $this->getFixt('email_html4.eml');
        $this->mailbox->addMessage($message);

        $message = $this->mailbox->getMessage(1);
        // echo $message->getFrom()->getName();
        // echo $message->getBodyHtml();
        // echo $message->getBodyText();
        // die;
        $email = $this->helper->createEmail($message);
        // echo $email->getSenderName();
        // echo $email->getBody();
        $this->assertEquals('postmaster@hotmail.com', $email->getFromEmail());
        // $this->assertEquals('à¸à¹à¸²à¸¢à¸à¸£à¸´à¸à¸²à¸£à¸¥à¸¹à¸à¸à¹à¸² | LIQ9.com', $email->getSenderName());
        $this->assertEquals('TEXT/PLAIN', $email->getFormat());
        $this->assertNotEquals(0, strlen($email->getBody()));
    }

	public function testCreateEmailRussain() {
		$message = $this->getFixt('email_russian.eml');
		$this->mailbox->addMessage($message);

		$message = $this->mailbox->getMessage(1);
		// echo $message->getCharset();
		$email = $this->helper->createEmail($message);
        $this->assertEquals(
'Ð¡Ð¿Ð°ÑÐ¸Ð±Ð¾ ÐÐ»ÐµÐºÑÐ°Ð½Ð´ÐµÑ, Ñ Ð½Ð°ÑÑÑÐ¿Ð°ÑÑÐ¸Ð¼ ÐÐ¾Ð²ÑÐ¼ ÐÐ¾Ð´Ð¾Ð¼ ÐÐ°Ñ Ð¸ ÐºÐ¾Ð¼Ð°Ð½Ð´Ñ Mirasvit!', trim($email->getBody()));
	    $this->assertEquals('TEXT/PLAIN', $email->getFormat());
    }

	public function testCreateEmailWithAttachment() {
		$message = $this->getFixt('email_attachment.eml');
		$this->mailbox->addMessage($message);

		$message = $this->mailbox->getMessage(1);
		$email = $this->helper->createEmail($message);
		$attachments = array();
		foreach ($email->getAttachments() as $attachment) {
			$attachments[] = $attachment;
		}
		$this->assertEquals(3, count($attachments));

        $a = $attachments[0];
		$this->assertEquals('image.jpg', $a->getName());
		$this->assertEquals('image', $a->getType());
		$this->assertEquals(5237, $a->getSize());
		$this->assertEquals(5237, strlen($a->getBody()));
	}

	public function testCreateEmailWithInlineAttachment() {
		$message = $this->getFixt('email_attachment_inline_image.eml');
		$this->mailbox->addMessage($message);

		$message = $this->mailbox->getMessage(1);
		$email = $this->helper->createEmail($message);
		$attachments = array();
		foreach ($email->getAttachments() as $attachment) {
			$attachments[] = $attachment;
		}
		$this->assertEquals(2, count($attachments));

        $a = $attachments[0];
        $this->assertEquals('2d2ydh23.duj.png', $a->getName());
		$this->assertEquals('image', $a->getType());
		$this->assertEquals(24969, $a->getSize());
		$this->assertEquals(24969, strlen($a->getBody()));
	}

    public function testCreateEmailWithInlineAttachment2() {
        $message = $this->getFixt('email_attachment_inline_image2.eml');
        $this->mailbox->addMessage($message);

        $message = $this->mailbox->getMessage(1);
        $email = $this->helper->createEmail($message);
        $attachments = array();
        foreach ($email->getAttachments() as $attachment) {
            $attachments[] = $attachment;
        }

        $this->assertEquals(1, count($attachments));

        $a = $attachments[0];
        $this->assertEquals('Outlook.jpg', $a->getName());
        $this->assertEquals('image', $a->getType());
    }
}
