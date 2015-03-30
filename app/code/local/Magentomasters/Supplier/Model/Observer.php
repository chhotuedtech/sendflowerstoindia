<?php

class Magentomasters_Supplier_Model_Observer {

	public function logging($value){
		$settings = $this->settings(null);
		if ($settings['logging']== '1'){
			Mage::log($value, null, "Ultimate_Dropship.log",true);
		}
	}
	
	private function _getProcessor(){
		return Mage::getModel('supplier/processor');
	}

	public function settings($store_id){
		$supplierModel = Mage::getModel('supplier/supplier');
		$settings = $supplierModel->getSupplierSettings($store_id);
		return $settings;
	}

	public function invoice($observer){	
		$this->logging('Start Auto Invoice Dropshipment');
		$eventData = $observer->getEvent()->getData();
		$eventName = $eventData['name'];
		if ($eventName == 'sales_order_invoice_pay'){
			$invoice = $observer->getEvent()->getInvoice();
			$order = $invoice->getOrder();
			$settings = $this->settings($order->getStoreId());
			if($settings['method'] == 'invoice' || $settings['method'] == 'invoicemanual'){
				$orderEntityid = $order->getEntity_id();
				$check = Mage::getModel('supplier/dropshipitems')->getCollection()->addFieldToFilter('order_id',$orderEntityid)->count();
				// Only Continue when there is no dropshipment allready
				if(!$check){
					$this->_getProcessor()->dropship($order,"invoice",null);
				}
			}
		}
	}
	
	public function ordercreate($observer){
		$order = $observer->getEvent()->getOrder();	
		$settings = $this->settings($order->getStoreId());
		if($settings['method'] == 'ordercreate'){
			$this->logging('Start Ordercreate Dropshipment');
			$orderEntityid = $order->getEntity_id();
			$check =  Mage::getModel('supplier/dropshipitems')->getCollection()->addFieldToFilter('order_id',$orderEntityid)->count();
			// Check if order is not allready dropped
			if(!$check){
				$this->_getProcessor()->dropship($order,"ordercreate",null);	
			}
		}
	}
	
	public function ordersave($observer){
		$order = $observer->getEvent()->getOrder();	
		$settings = $this->settings($order->getStoreId());
		if($settings['method'] == 'orderstatus'){
			$this->logging('Start Orderstatus Dropshipment');
			$orderEntityid = $order->getEntity_id();
			$check =  Mage::getModel('supplier/dropshipitems')->getCollection()->addFieldToFilter('order_id',$orderEntityid)->count();
			// Check if order is not allready dropped
			if(!$check){
				$dropStatusses = Mage::getStoreConfig('supplier/suppconfig/droporderstatus');
				$dropStatusses = explode(',', $dropStatusses);
				if(in_array($order->getStatus(), $dropStatusses)){
					$this->_getProcessor()->dropship($order,"orderstatus",null);	
				}
			}
		}
	}
	
	public function manual($order_id){
		$this->logging('Start Manual Dropshipment');
		$this->logging($order_id);
		$order = Mage::getModel('sales/order')->load($order_id);
		$settings = $this->settings($order->getStoreId());
		if ($settings['method'] != 'invoice'){	
			$this->_getProcessor()->dropship($order,"manual",null);
		}
	}
	
	public function cron($order_id,$supplierList){
		$this->logging('Start Cron Dropshipment');
		$order = Mage::getModel('sales/order')->load($order_id);
		$this->_getProcessor()->dropship($order,"cron",$supplierList);
	}
	
	public function form($order_id,$supplierList){
		$this->logging('Start Form Dropshipment');
		$order = Mage::getModel('sales/order')->load($order_id);
		$this->_getProcessor()->dropship($order,"form",$supplierList);
	}

	private function freshOrder($order_id){
		$order = Mage::getModel('sales/order')->load($order_id);		
		return $order;
	}

	public function processOrder($orderId,$trigger){
		
		$this->logging('processOrder');
		
		$order = $this->freshOrder($orderId);
		
		$settings = $this->settings($order->getStoreId());
		
		// Ship Order
		if ($settings['shipping']=='1'){
			
			$this->logging('shipping');
		
			if($order->canShip()){
			
				$convertor   = Mage::getModel('sales/convert_order');
				$shipment    = $convertor->toShipment($order);
	
				foreach ($order->getAllItems() as $orderItem)
				{
					if (!$orderItem->getQtyToShip())
					{
						continue;
					}
					if ($orderItem->getIsVirtual())
					{
						continue;
					}
					$item = $convertor->itemToShipmentItem($orderItem);
					$qty = $orderItem->getQtyToShip();
					$item->setQty($qty);
					$shipment->addItem($item);
				}
	
				$shipment->register();
	
				$shipment->setEmailSent($sendEmails);
	
				$shipment->getOrder()->setIsInProcess(true);
       			$transactionSave = Mage::getModel('core/resource_transaction')
				->addObject($shipment)
				->addObject($shipment->getOrder())
				->save();
				
				if ($sendEmails) $shipment->sendEmail($sendEmails, '');
				$isModified = true;
								
			}
		
		}
				
		// Invoice Order Standard switched of		
		if ($settings['invoice']== '1' && $trigger=="manual"){
			$this->logging($settings['invoice']);
			$this->logging('invoice');	
			$order = $this->freshOrder($orderId);
			if($order->canInvoice()){ 
				$invoice = $order->prepareInvoice();
		
				$invoice->register();
				Mage::getModel('core/resource_transaction')
				   ->addObject($invoice)
				   ->addObject($invoice->getOrder())
				   ->save();
		
				$invoice->sendEmail(true, '');
			}
		}
		
		// Complete Order	
		if ($settings['complete']=='1'){
			
			$this->logging('complete');
			
			$order = $this->freshOrder($orderId);
		
			if (!$order->canInvoice() && ($order->getStatus() !== 'complete' && $order->getStatus() !== 'canceled' && $order->getStatus() !== 'closed'))
			{
			$order->setStatus(Mage_Sales_Model_Order::STATE_COMPLETE);
			$order->save();
			$isModified = true;
			}
		
		}
	}
	
	public function getNextId()
	{
     	$connect = Mage::getSingleton('core/resource')->getConnection('core_read');   
		$table = Mage::getSingleton('core/resource')->getTableName('core_config_data');
		
		$select = "SELECT * FROM ". $table . " WHERE path='supplier/suppconfig/number'";
		$selectresult = $connect->query($select);
		$check = $selectresult->fetch();
		$last = $check['value'];

        if ($last) {
           	$next = $last+1;
			$query = "UPDATE ". $table . " SET value=".$next." WHERE path='supplier/suppconfig/number'";		   
        } else {
			$last = "100000000";
			$next = $last+1;
			$query = "INSERT INTO ". $table . " (scope,scope_id,path,value) VALUES ('default','0','supplier/suppconfig/number','" . $next . "')";
		}
		
		$connect->query($query);
		
		$this->logging("Next Dropship Id" . $next);
		
        return $next;
    }
	
	public function saveDropshipitem($order,$supplier_id,$item,$trigger){
		$supplier = Mage::getModel('supplier/supplier')->load($supplier_id)->getData();
		$settings = $this->settings($order->getStoreId());
		
		$method = "0";
			
		if($supplier['email_enabled'] == 1 && $supplier['xml_enabled'] == 0){
			$method = "1";	
		} elseif ($supplier['xml_enabled'] ==1 && $supplier['email_enabled'] == 0 && $supplier['xml_ftp'] == 0){
			$method = "2";
		} elseif ($supplier['xml_enabled'] ==1 && $supplier['email_enabled'] == 0 && $supplier['xml_ftp'] == 1){
			$method = "3";
		}
		
		if($item->getParentItemId()){
			$parent = Mage::getModel('sales/order_item')->load($item->getParentItemId());
			$finalprice = $parent->getPrice() * $parent->getQtyOrdered();
			$finalcosts = $parent->getBaseCost() * $parent->getQtyOrdered();
		} else{
			$finalprice = $item->getPrice() * $item->getQtyOrdered();
			$finalcosts = $item->getBaseCost() * $item->getQtyOrdered();
		}

		$dropshipid = $this->getNextId();
		$orderEntityid = $order->getEntityId();
		$orderId = $order->getRealOrderId();
		$supplier_name = $supplier['name'];
		$supplier_id = $supplier['id'];
		$itemData = $item->getData();
		$itemId = $itemData['item_id'];	
		$productid = $item->getProductId();
		$productname = $item->getName();
		$sku = $item->getSku();	
		$qty = $item->getQtyOrdered();
		
		$connect = Mage::getSingleton('core/resource')->getConnection('core_read');
		
		if($supplier['schedule_enabled']==2){ $status = 2; } elseif($trigger=='cron'){ $status = 1; } else { $status=1; }
		
		$date = date ("Y-m-d H:m:s");
		$productname = mysql_escape_string($productname);
		
		$table = Mage::getSingleton('core/resource')->getTableName('supplier_dropship_items');
		$insert = "INSERT INTO " . $table . " (dropship_id,order_id,order_number,order_item_id,supplier_id,supplier_name,product_id,product_name,sku,qty,cost,price,status,method,date)
	 	VALUES
                (                    
                    '$dropshipid',
					'$orderEntityid',
					'$orderId',
					'$itemId',
					'$supplier_id',
					'$supplier_name',
					'$productid',
					'$productname',
					'$sku',
					'$qty',
					'$finalcosts',
					'$finalprice',
					'$status',
					'$method',
					'$date'
                )";	
		
		$connect->query($insert);
						
	}

	public function updateDropshipitem($order,$supplier_id,$item,$trigger){
		$this->logging('Update Dropship Item');
		
		$itemData = $item->getData();
		$id = $itemData['dropshipid'];	
		$date = date ("Y-m-d H:m:s");
		
		$table = Mage::getSingleton('core/resource')->getTableName('supplier_dropship_items');
		$connect = Mage::getSingleton('core/resource')->getConnection('core_read');
		$query = "UPDATE $table SET status='1', date='$date' WHERE id='$id'";
		$this->logging($query);
		$connect->query($query);
	}
	
	private function updateDropshipItemStatus($order_item_id,$status){
		$this->logging('Update Dropship Item Status');	
		$table = Mage::getSingleton('core/resource')->getTableName('supplier_dropship_items');
		$connect = Mage::getSingleton('core/resource')->getConnection('core_read');
		$query = "UPDATE $table SET status='$status' WHERE order_item_id='$order_item_id'";
		$this->logging($query);
		$connect->query($query);
	}
	
	public function updateDropshipItemComplete($order_item_id){
		$this->logging('Update Dropship Item Status');	
		$table = Mage::getSingleton('core/resource')->getTableName('supplier_dropship_items');
		$connect = Mage::getSingleton('core/resource')->getConnection('core_read');
		$query = "UPDATE $table SET status='5' WHERE order_item_id='$order_item_id'"; 
		$this->logging($query);
		$connect->query($query);
	}

	public function ordercancel($observer){
		$this->logging('order cancel');	
		if (isset($observer['item'])) {
		 	$item = $observer['item'];
		 	$orderItemId = $item->getItemId();
		 	$parentOrderItem = Mage::getModel('supplier/order')->getOrderParentItem($item->getItemId());
			if(!empty($parentOrderItem) && isset($parentOrderItem['item_id'])){
				$orderItemId = $parentOrderItem['item_id'];
			}	
		 	$this->updateDropshipItemStatus($orderItemId,'3'); 
		}	
	}
	
	public function ordercredit($observer){
		if(isset($observer['creditmemo'])) {
			 $creditmemo = $observer['creditmemo'];
			 $order = $creditmemo->getOrder();
			 $items = $creditmemo->getAllItems();
			 foreach($items as $item){
			 	$orderItemId = $item->getOrderItemId();
				$parentOrderItem = Mage::getModel('supplier/order')->getOrderParentItem($item->getOrderItemId());
				if(!empty($parentOrderItem) && isset($parentOrderItem['item_id'])){
					$orderItemId = $parentOrderItem['item_id'];
				}
				$this->updateDropshipItemStatus($orderItemId,'4'); 
			 }		
		}	
	}

	public function saveshipment($observer){
		try{
			$this->logging('save shipping');
			$shipment = $observer->getEvent()->getShipment();
			$items = $shipment->getAllItems(); 
			foreach($items as $item){
				$orderItemId = $item->getOrderItemId();
				$parentOrderItem = Mage::getModel('supplier/order')->getOrderParentItem($item->getOrderItemId());
				if(!empty($parentOrderItem) && isset($parentOrderItem['item_id'])){
					$orderItemId = $parentOrderItem['item_id'];
				}
				Mage::getModel('supplier/observer')->updateDropshipItemComplete($orderItemId);
			}
		} catch(exception $e){
			$this->logging($e);
		}
	}

	
	// ADD MASS ACTION OPTION
	
	public function addDropshipoption($observer) 
    {
        $block = $observer->getEvent()->getBlock();
		if(get_class($block) =='Mage_Adminhtml_Block_Widget_Grid_Massaction'
            && $block->getRequest()->getControllerName() == 'sales_order') 
	    	{
			    $block->addItem('dropship', array(
	            'label' => 'Dropship',
	            'url' => Mage::app()->getStore()->getUrl('supplier/dropship/dropshipmass/action/dropship'),
	            ));			
	    	} 
    }
	
	// ADD DROPSHIP BUTTON
	
	public function addOrderoptions($observer) 
    {
        $block = $observer->getEvent()->getBlock();
        
		//$this->logging(get_class($block));
		
		if(get_class($block) =='Mage_Adminhtml_Block_Sales_Order_View'
            && $block->getRequest()->getControllerName() == 'sales_order') 
        {
            	
				 if (Mage::getStoreConfig('supplier/suppconfig/method') == 'manual' || Mage::getStoreConfig('supplier/suppconfig/method') == 'invoicemanual' ) {
					
					$order_id = $block->getRequest()->getParam('order_id');
					
					$settings = $this->settings(null);
					$check = Mage::getModel('supplier/dropshipitems')->getCollection()->addFieldToFilter('order_id',$order_id)->count();					
		
					if($settings['multiple']==1){ $check=''; }
					
					if(!$check){
						$block->addButton('Dropship', array(
						  'label'     => 'Dropship',
						  'url' => Mage::app()->getStore()->getUrl('supplier/dropship/dropship/'),		  
						  'onclick'   => 'setLocation(\'' . $block->getUrl('supplier/dropship/dropshipment') . '\')',
						));
					}
					
					
				 }
						
        }
    }
	
	// Add Attribute option
	
	public function addSupplierAttributeField(Varien_Event_Observer $observer)
	{
		$yesno = array(
            array(
                'value' => 0,
                'label' => Mage::helper('catalog')->__('No')
            ),
            array(
                'value' => 1,
                'label' => Mage::helper('catalog')->__('Yes')
        ));	
			
		$fieldset = $observer->getForm()->getElement('base_fieldset');
        $attribute = $observer->getAttribute();
        $fieldset->addField('edit_by_supplier', 'select', array(
            'name' => 'edit_by_supplier',
            'label' => Mage::helper('catalog')->__('Edit By Supplier Allowed?'),
            'title' => Mage::helper('catalog')->__('Edit By Supplier Allowed?'),
            'values' => $yesno,
        ));
	}
	
} ?>