<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Helper_Data extends Mage_Core_Helper_Abstract
{
    private static $_dictionary = array(
        'dd'   => '%d',
        'd'    => '%j',
        'MM'   => '%m',
        'M'    => '%n',
        'yyyy' => '%Y',
        'yy'   => '%y',
    );
    
    public function getYears($year = 0, $isGrid = false, $model = 'holidays', $column = 'from_year')
    {
        $years = array(0 => $this->__('Each year'));
        if ($isGrid) { // dropdown for grid
            $collection = Mage::getModel('amdeliverydate/' . $model)->getCollection();
            foreach ($collection as $item) {
                if ('holidays' == $model) {
                    if ($item->getYear() && !in_array($item->getYear(), $years)) {
                        $years[$item->getYear()] = $item->getYear();
                    }
                } else {
                    if ($item->getData($column) && !in_array($item->getData($column), $years)) {
                        $years[$item->getData($column)] = $item->getData($column);
                    }
                }
            }
        } else { // dropdown for edit page
            $curYear = date('Y');
            if ($year && $year < $curYear) {
                $years[$year] = $year;
            }
            for ($i = 0; $i <= 4; $i++) {
                $years[$curYear + $i] = $curYear + $i;
            }
        }
        return $years;
    }
    
    public function getMonths()
    {
        return array(
            0  => $this->__('Each month'),
            1  => $this->__('January'),
            2  => $this->__('February'),
            3  => $this->__('March'),
            4  => $this->__('April'),
            5  => $this->__('May'),
            6  => $this->__('June'),
            7  => $this->__('July'),
            8  => $this->__('August'),
            9  => $this->__('September'),
            10 => $this->__('October'),
            11 => $this->__('November'),
            12 => $this->__('December')
        );
    }
    
    public function getDays()
    {
        $days = array();
        for ($i = 1; $i <= 31; $i++) {
            $days[$i] = $i;
        }
        return $days;
    }
    
    public function getAlignForColumn($pointer)
    {
        $align = '';
        switch ($pointer) {
            case '0':
                $align = 'right';
                break;
            case '1':
                $align = 'center';
                break;
            case '2':
                $align = 'left';
                break;
        }
        return $align;
    }
    
    public function whatShow($place = 'order_grid', $storeId = 0, $include = 'show')
    {
        $fields = array();
        
        if (in_array($place, explode(',', Mage::getStoreConfig('amdeliverydate/date_field/' . $include, $storeId)))) {
            $fields[] = 'date';
        }
        if (in_array($place, explode(',', Mage::getStoreConfig('amdeliverydate/time_field/' . $include, $storeId)))) {
            $fields[] = 'time';
        }
        if (in_array($place, explode(',', Mage::getStoreConfig('amdeliverydate/comment_field/' . $include, $storeId)))) {
            $fields[] = 'comment';
        }
        return $fields;
    }
    
    public function getTIntervals($currentStore = 0)
    {
        $tIntervals = array('' => '');
        
        $collection = Mage::getModel('amdeliverydate/tinterval')->getCollection();
        $collection->getSelect()->order('sorting_order');

        foreach ($collection as $tInterval) {
            $storeIds = trim($tInterval->getData('store_ids'), ',');
            $storeIds = explode(',', $storeIds);
            if (!in_array($currentStore, $storeIds) && !in_array(0, $storeIds)) {
                continue;
            }
            
            $value = $tInterval->getData('from') . ' - ' . $tInterval->getData('to');
            $tIntervals[$value] = $value;
        }
        return $tIntervals;
    }
    
    public function getPhpFormat($storeId = 0)
    {
        return str_replace('%', '', $this->_convert(Mage::getStoreConfig('amdeliverydate/date_field/format', $storeId)));
    }
    
    private function _convert($value)
    {
        foreach (self::$_dictionary as $search => $replace) {
            $value = preg_replace('/(^|[^%])' . $search . '/', '$1' . $replace, $value);
        }
        return $value;
    }
    
    public function checkDefault($default, $currentStore, $now)
    {
        $default = date('Y-m-d', strtotime($default));
        list($y, $m, $d) = explode('-', $default);
        
        // min and max day intervals
        if (Mage::getStoreConfig('amdeliverydate/date_field/default', $currentStore) < Mage::getStoreConfig('amdeliverydate/general/min_days', $currentStore)
        || (Mage::getStoreConfig('amdeliverydate/general/max_days', $currentStore)
            && Mage::getStoreConfig('amdeliverydate/date_field/default', $currentStore) > Mage::getStoreConfig('amdeliverydate/general/max_days', $currentStore))) {
            return false;
        }
        // same day
        if (Mage::getStoreConfig('amdeliverydate/general/enabled_same_day', $currentStore)
        && 0 == Mage::getStoreConfig('amdeliverydate/date_field/default', $currentStore)) {
            list($h, $m, $s) = explode(',', Mage::getStoreConfig('amdeliverydate/general/same_day', $currentStore));
            $disableAfterSrc = date('Y', $now) . '-' . date('m', $now) . '-' . date('d', $now) . ' ' . $h . ':' . $m . ':' . $s;
            $disableAfter = strtotime($disableAfterSrc);
            if ($disableAfter <= $now) {
                return false;
            }
        }
        // next day
        if (Mage::getStoreConfig('amdeliverydate/general/enabled_next_day', $currentStore)
        && 1 == Mage::getStoreConfig('amdeliverydate/date_field/default', $currentStore)) {
            list($h, $m, $s) = explode(',', Mage::getStoreConfig('amdeliverydate/general/next_day', $currentStore));
            $disableAfterSrc = date('Y', $now) . '-' . date('m', $now) . '-' . date('d', $now) . ' ' . $h . ':' . $m . ':' . $s;
            $disableAfter = strtotime($disableAfterSrc);
            if ($disableAfter <= $now) {
                return false;
            }
        }
        // days of week
        $daysOfWeek = explode(',', Mage::getStoreConfig('amdeliverydate/general/disabled_days', $currentStore));
        $dayOfWeek = date('N', strtotime($default)) + 1;
        if (8 == $dayOfWeek) {
            $dayOfWeek = 1;
        }
        if (in_array($dayOfWeek, $daysOfWeek)) {
            return false;
        }
        // date intervals
        $collection = Mage::getModel('amdeliverydate/dinterval')->getCollection();
        if (0 < $collection->getSize()) {
            foreach ($collection as $interval) {
                $storeIds = trim($interval->getStoreIds(), ',');
                $storeIds = explode(',', $storeIds);
                if (!in_array($currentStore, $storeIds) && !in_array(0, $storeIds)) {
                    continue;
                }
                // from date
                if (0 == $interval->getFromYear()) {
                    $fromY = date('Y', strtotime($default));
                } else {
                    $fromY = $interval->getFromYear();
                }
                if (0 == $interval->getFromMonth()) {
                    $fromM = date('m', strtotime($default));
                } else {
                    $fromM = $interval->getFromMonth();
                }
                $fromDate = strtotime($fromY . '-' . $fromM . '-' . $interval->getFromDay());
                // to date
                if (0 == $interval->getToYear()) {
                    $toY = date('Y', strtotime($default));
                } else {
                    $toY = $interval->getToYear();
                }
                if (0 == $interval->getToMonth()) {
                    $toM = date('m', strtotime($default));
                } else {
                    $toM = $interval->getToMonth();
                }
                $toDate = strtotime($toY . '-' . $toM . '-' . $interval->getToDay());
                if ($fromDate <= strtotime($default)
                && $toDate >= strtotime($default)) {
                    return false;
                }
            }
        }
        // holidays
        $holidays = Mage::getModel('amdeliverydate/holidays')->getCollection();
        if (0 < $holidays->getSize()) {
            foreach ($holidays as $holiday) {
                $storeIds = trim($holiday->getStoreIds(), ',');
                $storeIds = explode(',', $storeIds);
                if (!in_array($currentStore, $storeIds) && !in_array(0, $storeIds)) {
                    continue;
                }
                if ((($y == $holiday->getYear()) && ($m == $holiday->getMonth()) && ($d == $holiday->getDay())) // fixed date
                || ((0 == $holiday->getYear()) && ($m == $holiday->getMonth()) && ($d == $holiday->getDay())) // each year
                || (($y == $holiday->getYear()) && (0 == $holiday->getMonth()) && ($d == $holiday->getDay())) // each month
                || ((0 == $holiday->getYear()) && (0 == $holiday->getMonth()) && ($d == $holiday->getDay()))) { // each year and each month
                    return false;
                }
            }
        }
        return true;
    }
}