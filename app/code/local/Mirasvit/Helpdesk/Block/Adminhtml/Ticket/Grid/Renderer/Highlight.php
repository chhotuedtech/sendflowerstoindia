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


class Mirasvit_Helpdesk_Block_Adminhtml_Ticket_Grid_Renderer_Highlight
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Options
{
    protected $_text  = null;
    protected $_value = null;
    protected $_code = null;

    public function render(Varien_Object $row)
    {
        $this->_value =  $this->_getValue($row);
        $this->_text  = parent::render($row);
        if ($this->getColumn()->getIndex() == 'color') { //we are in the list of statuses or priorities
            $color = $this->_value;
        } else {
            $code = str_replace('_id', '', $this->getColumn()->getIndex()).'_color';
            $color = '';
            if (isset($row[$code])) {
                $color = $row[$code];
            }
        }
        return "<span class='$color'>$this->_text</span>";

    }

    public function renderCss()
    {
        return $this->getColumn()->getIndex();
    }
}