<?php class Magentomasters_Supplier_Model_Source_Statusses{


	public function toOptionArray()
        {
            $collection = Mage::getResourceModel('sales/order_status_collection');
            $collection->load();
    
            $options = array();
            
            foreach ($collection as $orderStatus) {
                $options[] = array(
                   'label' => $orderStatus->getLabel(),
                   'value' => $orderStatus->getStatus()
                );
            }
    
            return $options;
        }
		
}