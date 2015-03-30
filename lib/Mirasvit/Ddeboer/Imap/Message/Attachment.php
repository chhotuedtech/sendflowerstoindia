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



// namespace Mirasvit_Ddeboer\Imap\Message;

/**
 * An e-mail attachment
 */
class Mirasvit_Ddeboer_Imap_Message_Attachment extends Mirasvit_Ddeboer_Imap_Message_Part
{
    protected $filename;

    protected $data;

    protected $contentType;

    protected $size;

    public function __construct($stream, $messageNumber, $partNumber = null, $structure = null)
    {
        parent::__construct($stream, $messageNumber, $partNumber, $structure);
    }

    /**
     * Get attachment filename
     *
     * @return string
     */
    public function getFilename()
    {
        if ($name = $this->parameters->get('filename')) {
            return $name;
        } else {
            return $this->parameters->get('name');
        }
    }

    /**
     * Get attachment file size
     *
     * @return int Number of bytes
     */
    public function getSize()
    {
        return strlen($this->getDecodedContent());
        //return $this->bytes;
    }
}
