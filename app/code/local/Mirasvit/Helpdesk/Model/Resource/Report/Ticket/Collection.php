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


class Mirasvit_Helpdesk_Model_Resource_Report_Ticket_Collection extends Mage_Sales_Model_Mysql4_Report_Collection_Abstract
{
    protected $_periodFormat;
    protected $_reportType;
    protected $_selectedColumns = array();

    public function __construct()
    {
        parent::_construct();
        $this->setModel('adminhtml/report_item');
        $this->_resource = Mage::getResourceModel('sales/report')->init('helpdesk/ticket_aggregated');
        $this->setConnection($this->getResource()->getReadConnection());
    }

    public function setFilterData($filterData)
    {
        if (isset($filterData['report_type'])) {
            $this->_reportType = $filterData['report_type'];
        } else {
            $this->_reportType = 'all';
        }
        return $this;
    }

    protected function _getSelectedColumns()
    {
        if ('month' == $this->_period) {
            $this->_periodFormat = 'DATE_FORMAT(period, \'%Y-%m\')';
        } elseif ('year' == $this->_period) {
            $this->_periodFormat = 'EXTRACT(YEAR FROM period)';
        } else {
            $this->_periodFormat = 'DATE_FORMAT(period, \'%Y-%m-%d\')';
        }

        // if (!$this->isTotals() && !$this->isSubTotals()) {
            $this->_selectedColumns = array(
                'period' => $this->_periodFormat,
                'total_ticket_cnt' => 'SUM(total_ticket_cnt)',
                'new_ticket_cnt'          => 'SUM(new_ticket_cnt)',
                'solved_ticket_cnt'          => 'SUM(solved_ticket_cnt)',
                'solved_ticket_percent'          => "ROUND(SUM(solved_ticket_cnt)/SUM(new_ticket_cnt)*100, 2)",
                'first_reply_time' => 'ROUND(AVG(first_reply_time)/3600, 1)',
                'first_resolution_time' => 'ROUND(AVG(first_resolution_time)/3600, 1)',
                'full_resolution_time' => 'ROUND(AVG(full_resolution_time)/3600, 1)',
            );
        // }

        // if ($this->isTotals()) {
        //     $this->_selectedColumns = $this->getAggregatedColumns();
        // }

        // if ($this->isSubTotals()) {
        //     $this->_selectedColumns = $this->getAggregatedColumns() + array('period' => $this->_periodFormat);
        // }
        return $this->_selectedColumns;
    }

    protected  function _initSelect()
    {
        $select = $this->getSelect();
        $select->from(array('main_table' => $this->getResource()->getMainTable()) , $this->_getSelectedColumns());

        if (!$this->isTotals() && !$this->isSubTotals()) {
            //Ð¿Ð¾Ð»Ñ Ð¿Ð¾ ÐºÐ¾ÑÐ¾ÑÑÐ¼ Ð±ÑÐ´ÑÑ ÑÐ´ÐµÐ»Ð°Ð½Ñ Ð³ÑÑÐ¿Ð¿Ð¸ÑÐ¾Ð²ÐºÐ¸ Ð¿ÑÐ¸ Ð²ÑÐ²Ð¾Ð´Ðµ Ð¾ÑÑÐµÑÐ°
            $select->group(array(
                $this->_periodFormat,
            ));
            if ($this->_reportType == 'by_user') {
                $select->group('main_table.user_id');
            }
        }
        if ($this->isSubTotals()) {
            $select->group(array(
                $this->_periodFormat,
            ));
        }
        if ($this->_reportType == 'by_user') {
            $select->joinLeft(array('au' => $this->getTable('admin/user')), 'main_table.user_id = au.user_id', array('user_name' => new Zend_Db_Expr("concat(au.firstname, ' ', au.lastname)")));
        }

        // echo $select;die;
        return $this;
    }
}