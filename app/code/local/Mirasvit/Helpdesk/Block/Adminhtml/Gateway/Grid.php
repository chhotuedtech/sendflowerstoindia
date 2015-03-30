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


class Mirasvit_Helpdesk_Block_Adminhtml_Gateway_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('grid');
        $this->setDefaultSort('gateway_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('helpdesk/gateway')
            ->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {

        $this->addColumn('gateway_id', array(
            'header'    => Mage::helper('helpdesk')->__('ID'),
//          'align'     => 'right',
//          'width'     => '50px',
            'index'     => 'gateway_id',
            )
        );
        $this->addColumn('name', array(
            'header'    => Mage::helper('helpdesk')->__('Title'),
//          'align'     => 'right',
//          'width'     => '50px',
            'index'     => 'name',
            )
        );
        $this->addColumn('email', array(
            'header'    => Mage::helper('helpdesk')->__('Email'),
//          'align'     => 'right',
//          'width'     => '50px',
            'index'     => 'email',
            )
        );
        $this->addColumn('is_active', array(
            'header'    => Mage::helper('helpdesk')->__('Active'),
//          'align'     => 'right',
//          'width'     => '50px',
            'index'     => 'is_active',
            'type'      => 'options',
            'options'   => array(
                0 => $this->__('No'),
                1 => $this->__('Yes')
            ),
            )
        );
        $this->addColumn('fetched_at1', array(
            'header'    => Mage::helper('helpdesk')->__('Last Fetched At'),
//          'align'     => 'right',
//          'width'     => '50px',
            'index'     => 'fetched_at',
            'frame_callback'   => array($this, '_lastActivityFormat'),
            )
        );
        $this->addColumn('last_fetch_result', array(
            'header'    => Mage::helper('helpdesk')->__('Last Fetch Result'),
//          'align'     => 'right',
//          'width'     => '50px',
            'index'     => 'last_fetch_result',
            )
        );
        return parent::_prepareColumns();
    }

    public function _lastActivityFormat($renderedValue, $row, $column, $isExport)
    {
        return Mage::helper('helpdesk/string')->nicetime(strtotime($renderedValue));
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('gateway_id');
        $this->getMassactionBlock()->setFormFieldName('gateway_id');
        $statuses = array(
                array('label'=>'', 'value'=>''),
                array('label'=>$this->__('Disabled'), 'value'=> 0),
                array('label'=>$this->__('Enabled'), 'value'=> 1),
        );
        $this->getMassactionBlock()->addItem('is_active', array(
             'label'=> Mage::helper('helpdesk')->__('Change status'),
             'url'  => $this->getUrl('*/*/massChange', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'is_active',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('helpdesk')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        $this->getMassactionBlock()->addItem('delete', array(
            'label'    => Mage::helper('helpdesk')->__('Delete'),
            'url'      => $this->getUrl('*/*/massDelete'),
            'confirm'  => Mage::helper('helpdesk')->__('Are you sure?')
        ));
        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /************************/

}