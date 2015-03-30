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


class Mirasvit_Helpdesk_Block_Adminhtml_Spam_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('grid');
        $this->setDefaultSort('ticket_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('helpdesk/ticket')
            ->getCollection()
            ->joinEmails()
            ->addFieldToFilter('is_spam', true);
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {

        $this->addColumn('code', array(
            'header'    => Mage::helper('helpdesk')->__('Code'),
//          'align'     => 'right',
//          'width'     => '50px',
            'index'     => 'code',
            'filter_index'     => 'main_table.code',
            )
        );
        $this->addColumn('name', array(
            'header'    => Mage::helper('helpdesk')->__('Subject'),
//          'align'     => 'right',
//          'width'     => '50px',
            'index'     => 'name',
            'filter_index'     => 'main_table.name',
            )
        );
        $this->addColumn('customer_name', array(
            'header'    => Mage::helper('helpdesk')->__('Customer Name'),
//          'align'     => 'right',
//          'width'     => '50px',
            'index'     => 'customer_name',
            'filter_index'     => 'main_table.customer_name',
            )
        );
        $this->addColumn('customer_email', array(
            'header'    => Mage::helper('helpdesk')->__('Customer Email'),
//          'align'     => 'right',
//          'width'     => '50px',
            'index'     => 'customer_email',
            'filter_index'     => 'main_table.customer_email',
            )
        );
        $this->addColumn('created_at', array(
            'header'    => Mage::helper('helpdesk')->__('Created At'),
//          'align'     => 'right',
//          'width'     => '50px',
            'index'     => 'created_at',
            'filter_index'     => 'main_table.created_at',
            'type'      => 'datetime',
            )
        );
        $options = Mage::getModel('helpdesk/pattern')->getCollection()->getOptionArray();
        $options[-1] = $this->__('Manually');
        $this->addColumn('pattern_id', array(
            'header'    => Mage::helper('helpdesk')->__('Rejected by'),
//          'align'     => 'right',
//          'width'     => '50px',
            'index'     => 'pattern_id',
            'filter_index'     => 'main_table.pattern_id',
            'type'      => 'options',
            'options'   => $options ,
            )
        );
        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('spam_id');
        $this->getMassactionBlock()->setFormFieldName('spam_id');
        $statuses = array(
                array('label'=>'', 'value'=>''),
                array('label'=>$this->__('Disabled'), 'value'=> 0),
                array('label'=>$this->__('Enabled'), 'value'=> 1),
        );
        $this->getMassactionBlock()->addItem('approve', array(
            'label'    => Mage::helper('helpdesk')->__('Approve'),
            'url'      => $this->getUrl('*/*/massApprove'),
            'confirm'  => Mage::helper('helpdesk')->__('Are you sure?')
        ));
        if (Mage::helper('helpdesk/permission')->isTicketRemoveAllowed()) {
            $this->getMassactionBlock()->addItem('delete', array(
                'label'    => Mage::helper('helpdesk')->__('Delete'),
                'url'      => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('helpdesk')->__('Are you sure?')
            ));
        }
        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /************************/

}