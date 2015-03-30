<?php

/**
 * Adminhtml sales order view
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @Date      30th March 2015
 * @author      Manoj Ninave
 */
class Mann_Adminhtml_Block_Sales_Order_View extends Mage_Adminhtml_Block_Sales_Order_View {

    public function __construct() {
        $this->_objectId = 'order_id';
        $this->_controller = 'sales_order';
        $this->_mode = 'view';

        parent::__construct();

        $this->_removeButton('delete');
        $this->_removeButton('reset');
        $this->_removeButton('save');
        $this->setId('sales_order_view');
        $order = $this->getOrder();

        try {
            $orderId = $this->getRequest()->getParam('order_id');
            $order = Mage::getModel('sales/order')->load($orderId);
            $orderData = $order->getData();
            $status = $orderData['status'];
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }


        if ($status != 'complete') {
            if ($this->_isAllowedAction('reorder') && $this->helper('sales/reorder')->isAllowed($order->getStore())) {
				$message = Mage::helper('sales')->__('Are you sure you want to complete this order ?');
                $this->_addButton('order_reorder', array(
                    'label' => Mage::helper('sales')->__('Complete'),
					'onclick'   => "confirmSetLocation('{$message}', '{$this->getCompleteOrderUrl()}')",
                ));
            }
        }
    }

    public function getCompleteOrderUrl() {
        return $this->getUrl('*/sales_order_shipment/complete');
    }

}
