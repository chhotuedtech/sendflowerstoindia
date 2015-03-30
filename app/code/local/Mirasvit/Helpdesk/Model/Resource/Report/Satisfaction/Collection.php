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


class Mirasvit_Helpdesk_Model_Resource_Report_Satisfaction_Collection extends Mage_Sales_Model_Mysql4_Report_Collection_Abstract
{
    protected $_periodFormat;
    protected $_reportType;
    protected $_selectedColumns = array();

    public function __construct()
    {
        parent::_construct();
        $this->setModel('adminhtml/report_item');
        $this->_resource = Mage::getResourceModel('sales/report')->init('helpdesk/satisfaction');
        $this->setConnection($this->getResource()->getReadConnection());
    }

    protected function _applyDateRangeFilter()
    {
        if (!is_null($this->_from)) {
            $this->getSelect()->where($this->_periodFormat.' >= ?', $this->_from);
        }
        if (!is_null($this->_to)) {
            $this->getSelect()->where($this->_periodFormat.' <= ?', $this->_to);
        }
        return $this;
    }

    public function _applyStoresFilter()
    {
        return $this;
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
            $this->_periodFormat = 'DATE_FORMAT(created_at, \'%Y-%m\')';
        } elseif ('year' == $this->_period) {
            $this->_periodFormat = 'EXTRACT(YEAR FROM created_at)';
        } else {
            $this->_periodFormat = 'DATE_FORMAT(created_at, \'%Y-%m-%d\')';
        }

        // if (!$this->isTotals() && !$this->isSubTotals()) {
            $this->_selectedColumns = array(
                'created_at' => $this->_periodFormat,
                'rate1' => 'sum(if (rate = 1, 1, 0))',
                'rate2' => 'sum(if (rate = 2, 1, 0))',
                'rate3' => 'sum(if (rate = 3, 1, 0))',
                'average_rate' => 'ROUND(avg(if (rate = 1, 0, if (rate = 2, 50, 100))), 1)',
                'total_cnt' => 'count(*)',
                'response_rate' => "ROUND(count(*)/(SELECT COUNT(*) FROM {$this->getTable('helpdesk/message')} WHERE user_id = main_table.user_id)*100, 1)",
            );

            if ($this->_reportType == 'by_user') {
                $this->_selectedColumns['user_name'] = "concat(au.firstname, ' ', au.lastname)";
            }
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
        // echo $this->getSelect();die;
        return $this;
    }
}