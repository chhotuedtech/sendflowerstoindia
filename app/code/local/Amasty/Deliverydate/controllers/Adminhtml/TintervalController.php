<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Adminhtml_TintervalController extends Amasty_Deliverydate_Controller_Abstract
{
    public function preDispatch()
    {
        parent::preDispatch();
        $this->_tabs      =  true;
        $this->_modelName = 'tinterval';
        $this->_title     = 'Time Intervals';
        $this->_modelId   = 'tinterval_id';
    }
    
    public function setupAction()
    {
        $this->loadLayout();
        
        $this->_setActiveMenu('sales/deliverydate/' . $this->_modelName);
        $this->_title($this->__('Generate'));
        
        $this->_addContent($this->getLayout()->createBlock('amdeliverydate/adminhtml_' . $this->_modelName . '_generate'));
        $this->_addLeft($this->getLayout()->createBlock('amdeliverydate/adminhtml_' . $this->_modelName . '_generate_tabs'));
        
        $this->renderLayout();
    }
    
    public function generateAction()
    {
        $data = $this->getRequest()->getPost();
        
        $stores = '';
        if (!Mage::app()->isSingleStoreMode()) { // prepare stores
            $stores = $data['store_ids'];
            if (is_array($stores)) {
                $stores = ',' . implode(',', $stores) . ',';
            }
        }
        
        $now = date('U');
        // prepare start time
        list($h, $m, $s) = $data['start'];
        $src = date('Y', $now) . '-' . date('m', $now) . '-' . date('d', $now) . ' ' . $h . ':' . $m . ':' . $s;
        $start = strtotime($src);
        // prepare finish time
        list($h, $m, $s) = $data['finish'];
        $src = date('Y', $now) . '-' . date('m', $now) . '-' . date('d', $now) . ' ' . $h . ':' . $m . ':' . $s;
        $finish = strtotime($src);
        if ($finish < $start) {
            $finish = $finish + 86400; // 24 h. * 60 min. * 60 sec. = 86400 sec.
        }
        // prepare sorting
        $modifySorting = false;
        if ($data['sorting_start']) {
            $modifySorting = true;
            $sorting = (int)$data['sorting_start'];
            $sortingStep = (int)$data['sorting_step'];
        }
        
        $step = (int)$data['step'] * 60; // 1 min = 60 sec.
        $format = $data['format'];
        
        $total = 0;
        do {
            $model = Mage::getModel('amdeliverydate/tinterval');
            
            $data = array();
            $data['store_ids'] = $stores;
            $data['from'] = date($format, $start);
            
            $start = $start + $step;
            $data['to'] = date($format, $start);
            
            if ($modifySorting) {
                $data['sorting_order'] = $sorting;
                $sorting = $sorting + $sortingStep;
            } else {
                $data['sorting_order'] = '';
            }
            
            $model->setData($data);
            $model->save();
            $total++;
        } while ($start < $finish);
        
        Mage::getSingleton('adminhtml/session')->addSuccess(
            Mage::helper('amdeliverydate')->__(
                'Total of %d record(s) were successfully created', $total
            )
        );
        $this->_redirect('*/*');
    }
}
