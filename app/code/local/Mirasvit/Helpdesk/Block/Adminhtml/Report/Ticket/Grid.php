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



class Mirasvit_Helpdesk_Block_Adminhtml_Report_Ticket_Grid extends Mirasvit_Helpdesk_Block_Adminhtml_Report_Grid_Abstract
{
    protected $_columnGroupBy = 'period';

    public function __construct()
    {
        parent::__construct();
    }

    public function getResourceCollectionName()
    {
        return 'helpdesk/report_ticket_collection';
    }

    protected function _prepareColumns()
    {
        if ($this->getFilterData()->getStoreIds()) {
            $this->setStoreIds(explode(',', $this->getFilterData()->getStoreIds()));
        }

        $this->addColumn('period', array(
            'header'            => Mage::helper('reports')->__('Period'),
            'index'             => 'period',
            'width'             => 100,
            'sortable'          => false,
            'period_type'       => $this->getPeriodType(),
            'renderer'          => 'adminhtml/report_sales_grid_column_renderer_date',
        ));

        if ($this->getFilterData()->getReportType() == 'by_user') {
            $this->addColumn('user_name', array(
                'header'    => Mage::helper('helpdesk')->__('User Name'),
                'index'     => 'user_name',
                'type'      => 'text',
                'sortable'  => false,
                )
            );
        }
        $this->addColumn('new_ticket_cnt', array(
            'header'    => Mage::helper('helpdesk')->__('New Tickets Number'),
            'index'     => 'new_ticket_cnt',
            'type'      => 'number',
            'sortable'  => false,
            )
        );
        $this->addColumn('solved_ticket_cnt', array(
            'header'    => Mage::helper('helpdesk')->__('Solved Tickets Number'),
            'index'     => 'solved_ticket_cnt',
            'type'      => 'number',
            'sortable'  => false,
            )
        );
        $this->addColumn('solved_ticket_percent', array(
            'header'    => Mage::helper('helpdesk')->__('Solved Tickets %'),
            'index'     => 'solved_ticket_percent',
            'type'      => 'text',
            'sortable'  => false,
            'renderer'  => 'helpdesk/adminhtml_report_renderer_unit',
            'unit' => '%s%%',
            )
        );
        $this->addColumn('total_ticket_cnt', array(
            'header'    => Mage::helper('helpdesk')->__('Changed Tickets Number'),
            'index'     => 'total_ticket_cnt',
            'type'      => 'number',
            'sortable'  => false,
            )
        );
        $this->addColumn('first_reply_time', array(
            'header'    => Mage::helper('helpdesk')->__('1st Reply Time'),
            'index'     => 'first_reply_time',
            'type'      => 'number',
            'sortable'  => false,
            'renderer'  => 'helpdesk/adminhtml_report_renderer_unit',
            'unit' => '%s h.',
            )
        );
        $this->addColumn('first_resolution_time', array(
            'header'    => Mage::helper('helpdesk')->__('1st Resolution Time'),
            'index'     => 'first_resolution_time',
            'type'      => 'number',
            'sortable'  => false,
            'renderer'  => 'helpdesk/adminhtml_report_renderer_unit',
            'unit' => '%s h.',
            )
        );
        $this->addColumn('full_resolution_time', array(
            'header'    => Mage::helper('helpdesk')->__('Full Resolution Time'),
            'index'     => 'full_resolution_time',
            'type'      => 'number',
            'sortable'  => false,
            'renderer'  => 'helpdesk/adminhtml_report_renderer_unit',
            'unit' => '%s h.',
            )
        );

        $this->addExportType('*/*/exportCsv', Mage::helper('adminhtml')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('adminhtml')->__('Excel XML'));
        return parent::_prepareColumns();
    }

    protected function getFilterData()
    {
        $date = Mage::getSingleton('core/date');
        $data = parent::getFilterData();

        if (!$data->hasData('from')) {
            $data->setData('from', $date->gmtDate(null, $date->gmtTimestamp() - 30 * 24 * 60 * 60));
        }

        if (!$data->hasData('to')) {
            $data->setData('to', $date->gmtDate(null, $date->gmtTimestamp()));
        }

        if (!$data->hasData('period_type')) {
            $data->setData('period_type', 'day');
        }

        if (!$data->hasData('report_type')) {
            $data->setData('report_type', 'all');
        }

        return $data;
    }
}