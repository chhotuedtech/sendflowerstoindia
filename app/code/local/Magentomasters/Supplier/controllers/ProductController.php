<?php class Magentomasters_Supplier_ProductController extends Magentomasters_Supplier_Controller_Supplier {
	
	public function preDispatch(){
		parent::preDispatch();	

		if(Mage::getStoreConfig('supplier/interfaceoptions/interface_enabled')=='0'){
			$redirectPath = Mage::getUrl();
			$this->_redirectUrl($redirectPath); 
		} else if(Mage::getStoreConfig('supplier/interfaceoptions/interface_stock')=='0'){
			$redirectPath = Mage::getUrl() . "supplier/order";
			$this->_redirectUrl($redirectPath); 
		}
	}
	
	public function indexAction() {
        $session = Mage::getSingleton('core/session');
        $supplierId = $session->getData('supplierId');
        if( $supplierId && $supplierId != "logout") {
            $this->loadLayout();
            $this->renderLayout();
        } else {
            $redirectPath = Mage::getUrl() . "supplier/";
            $this->_redirectUrl( $redirectPath );
        }
	}
	
	public function addAction() {
        $session = Mage::getSingleton('core/session');
        $supplierId = $session->getData('supplierId');
        if( $supplierId && $supplierId != "logout") {
            $this->loadLayout();
            $this->renderLayout();
        } else {
            $redirectPath = Mage::getUrl() . "supplier/";
            $this->_redirectUrl( $redirectPath );
        }
	}
	
	public function editAction() {
        $session = Mage::getSingleton('core/session');
        $supplierId = $session->getData('supplierId');
        if( $supplierId && $supplierId != "logout") {
            $this->loadLayout();
            $this->renderLayout();
        } else {
            $redirectPath = Mage::getUrl() . "supplier/";
            $this->_redirectUrl( $redirectPath );
        }
	}
	
	public function deleteAction(){
		$id = $this->getRequest()->getParam('id');
		if($id){
			$product = Mage::getModel('catalog/product')->load($id);
			try{
				$product->delete();
				Mage::getSingleton("core/session")->addSuccess($this->__('Product Deleted'));
			} catch(exception $e){
				Mage::getSingleton("core/session")->addError($e->getMessage());
			}
			$this->_redirect('supplier/product');
			return;
		}
	}
	
	public function saveAction(){
		
		$config = Mage::getStoreConfig('supplier/interfaceoptions/interface_product_edit');
		$session = Mage::getSingleton('core/session');
        $supplierId = $session->getData('supplierId');		
		$data = $this->getRequest()->getPost();
		$id = $this->getRequest()->getParam('id');

		if($data && $supplierId && $config){
			
			$supplier = Mage::getModel('supplier/supplier')->load($supplierId);
			$supplierAttributeOption = Mage::getModel('supplier/supplier')->getSupplierOptionsId($supplier->getName());
			
			$storeId = Mage_Core_Model_App::ADMIN_STORE_ID;
			Mage::app()->setCurrentStore($storeId);	
				
			if($id){	
				$product = Mage::getModel('catalog/product')->load($id);
				if($product->getSupplier()!=$supplierAttributeOption['option_id']){
					Mage::getSingleton("core/session")->addError($this->__('You are not allowed to save this product'));
					$this->_redirect('supplier/product');
					return;
				}
				
			} else{
				$product = Mage::getModel('catalog/product');
			}
			
			foreach($data['product'] as $key=>$value){
			 	$setName = $this->getSetName($key);	
				$product->$setName($value);
				$new[$this->getSetName($key)] = $value;
			}
			
			$categories = implode(',', $data['categories']);
			
			// Upload Images
			$product = $this->_uploadImages($data,$product);
			
			// Remove Images
			$product = $this->_removeImages($data,$product);
			
			// Update Images
			$product = $this->_updateImages($data,$product);

			if(!$id){
				$product->setTypeId($data['typeId']);
				$product->setAttributeSetId($data['attributeSetId']);
			}
			
			$product->setCategoryIds($categories);
			$product->setSupplier($supplierAttributeOption['option_id']);
			$product->setWebsiteIds($data['website']);	 
			$product->save();
			
			$newProductId = $product->getId();

			// Save Stock
			if(isset($data['stock']['qty']) && $newProductId){
				if($data['stock']['qty']==0){ 
					$stockData = array(
					  'qty' => $data['qty'],
					  'is_in_stock' => 0
					);
				} {
					$stockData = array(
					  'qty' => $data['stock']['qty'],
					  'is_in_stock' => 1
					);
				}
				try{
				 	$product = Mage::getModel('catalog/product' )->load($newProductId);
				 	$product->setStockData($stockData);
				 	$product->save();
				} catch(exception $e){
					Mage::getSingleton("core/session")->addError($e->getMessage());
				}
			}
			Mage::getSingleton("core/session")->addSuccess($this->__('Product Saved'));
            $this->_redirect('supplier/product/edit',array('id'=>$newProductId));
			
		}
		
	}

	private function _uploadImages($data,$product){
		$session = Mage::getSingleton('core/session');
        $supplierId = $session->getData('supplierId');
		
		if($supplierId){
			foreach($_FILES['file']['name'] as $key=>$file){
				
				if(isset($_FILES['file']['name'][$key]) && $_FILES['file']['name'][$key] != '') {
					try {
													
						$uploader = new Varien_File_Uploader('file['.$key.']');
		           		$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
						$uploader->setAllowRenameFiles(true);
						$uploader->setFilesDispersion(false);
						$path = Mage::getBaseDir('media') . DS . "supplier" . DS . $supplierId . DS . $key;
						$result = $uploader->save($path, $_FILES['link_file']['name'][$key]);
						
						if(!$result['error']){
							$product->addImageToMediaGallery($result['path'] . DS . $result['file'],null,true,false);
							unlink($result['path'] . DS . $result['file']);
						}
						
					} catch (Exception $e) {
			      		Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
			        }	
				}
			}
		}
	  	
		return $product;		
	}


	// Mage_Catalog_Model_Product_Attribute_Backend_Media
	private function _removeImages($data,$product){
		$attributes = $product->getTypeInstance()->getSetAttributes();	
		if (isset($attributes['media_gallery']) && isset($data['images']['remove'])) {
            $gallery = $attributes['media_gallery'];
			foreach($data['images']['remove'] as $remove){
				if($gallery->getBackend()->getImage($product, $remove)){
					$gallery->getBackend()->removeImage($product,$remove);
					unlink(Mage::getBaseDir('media') . "/catalog/product/" . $remove);
				}
			}
		}
		return $product;
	}
	
	private function _updateImages($data,$product){
		$attributes = $product->getTypeInstance()->getSetAttributes();	
		
		// Update Exclude/Disabled
		if(isset($attributes['media_gallery']) && isset($data['images']['disable'])) {
		 	$gallery = $attributes['media_gallery'];
			foreach($data['images']['disable'] as $disabled){
				$imagedata = array('disabled'=>true,'exclude'=>true);
				$gallery->getBackend()->updateImage($product,$disabled,$imagedata);
			}
		}
		
		// Update positions
		if(isset($attributes['media_gallery']) && isset($data['images']['position'])) {
		 	$gallery = $attributes['media_gallery'];
			foreach($data['images']['position'] as $key=>$position){
				$positiondata = array('position'=>$position);
				$gallery->getBackend()->updateImage($product,$key,$positiondata);
			}
		}
		
		// Update labels
		if(isset($attributes['media_gallery']) && isset($data['images']['label'])) {
		 	$gallery = $attributes['media_gallery'];
			foreach($data['images']['label'] as $key=>$label){
				$labeldata = array('label'=>$label);
				$gallery->getBackend()->updateImage($product,$key,$labeldata);
			}
		}
		
		// Update imagetypes
		if(isset($data['imagetypes'])) {
			foreach($data['imagetypes'] as $key=>$type){
				$setName = 'set'. Mage::helper('supplier')->getMethodName($key); 
				$product->$setName($type);
			}
		}
		
		return $product;
	}
	
	public function saveStockAction(){
		$session = Mage::getSingleton('core/session');
        $supplierId = $session->getData('supplierId');
		$productId = $this->getRequest()->getParam('id');
        if( $supplierId && $supplierId != "logout" && $productId) {
			 $data = $this->getRequest()->getPost();
			 $supplier = Mage::getModel('supplier/supplier')->load($supplierId);
			 $supplierAttributeOption = Mage::getModel('supplier/supplier')->getSupplierOptionsId($supplier->getName());
			 if($productId){
				if(!$data['qty'] || $data['qty']==0){ 
					$stockData = array(
					  'qty' => $data['qty'],
					  'is_in_stock' => 0
					);
				} else {
					$stockData = array(
					  'qty' => $data['qty'],
					  'is_in_stock' => 1
					);
				}
				try{
				 	$product = Mage::getModel('catalog/product' )->load($productId);
					
					if($product->getSupplier()!=$supplierAttributeOption['option_id']){
						Mage::getSingleton("core/session")->addError($this->__('you are not allowed to update stock for this product'));
						$this->_redirect('supplier/product');
						return;
					}
					
				 	$product->setStockData($stockData);
				 	$product->save();
					Mage::getSingleton("core/session")->addSuccess($this->__('Stock Updated'));
				} catch(exception $e){
					Mage::getSingleton("core/session")->addError($e->getMessage());
				}
            	$this->_redirect('supplier/product');
			 }
        } else {
            $redirectPath = Mage::getUrl() . "supplier/";
            $this->_redirect('supplier/product');
        }
	
	}
	
	private function getSetName($value){
		$setNameParts = explode("_",$value);
		$newSetName = 'set';	
		foreach($setNameParts as $setNamePart){
			$setName = ucfirst($setNamePart);
			$newSetName .= $setName; 
		}
		return $newSetName;
	}
	
}