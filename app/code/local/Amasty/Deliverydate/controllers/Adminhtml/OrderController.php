<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Adminhtml_OrderController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('system/amorderattach')
            ->_addBreadcrumb(Mage::helper('amdeliverydate')->__('Sales'), Mage::helper('amdeliverydate')->__('Sales'))
            ->_addBreadcrumb(Mage::helper('amdeliverydate')->__('Edit Delivery Date'), Mage::helper('amdeliverydate')->__('Edit Delivery Date'))
        ;
        return $this;
    }
    
    public function editAction()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        $order   = Mage::getModel('sales/order')->load($orderId);
        
        $deliveryDate = Mage::getModel('amdeliverydate/deliverydate');
        $deliveryDate->load($orderId, 'order_id');
        
        Mage::register('current_order', $order, true);
        Mage::register('current_deliverydate', $deliveryDate, true);
        
        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/edit_deliverydate')) {
            $this->_initAction()
                 ->_addContent($this->getLayout()->createBlock('amdeliverydate/adminhtml_sales_order_view_deliverydate_edit')->setData('action', $this->getUrl('*/*/save', array('order_id' => $orderId))))
                 ->renderLayout();
        } else {
            $this->_redirect('adminhtml/sales_order/view', array('order_id' => $orderId)); // probably, it is not needed (Magento checks it)
        }
    }
    
    public function validateAction()
    {
        $response = new Varien_Object();
        $response->setError(false);
        $this->getResponse()->setBody($response->toJson());
    }
    
    public function saveAction()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        $data    = $this->getRequest()->getPost();
        $data['date'] = $data['date_hidden'];
        
        $deliveryDate = Mage::getModel('amdeliverydate/deliverydate');
        $deliveryDate->load($orderId, 'order_id');
        if ($data) {
            if (!$deliveryDate->getOrderId()) {
                $deliveryDate->setOrderId($orderId);
            }
            foreach ($data as $key => $val) {
                $deliveryDate->setData($key, $val);
            }
            try {
                $deliveryDate->save();
                $this->_getSession()->addSuccess(Mage::helper('amdeliverydate')->__('The delivery date has been updated.'));
                $this->_redirect('adminhtml/sales_order/view', array('order_id' => $orderId));
                return;
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addException(
                    $e,
                    Mage::helper('amdeliverydate')->__('An error occurred while updating the delivery date.')
                );
            }
        } else {
            $this->_redirect('adminhtml/sales_order');
        }
    }
}
