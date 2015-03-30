<?php 
class Magentomasters_Supplier_Model_Dropshipitems extends Mage_Core_Model_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('supplier/dropshipitems');
    }
	
	
	protected function getDropshipitemsCollection(){
		
		$collection = $this->getCollection();
		//$collection->getCollection()->setOrder("cat_order","ASC");
		//$collection->addStoreFilter(Mage::app()->getStore());
		
		return $collection;
	}
	
	public function getIsDropped($item){
		$collection = $this->getCollection()->addFieldToFilter('order_item_id',$item->getItemId());
		if(count($collection)){
			return true;
		} else {
			return false;
		}
	}
}