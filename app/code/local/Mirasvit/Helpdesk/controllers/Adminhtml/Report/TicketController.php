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


class Mirasvit_Helpdesk_Adminhtml_Report_TicketController extends Mage_Adminhtml_Controller_Action
{
    public function _initAction()
    {
        $this->loadLayout()->_setActiveMenu('helpdesk');
        return $this;
    }

    public function _initReportAction($blocks)
    {
        if (!is_array($blocks)) {
            $blocks = array($blocks);
        }

        $requestData = Mage::helper('adminhtml')->prepareFilterString($this->getRequest()->getParam('filter'));

        $requestData = $this->_filterDates($requestData, array('from', 'to'));
        $requestData['store_ids'] = $this->getRequest()->getParam('store_ids');
        $params = new Varien_Object();

        foreach ($requestData as $key => $value) {
            if (!empty($value)) {
                $params->setData($key, $value);
            }
        }

        foreach ($blocks as $block) {
            if ($block) {
                $block->setPeriodType($params->getData('period_type'));
                $block->setFilterData($params);
            }
        }
        return $this;
    }


    public function indexAction()
    {
        $this->_title($this->__('Tickets Report'));
        $this->_showLastExecutionTime();
        $this->_initAction();
        $gridBlock = $this->getLayout()->getBlock('adminhtml_report_ticket.grid');
        $filterFormBlock = $this->getLayout()->getBlock('grid.filter.form');
        $chartBlock = $this->getLayout()->getBlock('grid.chart')->setGrid($gridBlock);
        $this->_initReportAction(array(
            $gridBlock,
            $filterFormBlock,
            $chartBlock,
        ));
        $this->renderLayout();
    }

    /**
     * Export report grid to CSV format
     */
    public function exportCsvAction()
    {
        $fileName   = 'report_ticket.csv';
        $grid       = $this->getLayout()->createBlock('helpdesk/adminhtml_report_ticket_grid');
        $this->_initReportAction($grid);
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    /**
     * Exportreport grid to Excel XML format
     */
    public function exportExcelAction()
    {
        $fileName   = 'report_ticket.xml';
        $grid       = $this->getLayout()->createBlock('helpdesk/adminhtml_report_ticket_grid');
        $this->_initReportAction($grid);
        $this->_prepareDownloadResponse($fileName, $grid->getExcel($fileName));
    }
    protected function _showLastExecutionTime()
    {
        $flag = Mage::getModel('reports/flag')->setReportFlagCode(Mirasvit_Helpdesk_Model_Resource_Report_Ticket::FLAG_CODE)->loadSelf();
        $updatedAt = ($flag->hasData())
            ? Mage::app()->getLocale()->storeDate(
                0, new Zend_Date($flag->getLastUpdate(), Varien_Date::DATETIME_INTERNAL_FORMAT), true
            )
            : 'undefined';

        $refreshLifetimeLink = $this->getUrl('*/*/refreshLifetime');
        $refreshRecentLink = $this->getUrl('*/*/refreshRecent');

        Mage::getSingleton('adminhtml/session')->addNotice(Mage::helper('adminhtml')->__('Last updated: %s. To refresh statistics, click <a href="%s">here</a>', $updatedAt, $refreshLifetimeLink));
        return $this;
    }

    public function refreshRecentAction()
    {
        try {
            $currentDate = Mage::app()->getLocale()->date();
            $date = $currentDate->subHour(25);
            Mage::getResourceModel('helpdesk/report_ticket')->aggregate($date);
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Recent statistics was successfully updated'));
        } catch (Mage_Core_Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        $this->_redirectReferer('*/*/*');
        return $this;
    }

    public function refreshLifetimeAction()
    {
        try {
            Mage::getResourceModel('helpdesk/report_ticket')->aggregate();
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Lifetime statistics was successfully updated'));
        } catch (Mage_Core_Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        $this->_redirectReferer('*/*/*');
        return $this;
    }
}