<?php class Magentomasters_Supplier_Controller_Supplier extends  Mage_Core_Controller_Front_Action
{
	public function preDispatch(){
		parent::preDispatch();
		
		$request = $this->getRequest();

		$openActions = 	array(
				'index' => 	array(
								'forgot',
								'index',
								'login',
								'register'
							)
		);

		// General Controller Security
		if(!in_array($request->getActionName(), $openActions[$request->getControllerName()])){
	        $supplierId =  Mage::getSingleton('core/session')->getData('supplierId');	
			if(!$supplierId || !is_numeric($supplierId)) {
				$this->_redirect('supplier/index');
				return;
			}
		}
		
		$supplierId = Mage::getSingleton('core/session')->getData('supplierId');	
		$supplier = Mage::getModel('supplier/supplier')->load($supplierId);
		$supplierAttributeOption = Mage::getModel('supplier/supplier')->getSupplierOptionsId($supplier->getName());
		
		// Deactivated Supplier
		if(!in_array($request->getActionName(), $openActions[$request->getControllerName()]) && $supplier->getStatus()!=1){
			Mage::getSingleton('core/session')->setData( 'supplierId' , 'logout' );	
			$this->_redirect('supplier/index');
			return;
		}
		
		// Product Controller Security
		if($request->getControllerName()=='product'){
			$id = $this->getRequest()->getParam('id');
			if($id){
				$product = Mage::getModel('catalog/product')->load($id);
				if($product->getSupplier()!=$supplierAttributeOption['option_id']){
					Mage::getSingleton("core/session")->addError($this->__('You are to view/edit or modify this product'));
					$this->_redirect('supplier/product');
					return;
				}
			}
		}
		
		$storeId = Mage_Core_Model_App::ADMIN_STORE_ID;
		$appEmulation = Mage::getSingleton('core/app_emulation');
		$initialEnvironmentInfo = $appEmulation->startEnvironmentEmulation($storeId);
		
		Mage::register('supplier_emulation',$initialEnvironmentInfo);
		
	}

	public function postDispatch(){
		parent::postDispatch();
		
		if(Mage::registry('supplier_emulation')){
			$appEmulation = Mage::getSingleton('core/app_emulation');
			$appEmulation->stopEnvironmentEmulation(Mage::registry('supplier_emulation'));	
			Mage::unregister('supplier_emulation');
		}
	}
}
	