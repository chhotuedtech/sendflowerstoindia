<?php class Magentomasters_Supplier_Model_Processor{
		
	private function _getObserver(){
		return Mage::getModel('supplier/observer');
	}	
	
	public function dropship($order,$trigger,$supplierList){
			$this->_getObserver()->logging("Dropship!");
			$this->_getObserver()->logging($trigger);
			$settings = $this->_getObserver()->settings($order->getStoreId());
			if($trigger!="cron" && $trigger!="form"){
				$supplierList = $this->_getSupplierListByOrder($order,$trigger);
			}
			if(!empty($supplierList)){ 
				$orderId =  $order->getEntity_id();
				$dropshipid = $this->_getObserver()->getNextId();
				$output = false;
				
				$this->_getObserver()->logging('Ordernumber: ' . $orderId);
		
		        $supplierSettingsArr = $this->_getObserver()->settings($order->getStoreId());
	
		        foreach ($supplierList as $supplierId => $supplier) {
					
					$supplierRes = Mage::getModel('supplier/supplier')->load($supplierId)->getData();
					$items = $supplier['cartItems'];	
		
					// Send email 
					if ($supplierRes['email_enabled'] == 1) { 
						$email = Mage::getModel('supplier/output')->getEmail($orderId,$supplierId,$items,$trigger);		
						$output = 'Email';
					} else {
						$email = false;
					}
	            	// Create XML
					if ($supplierRes['xml_enabled'] == 1 && $supplierRes['xml_csv'] == 0 && $supplierRes['email_attachement'] != 1) {
					    $xml = Mage::getModel('supplier/output')->getXml($orderId,$supplierId,$items,$trigger);
						$output = 'Xml';	    
					} else {
						$xml = false;
					}
					// Create CSV
					if ($supplierRes['xml_enabled'] == 1 && $supplierRes['xml_csv'] == 1 && $supplierRes['email_attachement'] != 2) {
					    $csv = Mage::getModel('supplier/output')->getCsv($orderId,$supplierId,$items,$trigger);
						$output = 'Csv';		    
					} else {
						$csv = false;
					}
					

					if($xml && $trigger!="cron"|| $email && $trigger!="cron"|| $csv && $trigger!="cron" || !$output){
						if(!$output){
							$outputMsg = "Non selected";
						} else {
							$outputMsg = $output;
						}
						foreach ($supplier['cartItems'] as $item) {							
							$saveDropshipitem = $this->_getObserver()->saveDropshipitem($order,$supplierId,$item,$trigger);
							Mage::getSingleton("adminhtml/session")->addSuccess('Item ' . $item->getName() . ' is dropped to supplier ' . $supplierRes['name'] . ' with output method: ' . $outputMsg);
			            }
					} elseif($trigger=="cron"){
						foreach ($supplier['cartItems'] as $item) {							
							$saveDropshipitem = $this->_getObserver()->updateDropshipitem($order,$supplierId,$item,$trigger);
			            }
					} 
		        }
		
				if($xml || $email || $csv || !$output){
					$newOrderState = Mage_Sales_Model_Order::STATE_PROCESSING;
			        $newOrderStatus = Mage::getStoreConfig('supplier/suppconfig/orderstate');
					if($trigger=="invoice"){
			        	$statusMessage = 'This order is dropped on invoice create';
					} elseif($trigger=="invoice" && $settings['shipping']==1){
						$statusMessage = 'This order is dropped on invoice create and shipment is automatically done';
					} elseif($trigger=="manual" && $settings['shipping']==1){
						$statusMessage = 'This order was dropped manually and shipment is created'; 
					} elseif($trigger=="ordercreate"){
						$statusMessage = 'This order is dropped on order create'; 
					}elseif($trigger=="orderstatus"){
						$statusMessage = 'This order is dropped on order status change'; 
					} else{ 
						$statusMessage = 'This order was dropped manually'; 
					}
					
			        $order->setState($newOrderState, $newOrderStatus, $statusMessage, false)->save();
					$this->_getObserver()->processOrder($orderId,$trigger);
					
					return true;
				}
			}
    }

	private function _getSupplierListByOrder($order,$trigger){
		$supplierModel = Mage::getModel('supplier/supplier');
        $cartItems = $order->getAllItems();
        $supplierList = array();
        foreach ($cartItems as $item) {
            $productId = $item->getProductId();			
            $supplierRes = $supplierModel->getSupplierByAttribute($productId);
			$isDropped = Mage::getModel('supplier/dropshipitems')->getIsDropped($item);

							 
			if($supplierRes && $item->getProductType()!="configurable" && $item->getProductType()!="bundle" && $item->getQtyCanceled()!=$item->getQtyOrdered() && $item->getQtyRefunded()!=$item->getQtyOrdered() && !$isDropped){
	 
	           	if ($trigger=='cron' && $supplierRes['schedule_enabled']==2 || $supplierRes['schedule_enabled']==1 || !$supplierRes['schedule_enabled']){
		            $this->_getObserver()->logging("Yes we have one");	
		            if (isset($supplierList[$supplierRes['id']])) {
		                $supplierRes['cartItems'] = $supplierList[$supplierRes['id']]['cartItems'];
		            }
	            	$supplierRes['cartItems'][] = $item;
	            	$supplierList[$supplierRes['id']] = $supplierRes;
	          	} else {
	          		$this->_getObserver()->saveDropshipitem($order,$supplierRes['id'],$item,$trigger);
	          	}
			}        
		}
        return $supplierList;
    }
}