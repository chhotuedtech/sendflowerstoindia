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



class Mirasvit_Helpdesk_Block_Adminhtml_Report_Satisfaction_Grid extends Mirasvit_Helpdesk_Block_Adminhtml_Report_Grid_Abstract
{
    protected $_columnGroupBy = 'created_at';

    public function __construct()
    {
        parent::__construct();
    }

    public function getResourceCollectionName()
    {
        return 'helpdesk/report_satisfaction_collection';
    }

    protected function _prepareColumns()
    {
        if ($this->getFilterData()->getStoreIds()) {
            $this->setStoreIds(explode(',', $this->getFilterData()->getStoreIds()));
        }

        $this->addColumn('created_at', array(
            'header'            => Mage::helper('reports')->__('Period'),
            'index'             => 'created_at',
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
        $this->addColumn('rate3', array(
            'header'    => Mage::helper('helpdesk')->__('Great Votes Number'),
            'index'     => 'rate3',
            'type'      => 'number',
            'sortable'  => false,
            )
        );
        $this->addColumn('rate2', array(
            'header'    => Mage::helper('helpdesk')->__('OK Votes Number'),
            'index'     => 'rate2',
            'type'      => 'number',
            'sortable'  => false,
            )
        );
        $this->addColumn('rate1', array(
            'header'    => Mage::helper('helpdesk')->__('Bad Votes Number'),
            'index'     => 'rate1',
            'type'      => 'number',
            'sortable'  => false,
            )
        );
        $this->addColumn('average_rate', array(
            'header'    => Mage::helper('helpdesk')->__('Satisfaction Score'),
            'index'     => 'average_rate',
            'type'      => 'number',
            'sortable'  => false,
            'renderer'  => 'helpdesk/adminhtml_report_renderer_unit',
            'unit' => '%s%%',
            )
        );
        $this->addColumn('total_cnt', array(
            'header'    => Mage::helper('helpdesk')->__('Total Responses Number'),
            'index'     => 'total_cnt',
            'type'      => 'number',
            'sortable'  => false,
            )
        );
        $this->addColumn('response_rate', array(
            'header'    => Mage::helper('helpdesk')->__('Response Rate'),
            'index'     => 'response_rate',
            'type'      => 'number',
            'sortable'  => false,
            'renderer'  => 'helpdesk/adminhtml_report_renderer_unit',
            'unit' => '%s%%',
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