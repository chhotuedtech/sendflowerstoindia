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



// namespace Mirasvit_Ddeboer\Imap\Search\Text;

// use Mirasvit_Ddeboer\Imap\Search\Text;

/**
 * Represents a body text contains condition. Messages must have a body
 * containing the specified text in order to match the condition.
 */
class  Mirasvit_Ddeboer_Imap_Search_Text_Body extends  Mirasvit_Ddeboer_Imap_Search_Text
{
    /**
     * Returns the keyword that the condition represents.
     *
     * @return string
     */
    public function getKeyword()
    {
        return 'BODY';
    }
}
