<?php
class Magentomasters_Supplier_ShippingController extends Magentomasters_Supplier_Controller_Supplier {

    public function indexAction() {
        $session = Mage::getSingleton('core/session');
        $supplierId = $session->getData('supplierId');
        $orderId = $this->getRequest()->getParam('orderid');
        if( $supplierId && $supplierId != "logout") {
            $this->loadLayout();
            $this->renderLayout();
        } else {
            $redirectPath = Mage::getUrl() . "supplier/";
            $this->_redirectUrl( $redirectPath );
        }
    }
	
	public function gridAction() {
        $session = Mage::getSingleton('core/session');
        $supplierId = $session->getData('supplierId');
        $orderId = $this->getRequest()->getParam('orderid');
        if( $supplierId && $supplierId != "logout") {
            $this->loadLayout();
            $this->renderLayout();
        } else {
            $redirectPath = Mage::getUrl() . "supplier/";
            $this->_redirectUrl( $redirectPath );
        }
    }
	
	public function viewAction() {
        $session = Mage::getSingleton('core/session');
        $supplierId = $session->getData('supplierId');
        $orderId = Mage::getModel('supplier/order')->getOrderIdByShippingId($this->getRequest()->getParam('shipping_id'));
      	if($supplierId && $supplierId != "logout" && $orderId) {
        	$check = Mage::getModel('supplier/order')->checkOrderAuth($supplierId,$orderId['order_id']); 
            if(!$check){
            	$this->_redirect("supplier/order");
				return;
			} else {
            	$this->loadLayout();
            	$this->renderLayout();
			}
        } else {
            $this->_redirect("supplier/order");
			return;
        }
    } 
	
	public function shipAction() {
        $session = Mage::getSingleton('core/session');
        $supplierId = $session->getData('supplierId');
        $orderId = $this->getRequest()->getParam('orderid');
        if( $supplierId && $supplierId != "logout") {
            $this->loadLayout();
            $this->renderLayout();
        } else {
            $redirectPath = Mage::getUrl() . "supplier/";
            $this->_redirectUrl( $redirectPath );
        }
    }
	
	public function addshipmentAction(){
			
		$session = Mage::getSingleton('core/session');
        $supplierId = $session->getData('supplierId');
        $orderId = $this->getRequest()->getParam('orderid');
        
        if( $supplierId && $supplierId != "logout") {
	        	
	        $post = $this->getRequest()->getPost(); 
	        
	        if ($post) {
	            	
	            $orderId = $post['order_id'];
	        	$itemsQty = $post['ship_qty'];
				$tracking = $post['tracking'];
	        	
	        	try {
	        		$this->completeAndShip($orderId,$itemsQty,$tracking);
					/* Added for change Order status as Shipped */
					$order = Mage::getModel('sales/order')->load($orderId);
					$total_ordered_items = $order->getData('total_qty_ordered');
					$num_of_item_shipped = 0;
					foreach ($order->getAllVisibleItems() as $item){
					   // $item->getQtyOrdered() // Number of item ordered
					   $num_of_shipped_items += $item->getQtyShipped();  
					   
					}

					if($num_of_shipped_items == $total_ordered_items){
						// set status to complete
						echo "in if ".$num_of_shipped_items;
						$state = 'order_shipped';
						$status = 'order_shipped';
						$comment = "Order has been shipped by Supplier !";
						$isCustomerNotified = false; //whether customer to be notified
						$order->setState($state, $status, $comment, $isCustomerNotified);    
						$order->save();
					}
					else{
						echo "in else ".$num_of_shipped_items;
						// Partial 
						$state = 'partial_shipped';
						$status = 'partial_shipped';
						$comment = "Order has been Partial shipped by Supplier !";
						$isCustomerNotified = false; //whether customer to be notified
						$order->setState($state, $status, $comment, $isCustomerNotified);    
						$order->save();
					}
					
					
					/* End */
					
	        	} catch (Exception $e) {
					Mage::getSingleton('core/session')->addError($e->getMessage());
					$this->_redirectUrl( Mage::getUrl() . 'supplier/order');
	        	}
				
	        }      
	        
	        Mage::getSingleton('core/session')->addSuccess("Succesfully Shipped");
	        $this->_redirectUrl(Mage::getUrl().'supplier/order');
		
		} else {
            $redirectPath = Mage::getUrl() . "supplier/";
            $this->_redirectUrl( $redirectPath );
        }	
    }
	
	public function completeAndShip($orderId,$itemsQty,$tracking){
        $email = true; // <-- Must be users email address  $order->getCustomerEmail()
        $carrier = 'custom';
        $includeComment = false;
        $comment = "The order is shipped by the supplier";
        $order = Mage::getModel('sales/order')->load($orderId);
        $convertor = Mage::getModel('sales/convert_order');
        $shipment = $convertor->toShipment($order);

        foreach ($order->getAllItems() as $k=>$orderItem) {
            	
			if (!$orderItem->getQtyToShip()) {
        		continue;
        	}
			if ($orderItem->getIsVirtual()) {
           		continue;
        	}
			 	
			$item = $convertor->itemToShipmentItem($orderItem);	
			
			//$productId = $orderItem->getProductId();
		
			if($itemsQty[$orderItem->getItemId()]) {
                $item->setQty($itemsQty[$orderItem->getItemId()]);
                $shipment->addItem($item);
            }			
        }
        
        $carrierTitle = NULL;

        if ($carrier == 'custom') {
            $carrierTitle = 'Playtimes';
        }
        foreach ($tracking as $data) {
            $track = Mage::getModel('sales/order_shipment_track')->addData($data);
            $shipment->addTrack($track);
        }

        $shipment->register();
        $shipment->addComment($comment, $email && $includeComment);
        $shipment->setEmailSent(true);
        $shipment->getOrder()->setIsInProcess(true);

        $transactionSave = Mage::getModel('core/resource_transaction')
            ->addObject($shipment)
            ->addObject($shipment->getOrder())
            ->save();

        $shipment->sendEmail($email, ($includeComment ? $comment : ''));
		$order->setStatus('Complete');
		$order->addStatusToHistory($order->getStatus(), 'Order Completed because every item have been shipped', false);
        $shipment->save();
	
    }

	public function addTrackAction()
    {
 		$session = Mage::getSingleton('core/session');
        $supplierId = $session->getData('supplierId');
        $orderId = $this->getRequest()->getParam('orderid');
        if( $supplierId && $supplierId != "logout") {
		    $post = $this->getRequest()->getPost();
	        
	        if ($post) {	
				
				$orderId = $this->getRequest()->getPost('order_id');
				$shippingId = $this->getRequest()->getPost('shipping_id');
		        $carrier = $this->getRequest()->getPost('carrier');
		        $number  = $this->getRequest()->getPost('number');
		        $title  = $this->getRequest()->getPost('title');
				
				if($shippingId && $carrier && $number && $title){
				
			        try {
			        	
			            $shipment = Mage::getModel('sales/order_shipment')->load($shippingId);
			            
			            if ($shipment) {
			                	
			                $track = Mage::getModel('sales/order_shipment_track')->setNumber($number)->setCarrierCode($carrier)->setTitle($title);
			                
			                $shipment->addTrack($track)->save();
							
							$this->_redirectUrl(Mage::getUrl().'supplier/shipping/grid/order_id/' . $orderId);
			
			            } else {
							//$this->_redirectUrl(Mage::getUrl().'supplier/order');
			            }
			        } catch (Mage_Core_Exception $e) {
			            
			        } 
		        }
			}
		} else {
			$redirectPath = Mage::getUrl() . "supplier/";
			$this->_redirectUrl( $redirectPath );
        }	
    }

	public function emailAction(){
		$session = Mage::getSingleton('core/session');
        $supplierId = $session->getData('supplierId');
        $orderId = $this->getRequest()->getParam('orderid');
        if( $supplierId && $supplierId != "logout") {
			$shipmentId = $this->getRequest()->getParam('shipping_id');		
			if($shipmentId){
	        	try{
				   $shipment = Mage::getModel('sales/order_shipment')->load($shipmentId); 
				} catch (Exception $e) {
			       $this->_getSession()->addError($this->__('Cannot send shipment information.'));
			    }
	        	try {
		            if ($shipment) {
		                $shipment->sendEmail(true)
		                    ->setEmailSent(true)
		                    ->save();
		                $historyItem = Mage::getResourceModel('sales/order_status_history_collection')
		                   ->getUnnotifiedForInstance($shipment, Mage_Sales_Model_Order_Shipment::HISTORY_ENTITY_NAME);
		                if ($historyItem) {
		                    $historyItem->setIsCustomerNotified(1);
		                    $historyItem->save();
		                }
		                Mage::getSingleton('core/session')->addSuccess($this->__('The shipment has been sent.'));
		            }
		       	} catch (Mage_Core_Exception $e) {
		            Mage::getSingleton('core/session')->addError($e->getMessage());
		        } catch (Exception $e) {
		            Mage::getSingleton('core/session')->addError($this->__('Cannot send shipment information.'));
		        }
	        } else{
				Mage::getSingleton('core/session')->addError($this->__('Cannot send shipment information.'));
	        }
			$this->_redirect('*/*/view', array(
		            'shipping_id' => $this->getRequest()->getParam('shipping_id')
		    ));
		} else {
			$redirectPath = Mage::getUrl() . "supplier/";
			$this->_redirectUrl( $redirectPath );
        }		
	}
	
 
}