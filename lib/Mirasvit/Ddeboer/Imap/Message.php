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



// namespace Mirasvit_Ddeboer\Imap;

// use Mirasvit_Ddeboer\Imap\Message\EmailAddress;
// use Mirasvit_Ddeboer\Imap\Exception\MessageDeleteException;
// use Mirasvit_Ddeboer\Imap\Exception\MessageMoveException;

/**
 * An IMAP message (e-mail)
 */
class Mirasvit_Ddeboer_Imap_Message extends Mirasvit_Ddeboer_Imap_Message_Part
{
    protected $stream;
    protected $headers;
    protected $attachments;

    /**
     * @var boolean
     */
    protected $keepUnseen = false;

    /**
     * Constructor
     *
     * @param \resource $stream        IMAP stream
     * @param int       $messageNumber Message number
     */
    public function __construct($stream, $messageNumber)
    {
        $this->stream = $stream;
        $this->messageNumber = $messageNumber;

        $this->loadStructure();
    }

    /**
     * Get message id
     *
     * A unique message id in the form <...>
     *
     * @return string
     */
    public function getId()
    {
        $id = $this->getHeaders()->get('message_id');
        if (empty($id)) { //in some emails ID is empty
            $date = $this->getDate();
            if (is_object($date)) {
                $date = $date->format('Y-m-d H:i:s');
            }
            $id = md5($date.$this->getContent());
        }
        return $id;
    }


    /**
     * Get message sender (from headers)
     *
     * @return EmailAddress
     */
    public function getFrom()
    {
        return $this->getHeaders()->get('from');
    }

    /**
     * Get message recipients (from headers)
     *
     * @return EmailAddress[] Empty array in case message has no To: recipients
     */
    public function getTo()
    {
        return $this->getHeaders()->get('to');
    }

    public function getReplyTo()
    {
        return $this->getHeaders()->get('reply_to');
    }

    /**
     * Get message number (from headers)
     *
     * @return int
     */
    public function getNumber()
    {
        return $this->messageNumber;
    }

    /**
     * Get date (from headers)
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->getHeaders()->get('date');
    }

    /**
     * Get message size (from headers)
     *
     * @return int
     */
    public function getSize()
    {
        return $this->getHeaders()->get('size');
    }

    /**
     * Get raw part content
     *
     * @return string
     */
    public function getContent()
    {
        // Null headers, so subsequent calls to getHeaders() will return
        // updated seen flag
        $this->headers = null;

        return $this->doGetContent($this->keepUnseen);
    }

    /**
     * Get message answered flag value (from headers)
     *
     * @return boolean
     */
    public function isAnswered()
    {
        return $this->getHeaders()->get('answered');
    }

    /**
     * Get message deleted flag value (from headers)
     *
     * @return boolean
     */
    public function isDeleted()
    {
        return $this->getHeaders()->get('deleted');
    }

    /**
     * Get message draft flag value (from headers)
     *
     * @return boolean
     */
    public function isDraft()
    {
        return $this->getHeaders()->get('draft');
    }

    /**
     * Has the message been marked as read?
     *
     * @return boolean
     */
    public function isSeen()
    {
        return 'U' != $this->getHeaders()->get('unseen');
    }

    /**
     * Get message subject (from headers)
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->getHeaders()->get('subject');
    }

    /**
     * Get message headers
     *
     * @return Message\Headers
     */
    public function getHeaders()
    {
        if (null === $this->headers) {
            // imap_header is much faster than imap_fetchheader
            // imap_header returns only a subset of all mail headers,
            // but it does include the message flags.
            $headers = imap_header($this->stream, $this->messageNumber);
            $this->headers = new Mirasvit_Ddeboer_Imap_Message_Headers($headers);
            $this->headers->raw_text = imap_fetchheader($this->stream, $this->messageNumber,FT_PREFETCHTEXT)
                .imap_body($this->stream, $this->messageNumber);
        }

        return $this->headers;
    }

    public function hasHtmlText() {
        if ($this->type == 'text' && $this->subtype == 'HTML') {
            return true;
        }
        $iterator = new RecursiveIteratorIterator($this, RecursiveIteratorIterator::SELF_FIRST);
        foreach ($iterator as $part) {
            if ($part->getSubtype() == 'HTML') {
                return true;
            }
        }
        return false;
    }

    public function hasPlainText() {
        if ($this->type == 'text' && $this->subtype == 'PLAIN') {
            return true;
        }
        $iterator = new RecursiveIteratorIterator($this, RecursiveIteratorIterator::SELF_FIRST);
        foreach ($iterator as $part) {
            if ($part->getSubtype() == 'PLAIN') {
                return true;
            }
        }
        return false;
    }
    /**
     * Get body HTML
     *
     * @return string | null Null if message has no HTML message part
     */
    public function getBodyHtml()
    {
        $iterator = new RecursiveIteratorIterator($this, RecursiveIteratorIterator::SELF_FIRST);
        foreach ($iterator as $part) {
            if ($part->getSubtype() == 'HTML') {
                return $part->getDecodedContent();
            }
        }
    }

    /**
     * Get body text
     *
     * @return string
     */
    public function getBodyText()
    {
        $iterator = new RecursiveIteratorIterator($this, RecursiveIteratorIterator::SELF_FIRST);
        foreach ($iterator as $part) {
            if ($part->getSubtype() == 'PLAIN') {
                return $part->getDecodedContent();
            }
        }

        // If message has no parts, return content of message itself.
        return $this->getDecodedContent();
    }

    /**
     * Get attachments (if any) linked to this e-mail
     *
     * @return Mirasvit_Ddeboer_Imap_Message_Attachment[]
     */
    public function getAttachments()
    {
        if (null === $this->attachments) {
            foreach ($this->getParts() as $part) {
                if ($part instanceof Mirasvit_Ddeboer_Imap_Message_Attachment) {
                    $this->attachments[] = $part;
                }
                if($part->hasChildren()) {
                    foreach($part->getParts() AS $child_part) {
                        if ($child_part instanceof Mirasvit_Ddeboer_Imap_Message_Attachment) {
                            $this->attachments[] = $child_part;
                        }
                    }
                }
            }
        }

        return $this->attachments;
    }

    /**
     * Does this message have attachments?
     *
     * @return int
     */
    public function hasAttachments()
    {
        return count($this->getAttachments()) > 0;
    }

    /**
     * Delete message
     */
    public function delete()
    {
        // 'deleted' header changed, force to reload headers, would be better to set deleted flag to true on header
        $this->headers = null;

        if (!imap_delete($this->stream, $this->messageNumber)) {
            throw new Mirasvit_Ddeboer_Imap_MessageCannotBeDeletedException($this->messageNumber);
        }
    }

    /**
     * Move message to another mailbox
     * @param Mailbox $mailbox
     *
     * @throws MessageMoveException
     * @return Message
     */
    public function move(Mirasvit_Ddeboer_Imap_Mailbox $mailbox)
    {
        if (!imap_mail_move($this->stream, $this->messageNumber, $mailbox->getName())) {
            throw new Mirasvit_Ddeboer_Imap_MessageMoveException($this->messageNumber, $mailbox->getName());
        }
        return $this;
    }

    /**
     * Prevent the message from being marked as seen
     *
     * Defaults to false, so messages that are read will be marked as seen.
     *
     * @param bool $bool
     *
     * @return Message
     */
    public function keepUnseen($bool = true)
    {
        $this->keepUnseen = (bool) $bool;

        return $this;
    }

    /**
     * Load message structure
     */
    protected function loadStructure()
    {
        $structure = imap_fetchstructure($this->stream, $this->messageNumber);
        $this->parseStructure($structure);
    }
}
