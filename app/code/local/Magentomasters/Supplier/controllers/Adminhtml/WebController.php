<?php

class Magentomasters_Supplier_Adminhtml_WebController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
    	
        $this->loadLayout()
                ->_setActiveMenu('supplier/items')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
        return $this;
    }

    public function indexAction() {
        $this->loadLayout();
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Supplier Manager'));
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Supplier News'));

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->_addContent($this->getLayout()->createBlock('supplier/adminhtml_web'));
        $this->renderLayout();
    }
	
	public function dropshipmentsAction(){
			$this->loadLayout();
			$this->getLayout()->getBlock('selections.grid')->setSupplier($this->getRequest()->getPost('id', null));
			$this->renderLayout();
	}
	
	public function productsgridAction(){
			$this->loadLayout();
			$this->getLayout()->getBlock('products.grid')->setProducts($this->getRequest()->getPost('productsIds', null));
			$this->renderLayout();
	}

	public function productsAction(){
			$this->loadLayout();
			$this->getLayout()->getBlock('products.grid')
			->setProducts($this->getRequest()->getPost('productsIds', null));
			$this->renderLayout();
	}
	
    public function editAction() {
    	
        $id     = $this->getRequest()->getParam('id');
        
        $model  = Mage::getModel('supplier/supplier')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('config_data', $model);
            Mage::register('web_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('supplier/items');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('supplier/adminhtml_web_edit'))
                    ->_addLeft($this->getLayout()->createBlock('supplier/adminhtml_web_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('web')->__('Supplier does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function saveAction()
    {
        $data = $this->getRequest()->getPost();

       	if($data){  
           	if(!$data['password']){
				unset($data['password']);
			} else {
				$data['password'] = md5($data['password']);
			}
			
			$model = Mage::getModel('supplier/supplier');        
            $model->setData($data)->setId($this->getRequest()->getParam('id'));
			
			// Check if supplier attribute excists
            $attributeInfo = Mage::getResourceModel('eav/entity_attribute_collection')->setCodeFilter('supplier')->getFirstItem();
            try {
                $attributeOptions = $attributeInfo->getSource()->getAllOptions(false);
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError("Supplier attribute not found. Please add a new attribute according to the manual!");
                Mage::getSingleton('adminhtml/session')->setFormData($data);
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
             
            // Save Attribute Option if the supplier is new            
            if(!$this->getRequest()->getParam('id')){ $this->_addAttributeValue($data['name']); }
			
			// Save Supplier
            try {            			
            	$model->save(); 	
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('supplier')->__('Supplier was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
			}
			catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				Mage::getSingleton('adminhtml/session')->setFormData($data);
			}	
				
			// Save Supplier Products
			$products = Mage::helper('adminhtml/js')->decodeGridSerializedInput($data['links']['products']);
			if(!empty($products)){ Mage::getModel('supplier/supplier')->saveSupplierProducts($products,$model->getName()); } 
	
			// Save Table Rate (If csv upload file is found)
			if(isset($_FILES['shipping_file']['name']) && $_FILES['shipping_file']['name']!='') {
                try {
                    $uploader = new Varien_File_Uploader('shipping_file');
                    $uploader->setAllowedExtensions(array('csv'));
                    $uploader->setAllowRenameFiles(false);
                    $uploader->setFilesDispersion(false);
                    $path = Mage::getBaseDir('media') . DS . 'supplier/tableratefile/'.$model->getId() .'/';
                    $upload = $uploader->save($path, $_FILES['shipping_file']['name'] );
                    Mage::getModel('supplier/shipping')->saveTableRate($path . $upload['file'],$model->getId(),$model->getShippingCondition()); 
					$model->setShippingFile('/supplier/tableratefile/'.$model->getId() .'/'.$upload['file']);   
					$model->save();
                } catch (Exception $e) {
              		Mage::getSingleton('adminhtml/session')->addError("TABLE RATE SHIPPING ERROR: " . $e);
                }
            }
			
			// Redirect after
			if ($this->getRequest()->getParam('back')) {
                $this->_redirect('*/*/edit', array('id' => $model->getId()));
                return;
            }
            $this->_redirect('*/*/');
            return;
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('supplier')->__('Unable to find Supplier to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
		$arr_ids = $this->getRequest()->getParam('id');
		
		if (!is_array($arr_ids)) {
	            $arr_ids = array($arr_ids);
	        }
		foreach( $arr_ids as $id ){
	        if ($id > 0) {
	            try {
	                $model = Mage::getModel('supplier/supplier');
					$supplier = $model->load($id);					
					$this->_deleteAttributeValue($supplier['name']);
	                $model->setId($id)->delete();
	                
	                $this->_redirect('*/*/');
	            } catch (Exception $e) {
	                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
	                $this->_redirect('*/*/edit', array('id' => $id));
	            }
	        }
			
		}
		
		Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Supplier was successfully deleted'));
	    $this->_redirect('*/*/');
    }

	private function _addAttributeValue($value){
			$arg_attribute = 'supplier';
			$arg_value = $value;
			
			$attr_model = Mage::getModel('catalog/resource_eav_attribute');
			$attr = $attr_model->loadByCode('catalog_product', $arg_attribute);
			
			$attr_id = $attr->getAttributeId();
			
			$option['attribute_id'] = $attr_id;
			
			$option['value']['any_option_name'][0] = $arg_value;
		
			$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
			$setup->addAttributeOption($option);	
	}
	
	private function _deleteAttributeValue($name){
			
			$option = Mage::getModel('supplier/supplier')->getSupplierOptionsId($name);
			Mage::getModel('supplier/supplier')->deleteOptionValue($option['option_id']);
			Mage::log($optionid , null, 'drop.log');	
	
	}
	
	public function importstockAction(){
		
		$id = $this->getRequest()->getParam('id');
		
		Mage::getModel('supplier/schedule')->importStock('manual',$id);
		
		$this->_redirect('*/*/edit', array('id' => $id));
		
	}
	
	public function downloadAction(){
		if($this->getRequest()->getParam("id")){
			$supplier = mage::getModel('supplier/supplier')->load($this->getRequest()->getParam("id"));
			$path = Mage::getBaseDir('media') . $supplier->getShippingFile(); 
			if(file_exists($path)){
				$this->_prepareDownloadResponse('tablerates-'.$this->getRequest()->getParam("id").'.csv', file_get_contents($path));
			} else {
				$this->_redirect("*/*/");
			}
		}
	}
}