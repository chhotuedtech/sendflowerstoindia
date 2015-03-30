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


class Mirasvit_Helpdesk_Model_Resource_Report_Ticket extends Mage_Core_Model_Mysql4_Abstract
{
    const FLAG_CODE = 'report_ticket';

    protected function _construct()
    {
        $this->_init('helpdesk/ticket_aggregated', 'ticket_aggregated_id');
        $this->_setResource(array('read', 'write'));
    }


    public function aggregate($from = null, $to = null)
    {
        if (!is_null($from)) {
            $from = $this->formatDate($from);
        }
        if (!is_null($to)) {
            $from = $this->formatDate($to);
        }

        $this->_aggregateTicket($from, $to);
        $this->_refreshFlag();
        return $this;
    }

    protected function _refreshFlag()
    {
        $flag = Mage::getModel('reports/flag');
        $flag->setReportFlagCode(self::FLAG_CODE)
            ->unsetData()
            ->loadSelf()
            ->setLastUpdate($this->formatDate(time()));
        $flag->save();
    }

    protected function _selectAndInsert($from, $to, $isForAllStores)
    {
        $isForAllStores = true;
        $writeAdapter = $this->_getWriteAdapter();
        $ticketTableName = $this->getTable('helpdesk/ticket');
        $messageTableName = $this->getTable('helpdesk/message');
        $tableName = $this->getTable('helpdesk/ticket_aggregated');
        $timeOffset = (int)((Mage::getModel('core/date')->timestamp(Mage::getModel('core/date')->date()) - Mage::getModel('core/date')->timestamp())/3600);

        if ($timeOffset > 0) {
            $timeOffset = '+'.$timeOffset;
        }
        $columns = array(
            'period'                => "DATE(CONVERT_TZ(created_at, '+00:00', '{$timeOffset}:00') )",
            // 'period'                => "DATE(created_at)",
            'store_id'              => $isForAllStores? new Zend_Db_Expr('0'): 'store_id',
            'user_id'              => 'user_id',
            //'total_ticket_cnt'          => "(select count(*) from {$ticketTableName} t where t.updated_at < DATE_ADD(period, INTERVAL 1 DAY) AND t.updated_at >= period AND (t.user_id=main_table.user_id) AND (t.store_id=main_table.store_id))",
            'total_ticket_cnt'          => "(select count(*) from {$ticketTableName} t where (t.is_spam = '') AND EXISTS (SELECT * FROM {$messageTableName} m WHERE m.ticket_id=t.ticket_id AND DATE(CONVERT_TZ(m.created_at, '+00:00', '{$timeOffset}:00')) = period  ) AND (t.user_id=main_table.user_id))",
            // 'new_ticket_cnt'          => 'sum(if(status_id = 1, 1, 0))',
            'new_ticket_cnt'          => "(select count(*) from {$ticketTableName} t where (t.is_spam = '') AND DATE(CONVERT_TZ(t.created_at, '+00:00', '{$timeOffset}:00')) = period AND (t.user_id=main_table.user_id))",
            // 'solved_ticket_cnt'          => 'sum(if(status_id = 3, 1, 0))',
            'solved_ticket_cnt'          => "(select count(*) from {$ticketTableName} t where (t.is_spam = '') AND t.status_id=3 and DATE(CONVERT_TZ(t.created_at, '+00:00', '{$timeOffset}:00')) = period  AND (t.user_id=main_table.user_id))",
            'first_reply_time'          => 'AVG(if (first_reply_at IS NOT NULL, UNIX_TIMESTAMP(first_reply_at) - UNIX_TIMESTAMP(created_at), NULL))',
            'first_resolution_time'          => 'AVG(if (first_reply_at IS NOT NULL, UNIX_TIMESTAMP(first_solved_at) - UNIX_TIMESTAMP(created_at), NULL))',
            'full_resolution_time'          => 'AVG(if (status_id = 3 AND first_reply_at IS NOT NULL, UNIX_TIMESTAMP(last_reply_at) - UNIX_TIMESTAMP(created_at), NULL))',
        );
        $select = $writeAdapter->select()
            ->from(array('main_table' => $this->getTable('helpdesk/ticket')), $columns);

        if (!is_null($from) || !is_null($to)) {
            $subQuery = $writeAdapter->select();
            $subQuery->from(array('so'=>$this->getTable('helpdesk/ticket')), array('DISTINCT DATE(so.created_at)'))
                ->where($where);
            $select->where("DATE(created_at) IN(?)", new Zend_Db_Expr($subQuery));
        }
        $select->group(array(
            // "DATE(created_at)",
            "DATE(CONVERT_TZ(created_at, '+00:00','{$timeOffset}:00') )",
            'user_id',
        ));
        if (!$isForAllStores) {
            $select->group('store_id');
        }

        // echo $select;die;
        $sql = "INSERT INTO `{$tableName}` (" . implode(',', array_keys($columns)) . ") {$select}";
        // echo $sql;die;
        $writeAdapter->query($sql);
    }

    protected function _aggregateTicket($from, $to)
    {
        $writeAdapter = $this->_getWriteAdapter();
        try {
            $tableName = $this->getTable('helpdesk/ticket_aggregated');
            $writeAdapter->beginTransaction();

            if (is_null($from) && is_null($to)) {
                $writeAdapter->delete($tableName);
            } else {
                $where = (!is_null($from)) ? "so.updated_at >= '{$from}'" : '';
                if (!is_null($to)) {
                    $where .= (!empty($where)) ? " AND so.updated_at <= '{$to}'" : "so.updated_at <= '{$to}'";
                }

                $subQuery = $writeAdapter->select();
                $subQuery->from(array('so'=>$this->getTable('helpdesk/ticket')), array('DISTINCT DATE(so.created_at)'))
                    ->where($where);

                $deleteCondition = 'DATE(period) IN (' . new Zend_Db_Expr($subQuery) . ')';
                $writeAdapter->delete($tableName, $deleteCondition);
            }
            /// SELECT AND INSERT
            // $this->_selectAndInsert($from, $to, false);
            $this->_selectAndInsert($from, $to, true);

        } catch (Exception $e) {
            $writeAdapter->rollBack();
            throw $e;
        }
        $writeAdapter->commit();
        return $this;
    }
}