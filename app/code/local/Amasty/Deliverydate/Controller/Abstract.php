<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Controller_Abstract extends Mage_Adminhtml_Controller_Action
{
    protected $_title     = 'Exceptions: Dates and Holidays';
    protected $_modelName = 'holidays';
    protected $_modelId   = 'holiday_id';
    
    protected function _setActiveMenu($menuPath)
    {
        $this->getLayout()->getBlock('menu')->setActive($menuPath);
        $this->_title($this->__('Sales'))
             ->_title($this->__('Delivery Date'))
             ->_title($this->__($this->_title));	        
        
        return $this;
    } 
    
    public function indexAction()
    {
	    $this->loadLayout(); 
        $this->_setActiveMenu('sales/deliverydate/' . $this->_modelName);
        $this->_addContent($this->getLayout()->createBlock('amdeliverydate/adminhtml_' . $this->_modelName)); 	    
 	    $this->renderLayout();
    }

	public function newAction() 
	{
        $this->editAction();
	}
	
    public function editAction() 
    {
		$id    = (int) $this->getRequest()->getParam($this->_modelId);
		$model = Mage::getModel('amdeliverydate/' . $this->_modelName)->load($id);
        
		if ($id && !$model->getId()) {
    		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('amdeliverydate')->__('Record does not exist'));
			$this->_redirect('*/*/');
			return;
		}
		
		$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
		if (!empty($data)) {
			$model->setData($data);
		} else {
		    $this->prepareForEdit($model);
		}
		
		Mage::register('amdeliverydate_' . $this->_modelName, $model, true);
        
		$this->loadLayout();
		
		$this->_setActiveMenu('sales/deliverydate/' . $this->_modelName);
		$this->_title($this->__('Edit'));
		
        $this->_addContent($this->getLayout()->createBlock('amdeliverydate/adminhtml_' . $this->_modelName . '_edit'));
        $this->_addLeft($this->getLayout()->createBlock('amdeliverydate/adminhtml_' . $this->_modelName . '_edit_tabs'));
        
		$this->renderLayout();
	}

	public function saveAction() 
	{
        $id    = (int) $this->getRequest()->getParam($this->_modelId);
	    $model = Mage::getModel('amdeliverydate/' . $this->_modelName);
	    $data  = $this->getRequest()->getPost();
        
		if ($data) {
            $model->setData($data);
            if ($id) {
                $model->setId($id);
            }
			try {
			    $this->prepareForSave($model);
			    
				$model->save();
				
				Mage::getSingleton('adminhtml/session')->setFormData(false);
				
				$msg = Mage::helper('amdeliverydate')->__('Record has been successfully saved');
                Mage::getSingleton('adminhtml/session')->addSuccess($msg);
                if ($this->getRequest()->getParam('continue')) {
                    $this->_redirect('*/*/edit', array($this->_modelId => $model->getId()));
                } else {
                    $this->_redirect('*/*');
                }
            } 
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                if ($id) {
                    $this->_redirect('*/*/edit', array($this->_modelId => $id));
                } else {
                    $this->_redirect('*/*/edit');
                }
                
            }	
            return;
        }
        
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('amdeliverydate')->__('Unable to find a record to save'));
        $this->_redirect('*/*');
	} 
	
    public function deleteAction()
    {
		$id    = (int) $this->getRequest()->getParam($this->_modelId);
		$model = Mage::getModel('amdeliverydate/' . $this->_modelName)->load($id);

		if ($id && !$model->getId()) {
    		Mage::getSingleton('adminhtml/session')->addError($this->__('Record does not exist'));
			$this->_redirect('*/*/');
			return;
		}
         
        try {
            $model->delete();
            Mage::getSingleton('adminhtml/session')->addSuccess(
                $this->__('Record has been successfully deleted'));
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        
        $this->_redirect('*/*/');
    }	
		
    public function massDeleteAction()
    {
        $ids = $this->getRequest()->getParam($this->_modelName . 's');
        if (!is_array($ids)) {
             Mage::getSingleton('adminhtml/session')->addError(Mage::helper('amdeliverydate')->__('Please select records'));
             $this->_redirect('*/*/');
             return;
        }
         
        try {
            foreach ($ids as $id) {
                $model = Mage::getModel('amdeliverydate/' . $this->_modelName)->load($id);
                $model->delete();
            }
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('amdeliverydate')->__(
                    'Total of %d record(s) were successfully deleted', count($ids)
                )
            );
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        
        $this->_redirect('*/*/');
    }
    
    protected function prepareForSave($model)
    {
        $stores = $model->getData('store_ids');
        if (is_array($stores)) {
            // need commas to simplify sql query
            $model->setData('store_ids', ',' . implode(',', $stores) . ',');    
        } else { // need for null value
            $model->setData('store_ids', ''); 
        }
        return true;
    }
    
    protected function prepareForEdit($model)
    {
        $stores = $model->getData('store_ids');
        if (!is_array($stores)) {
            $model->setData('store_ids', explode(',', $stores));
        }
        return true;
    }
}
