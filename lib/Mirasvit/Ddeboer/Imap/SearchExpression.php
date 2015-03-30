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

// use Mirasvit_Ddeboer\Imap\Search\Condition;

/**
 * Defines a search expression that can be used to look up email messages.
 */
class Mirasvit_Ddeboer_Imap_SearchExpression
{
    /**
     * The conditions that together represent the expression.
     *
     * @var array
     */
    protected $conditions = array();

    /**
     * Adds a new condition to the expression.
     *
     * @param Condition $condition The condition to be added.
     * @return SearchExpression
     */
    public function addCondition(Mirasvit_Ddeboer_Imap_Search_Condition $condition)
    {
        $this->conditions[] = $condition;

        return $this;
    }

    /**
     * Converts the expression to a string that can be sent to the IMAP server.
     *
     * @return string
     */
    public function __toString()
    {
        return implode(' ', $this->conditions);
    }
}
