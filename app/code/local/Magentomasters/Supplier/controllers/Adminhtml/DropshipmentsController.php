<?php 
class Magentomasters_Supplier_Adminhtml_DropshipmentsController extends Mage_Adminhtml_Controller_Action {

    /**
     * Additional initialization
     *
     */

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('sales/order')
            ->_addBreadcrumb($this->__('Sales'), $this->__('Sales'))
            ->_addBreadcrumb($this->__('Shipments'),$this->__('Dropshipments'));
        return $this;
    }

    /**
     * Shipments grid
     */
    public function indexAction()
    {
       	
		$this->loadLayout();
		$this->_title($this->__('Sales'))->_title($this->__('Dropshipments'));
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Supplier Manager'));
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Supplier News'));

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->_addContent($this->getLayout()->createBlock('supplier/adminhtml_dropshipments'));
        $this->renderLayout();
    }
	
	public function exportCsvAction() {
		$fileName = 'dropshipments.csv';
		 
		$content = $this->getLayout ()->createBlock('supplier/adminhtml_dropshipments');
		
		$content->addColumnAfter('price', array(
				'header' => Mage::helper('supplier')->__('Price'),
				'index' => 'price',
		));
			
		$content->addColumnAfter('cost', array(
				'header' => Mage::helper('supplier')->__('Cost'),
				'index' => 'cost',
		));
		 
		$this->_prepareDownloadResponse($fileName, $content->getCsvFile());
 
	}

	public function emailAction(){
        $orderId = $this->getRequest()->getParam('id');
		$dropshipitem= Mage::getModel('supplier/dropshipitems')->load($orderId)->getData();
		$items = Mage::getModel('supplier/order')->getCartItemsBySupplier($dropshipitem[supplier_id],$dropshipitem[order_id]);
		$email = Mage::getModel('supplier/output')->getEmail($dropshipitem[order_id],$dropshipitem[supplier_id],$items);		
		Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('supplier')->__('Email Sent'));
		$this->_redirect('*/*/');
	}
	
	public function printDropshipmentsAction(){
		$data = $this->getRequest()->getPost();
		$dropshipment_ids = $this->getRequest()->getPost('dropshipment_ids', array());
		$file = 'dropshipments_'.date("Ymd_His").'.pdf';
        $pdf = Mage::getModel('supplier/output')->getPdfs($dropshipment_ids);	    
		$this->_prepareDownloadResponse($file,$pdf,'application/pdf');
	}
	
	public function printPdfReportAction(){
		$data = $this->getRequest()->getPost();
		$dropshipment_ids = $this->getRequest()->getPost('dropshipment_ids', array());
		$file = 'report_'.date("Ymd_His").'.pdf';
        $pdf = Mage::getModel('supplier/output')->getPdfReport($dropshipment_ids);	    
		$this->_prepareDownloadResponse($file,$pdf,'application/pdf');
	}
	
	public function dropshipformAction(){
		$data = $this->getRequest()->getPost();

		$items = array();
		$supplierList = array();
		
		foreach($data['supplier'] as $postItem){
			if($postItem['item_id']){

				$supplier = Mage::getModel('supplier/supplier')->load($postItem['id'])->getData();
				$item = Mage::getModel('sales/order_item')->load($postItem['item_id']);
				
				if(isset($postItem['additional_info'])){
					$item->setAdditionalInfo($postItem['additional_info']);
				}
				
				$items[$supplier['id']][] = $item; 
				$supplierList[$supplier['id']] = $supplier;
				$supplierList[$supplier['id']]['cartItems'] = $items[$supplier['id']];
			}
		}
		
		try {
			$dropship = Mage::getModel('supplier/observer')->form($this->getRequest()->getParam('order_id'),$supplierList); 
		} catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError("A error has occurred during dropshipment<br/><br/>" . $e);
			$this->_redirect('adminhtml/sales_order/view/order_id/' . $this->getRequest()->getParam('order_id'));		
			return;
		}
		
		$this->_getSession()->addSuccess($this->__('Order is dropshipped succesfully'));
		$this->_redirect('adminhtml/sales_order/view/order_id/' . $this->getRequest()->getParam('order_id'));	
	}

	public function massStatusAction(){
		$dropshipment_ids = (array) $this->getRequest()->getPost('dropshipment_ids');
		$status = (int)$this->getRequest()->getPost('status');
		
		try {
			if($status && $dropshipment_ids){
				foreach($dropshipment_ids as $dropshipmentid){
					Mage::getModel('supplier/dropshipitems')->load($dropshipmentid)->setStatus($status)->save();
				}
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('supplier')->__('Status updated'));
			}
		} catch(exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}

		$this->_redirect('*/*/');
	}
	
}