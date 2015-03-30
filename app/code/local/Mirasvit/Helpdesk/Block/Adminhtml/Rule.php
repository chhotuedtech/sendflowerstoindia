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


class Mirasvit_Helpdesk_Block_Adminhtml_Rule extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct ()
    {
        parent::__construct();
        $this->_controller = 'adminhtml_rule';
        $this->_blockGroup = 'helpdesk';
        $this->_headerText = Mage::helper('helpdesk')->__('Workflow Rules');
        $this->_addButtonLabel = Mage::helper('helpdesk')->__('Add New Rule');
    }

    public function getCreateUrl ()
    {
        return $this->getUrl('*/*/add');
    }

    /************************/

}