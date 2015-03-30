<?php
/**
 * Adminhtml sales order shipment controller
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Manoj Ninave
 * @Date      30th March 2015
 */
require_once 'Mage/Adminhtml/controllers/Sales/Order/ShipmentController.php';
class Mann_Adminhtml_Sales_Order_ShipmentController extends Mage_Adminhtml_Sales_Order_ShipmentController
{
    
    /**
     * Change status to complete
     */
    public function completeAction()
    {
          
        $orderId = $this->getRequest()->getParam('order_id');
        $orderOBJ = Mage::getModel('sales/order')->load($orderId);
        $orderOBJ->setStatus('complete');
        $orderOBJ->save();
        /**
         * Clear old values for shipment qty's
         */
        $this->_getSession()->addSuccess($this->__('Status has been changed to complete.'));
        $this->_redirect('*/sales_order/view', array('order_id'=>$this->getRequest()->getParam('order_id')));
    }

    
}
