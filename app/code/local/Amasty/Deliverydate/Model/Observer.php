<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Model_Observer
{
    protected $_controllerNames = array('sales_', 'orderspro_');
    protected $_exportActions = array('exportCsv', 'exportExcel');
    protected $_permissibleActions = array('index', 'grid', 'exportCsv', 'exportExcel');
    protected $_orderCollectionClasses = array('Mage_Sales_Model_Resource_Order_Grid_Collection',
                                               'Mage_Sales_Model_Resource_Order_Collection');
    protected $_invoiceCollectionClasses = array('Mage_Sales_Model_Resource_Order_Invoice_Grid_Collection',
                                                 'Mage_Sales_Model_Resource_Order_Invoice_Collection');
    protected $_shipmentCollectionClasses = array('Mage_Sales_Model_Resource_Order_Shipment_Grid_Collection',
                                                  'Mage_Sales_Model_Resource_Order_Shipment_Collection');
                                                 
    public function onSalesQuoteSaveAfter($observer)
    {
        if ($data = Mage::app()->getRequest()->getPost('amdeliverydate')) {
            $data['date_hidden'] = Mage::app()->getRequest()->getPost('delivery_date_hidden');
            $checkout = Mage::getSingleton('checkout/type_onepage')->getCheckout();
            $checkout->setAmastyDeliveryDate($data);
        }
    }
    
    public function onCheckoutTypeOnepageSaveOrderAfter($observer)
    {
        $checkout = Mage::getSingleton('checkout/type_onepage')->getCheckout();
        $data = $checkout->getAmastyDeliveryDate();
        if (is_array($data) && !empty($data)) {
            $data['date'] = $data['date_hidden'];
            if ('' == $data['date']) {
                $data['date'] = '0000-00-00';
            }
            $deliveryDate = Mage::getModel('amdeliverydate/deliverydate');
            $order = $observer->getOrder();
            $deliveryDate->load($order->getIncrementId(), 'increment_id');
            if ($deliveryDate->getId()) {
                return false;
            }
            $deliveryDate->addData($data);
            $deliveryDate->setData('order_id', $order->getId());
            $deliveryDate->setData('increment_id', $order->getIncrementId());
            $deliveryDate->save();
            $checkout->setAmastyDeliveryDate(array());
        }
    }

    public function onSalesOrderSaveAfter($observer) // Backend Order Save
    {
        if ($this->_isControllerName('order')
            && 'save' == Mage::app()->getRequest()->getActionName()
            && !Mage::registry('amdeliverydate_saved')) {
            $data = Mage::app()->getRequest()->getPost('amdeliverydate');
            $data['date_hidden'] = Mage::app()->getRequest()->getPost('delivery_date_hidden');
            if (is_array($data) && !empty($data)) {
                $data['date'] = $data['date_hidden'];
                if ('' == $data['date']) {
                    $data['date'] = '0000-00-00';
                }
                $deliveryDate = Mage::getModel('amdeliverydate/deliverydate');
                $order = $observer->getOrder();
                $deliveryDate->load($order->getIncrementId(), 'increment_id');
                if ($deliveryDate->getId()) {
                    return false;
                }
                $deliveryDate->addData($data);
                $deliveryDate->setData('order_id', $order->getId());
                $deliveryDate->setData('increment_id', $order->getIncrementId());
                $deliveryDate->save();

                Mage::register('amdeliverydate_saved', true);
            }
        }
    }
    
    protected function _prepareBackendHtml($html, $node, $block, $place)
    {
        $blockClass = Mage::getConfig()->getBlockClassName($node);
        $deliveryDate = Mage::helper('amdeliverydate')->whatShow($place);
        if ($blockClass == get_class($block) &&
            !empty($deliveryDate) &&
            false === strpos($html, 'BEGIN `Amasty: Delivery Date`')) {
            $insert = Mage::app()->getLayout()->createBlock('amdeliverydate/adminhtml_sales_order_view_deliverydate');
            $html = preg_replace('@<div class="entry-edit">(\s*)<div class="entry-edit-head">(\s*)(.*?)head-products@', 
                                $insert->toHtml() .'<div class="entry-edit"><div class="entry-edit-head">$3head-products', $html, 1);
        }
        return $html;
    }
    
    protected function _prepareEmailHtml($html, $node, $block, $place, $storeId)
    {
        $blockClass = Mage::getConfig()->getBlockClassName($node);
        if ($blockClass == get_class($block)) {
            $deliveryDate = Mage::helper('amdeliverydate')->whatShow($place, $storeId, 'include');
            if (!empty($deliveryDate)) {
                $insert = Mage::app()->getLayout()->createBlock('amdeliverydate/sales_order_email_deliverydate');
                $insert->setOrder($block->getOrder());
                $insert->setDeliveryDate();
                $html = $insert->toHtml() . $html;
            }
        }
        return $html;
    }

    protected function _prepareBackendCreateOrderHtml($html, $node, $block, $place)
    {
        $blockClass = Mage::getConfig()->getBlockClassName($node);
        $deliveryDate = Mage::helper('amdeliverydate')->whatShow($place);
        if ($blockClass == get_class($block) &&
            !empty($deliveryDate) &&
            false === strpos($html, 'BEGIN `Amasty: Delivery Date`')) {
            $insert = Mage::app()->getLayout()->createBlock('amdeliverydate/adminhtml_sales_order_create_deliverydate');
            $html .= $insert->toHtml();
        }
        return $html;
    }
    
    public function onCoreBlockAbstractToHtmlAfter($observer) 
    {
        $storeId = Mage::app()->getStore()->getId();
        if (Mage::getStoreConfig('amdeliverydate/general/enabled', $storeId)) {
            $block = $observer->getBlock();
            $transport = $observer->getTransport();
            $html = $transport->getHtml();
            // Shipping Method step
            $blockClass = Mage::getConfig()->getBlockClassName('checkout/onepage_shipping_method_available');
            if ($blockClass == get_class($block)) {
                $insert = Mage::app()->getLayout()->createBlock('amdeliverydate/checkout_onepage_shipping_method_deliverydate')->toHtml();
                $html .= $insert;
                // select Shipping Method
                $html .= '<script>if (typeof(amDeliverydateConditionObj) != "undefined"){amDeliverydateConditionObj.check();}</script>';
            }
            // Checkout Progress 
            $blockClass = Mage::getConfig()->getBlockClassName('checkout/onepage_progress');
            if ($blockClass == get_class($block)) {
                $checkout = Mage::getSingleton('checkout/type_onepage')->getCheckout();
                if ($checkout->getStepData('shipping_method', 'complete')
                    && !$checkout->getStepData('payment', 'complete')) {
                    if ($checkout->getAmastyProgressPos()) {
                        $pos = $checkout->getAmastyProgressPos();
                    } else {
                        if (Mage::helper('core')->isModuleEnabled('Amasty_Orderattr')) {
                            $pos = strripos($html, '<dt>');
                        } else {
                            $pos = strripos($html, '</dd>');
                        }
                        $checkout->setAmastyProgressPos($pos);
                    }
                    $insert = Mage::app()->getLayout()->createBlock('amdeliverydate/checkout_onepage_progress_deliverydate')->toHtml();
                    $html = substr_replace($html, $insert, $pos-1, 0);
                }
            }
            // Order View Page
            $blockClass = Mage::getConfig()->getBlockClassName('sales/order_info');
            if ($blockClass == get_class($block)) {
                $pos = strpos($html, 'col-2');
                $pos = strpos($html, 'box-content', $pos);
                $pos = strpos($html, '</div>', $pos);
                $insert = Mage::app()->getLayout()->createBlock('amdeliverydate/sales_order_info_deliverydate');
                $insert->setOrder($block->getOrder());
                $insert->setDeliveryDate();
                $html = substr_replace($html, $insert->toHtml(), $pos+6, 0);
            }
            // Order Confirmation Email
            $html = $this->_prepareEmailHtml($html, 'sales/order_email_items', $block, 'order_email', $storeId);
            // Invoice Email
            $html = $this->_prepareEmailHtml($html, 'sales/order_email_invoice_items', $block, 'invoice_email', $storeId);
            // Shipment Email
            $html = $this->_prepareEmailHtml($html, 'sales/order_email_shipment_items', $block, 'shipment_email', $storeId);
            // Adminhtml Order View Page
            $html = $this->_prepareBackendHtml($html, 'adminhtml/sales_order_view_tab_info', $block, 'order_view');
            // Adminhtml Order Create/Edit Page
            $html = $this->_prepareBackendCreateOrderHtml($html, 'adminhtml/sales_order_create_form_account', $block, 'order_create');
            // Adminhtml Invoice View Page
            $html = $this->_prepareBackendHtml($html, 'adminhtml/sales_order_invoice_view', $block, 'invoice_view');
            // Adminhtml Shipment View Page
            $html = $this->_prepareBackendHtml($html, 'adminhtml/sales_order_shipment_view', $block, 'shipment_view');
            $transport->setHtml($html);
        }
    }
    
    protected function _isInstanceOf($block, $classes)
    {
        $found = false;
        foreach ($classes as $className) {
            if ($block instanceof $className) {
                $found = true;
                break;
            }
        }
        return $found;
    }
    
    protected function _isJoined($from)
    {
        $found = false;
        foreach ($from as $alias => $data) {
            if ('deliverydate' === $alias) {
                $found = true;
            }
        }
        return $found;
    }
    
    protected function _isControllerName($place)
    {
        $found = false;
        foreach ($this->_controllerNames as $controllerName) {
            if (false !== strpos(Mage::app()->getRequest()->getControllerName(), $controllerName . $place)) {
                $found = true;
            }
        }
        return $found;
    }
    
    protected function _prepareCollection($collection, $place = 'order', $column = 'entity_id')
    {
        if (!Mage::getStoreConfig('amdeliverydate/general/enabled'))
            return $collection;
            
        if ($this->_isJoined($collection->getSelect()->getPart('from')))
            return $collection;
            
        if (!$this->_isControllerName($place) ||
            !in_array(Mage::app()->getRequest()->getActionName(), $this->_permissibleActions))
            return $collection;
        
        $deliveryDate = Mage::helper('amdeliverydate')->whatShow($place . '_grid');
        
        if (!empty($deliveryDate)) {
            $isVersion14 = ! Mage::helper('ambase')->isVersionLessThan(1,4);
            $alias = $isVersion14 ? 'main_table' : 'e';
            
            $incrementColumn = 'increment_id';
            if ('order' !== $place) {
                $incrementColumn = 'order_increment_id';
            }
            
            $collection->getSelect()
                       ->joinLeft(
                            array('deliverydate' => Mage::getModel('amdeliverydate/deliverydate')->getResource()->getTable('amdeliverydate/deliverydate')),
                            "($alias.$column = deliverydate.order_id) OR ($alias.$incrementColumn = deliverydate.increment_id)",
                            $deliveryDate
                       );
                       
            $where = $collection->getSelect()->getPart('where');
            foreach ($where as $key => $condition) {
                if (1 == strpos($condition, 'increment_id ')) {
                    $condition = substr_replace($condition, $alias . '.', 1, 0);
                    $where[$key] = $condition;
                }
            }
            $collection->getSelect()->setPart('where', $where);
        }
        
        return $collection;
    }
    
    public function onCoreCollectionAbstractLoadBefore($observer)
    {
        $collection = $observer->getCollection();
        if ($this->_isInstanceOf($collection, $this->_orderCollectionClasses)) {
            $this->_prepareCollection($collection);
        }
        if ($this->_isInstanceOf($collection, $this->_invoiceCollectionClasses)) {
            $this->_prepareCollection($collection, 'invoice', 'order_id');
        }
        if ($this->_isInstanceOf($collection, $this->_shipmentCollectionClasses)) {
            $this->_prepareCollection($collection, 'shipment', 'order_id');
        }
    }
    
    public function dateFilter($collection, $column)
    {
        $filterData = $column->getFilter()->getData();
        if (isset($filterData['value']['from_hidden'])) {
            $from = $filterData['value']['from_hidden'];
            $collection->getSelect()->where('deliverydate.date >= ?', $from);
        }
        if (isset($filterData['value']['to_hidden'])) {
            $to = $filterData['value']['to_hidden'];
            $collection->getSelect()->where('deliverydate.date <= ?', $to);
        }
        return $this;
    }
    
    protected function _prepareColumns(&$grid, $fields, $export = false, $place = 'order', $after = 'grand_total')
    {
        if (!$this->_isControllerName($place) || 
            !in_array(Mage::app()->getRequest()->getActionName(), $this->_permissibleActions))
            return $grid;
            
        $hlp = Mage::helper('amdeliverydate');
        
        if (in_array('date', $fields)) {
            $column = array(
                'header'       => $hlp->__('Delivery Date'),
                'type'         => 'date',
                'align'        => 'center',
                'index'        => 'date',
                'filter_index' => 'deliverydate.date',
                'gmtoffset'    => false,
                'renderer'     => 'amdeliverydate/adminhtml_sales_order_grid_renderer_date',
                'filter'       => 'amdeliverydate/adminhtml_sales_order_grid_filter_date',
                'filter_condition_callback' => array($this, 'dateFilter'),
            );
            $grid->addColumnAfter($column['index'], $column, $after);
            $after = $column['index'];
        }
        
        if (in_array('time', $fields)) {
            $column = array(
                'header'       => $hlp->__('Delivery Time Interval'),
                'index'        => 'time',
                'filter_index' => 'deliverydate.time',
                'filter'       => 'adminhtml/widget_grid_column_filter_text',
                'sortable'     => true,
                'renderer'     => 'amdeliverydate/adminhtml_sales_order_grid_renderer_text' . ($export ? '_export' : ''),
            );
            $grid->addColumnAfter($column['index'], $column, $after);
            $after = $column['index'];
        }
        
        if (in_array('comment', $fields)) {
            $column = array(
                'header'       => $hlp->__('Delivery Comments'),
                'index'        => 'comment',
                'filter_index' => 'deliverydate.comment',
                'filter'       => 'adminhtml/widget_grid_column_filter_text',
                'sortable'     => true,
                'renderer'     => 'amdeliverydate/adminhtml_sales_order_grid_renderer_text' . ($export ? '_export' : ''),
            );
            $grid->addColumnAfter($column['index'], $column, $after);
        }

        return $grid;
    }
    
    public function onCoreLayoutBlockCreateAfter($observer)
    {
        if (Mage::getStoreConfig('amdeliverydate/general/enabled')) {
            $block = $observer->getBlock();
            
            $blockClass = Mage::getConfig()->getBlockClassName('adminhtml/sales_order_grid');
            if ($blockClass == get_class($block)) {
                $deliveryDate = Mage::helper('amdeliverydate')->whatShow();
                if (!empty($deliveryDate)) {
                    $this->_prepareColumns($block, $deliveryDate, in_array(Mage::app()->getRequest()->getActionName(), $this->_exportActions));
                }
            }
            
            $blockClass = Mage::getConfig()->getBlockClassName('adminhtml/sales_invoice_grid');
            if ($blockClass == get_class($block)) {
                $deliveryDate = Mage::helper('amdeliverydate')->whatShow('invoice_grid');
                if (!empty($deliveryDate)) {
                    $this->_prepareColumns($block, $deliveryDate, in_array(Mage::app()->getRequest()->getActionName(), $this->_exportActions), 'invoice');
                }
            }
            
            $blockClass = Mage::getConfig()->getBlockClassName('adminhtml/sales_shipment_grid');
            if ($blockClass == get_class($block)) {
                $deliveryDate = Mage::helper('amdeliverydate')->whatShow('shipment_grid');
                if (!empty($deliveryDate)) {
                    $this->_prepareColumns($block, $deliveryDate, in_array(Mage::app()->getRequest()->getActionName(), $this->_exportActions), 'shipment', 'total_qty');
                }
            }
        }
    }
}