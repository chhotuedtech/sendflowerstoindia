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



/**
 * This file is part of the EmailReplyParser package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */


/**
 * @author William Durand <william.durand1@gmail.com>
 */
function mst_email_reply_filter($fragment) {
    return !$fragment->isHidden();
}

final class Email
{
    /**
     * @var Fragment[]
     */
    private $fragments;

    public function __construct(array $fragments = array())
    {
        $this->fragments = $fragments;
    }

    /**
     * @return Fragment[]
     */
    public function getFragments()
    {
        return $this->fragments;
    }

    /**
     * @return string
     */
    public function getVisibleText()
    {
        $visibleFragments = array_filter($this->fragments, 'mst_email_reply_filter');

        return rtrim(implode("\n", $visibleFragments));
    }
}
