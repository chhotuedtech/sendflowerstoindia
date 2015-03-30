<?php
class Magentomasters_Supplier_Block_Adminhtml_Web_Edit_Tab_Products extends Mage_Adminhtml_Block_Widget_Grid
{

	public function __construct() {
		parent::__construct();
		$this->setId('selectionsGrid');
		$this->setUseAjax(true); // Using ajax grid is important
		$this->setDefaultFilter(array('in_products'=>1)); // By default we have added a filter for the rows, that in_products value to be 1
		$this->setDefaultSort('position');
		$this->setDefaultDir("DESC");
		$this->setSaveParametersInSession(false);  //Dont save paramters in session or else it creates problems
    }

    protected function _getHelper() {
        return Mage::helper('supplier');
    }

    protected function _getStore() {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    protected function _prepareCollection() {
        $store = $this->_getStore();
        $collection = Mage::getModel('catalog/product')->getCollection()
                        ->addAttributeToSelect('sku')
                        ->addAttributeToSelect('name')
						->addAttributeToSelect('supplier')
                        ->addAttributeToSelect('attribute_set_id')
                        ->addAttributeToSelect('type_id')
						->addAttributeToSelect('manufacturer')
			//->addAttributeToSelect('subgroep')
                        ->addFieldToFilter('type_id', array('neq' => Mage_Catalog_Model_Product_Type::TYPE_GROUPED))
                        ->addFieldToFilter('type_id', array('neq' => Mage_Catalog_Model_Product_Type::TYPE_BUNDLE))
                        ->joinField('qty', 'cataloginventory/stock_item', 'qty', 'product_id=entity_id', '{{table}}.stock_id=1', 'left');

        if ($store->getId()) {
            $collection->addStoreFilter($store);
            $collection->joinAttribute('custom_name', 'catalog_product/name', 'entity_id', null, 'inner', $store->getId());
            $collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner', $store->getId());
            $collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner', $store->getId());
            $collection->joinAttribute('price', 'catalog_product/price', 'entity_id', null, 'left', $store->getId());
        } else {
            $collection->addAttributeToSelect('price');
            $collection->addAttributeToSelect('status');
            $collection->addAttributeToSelect('visibility');
        }
		
        $this->setCollection($collection);

        parent::_prepareCollection();
        
        return $this;
    }
	
	protected function _addColumnFilterToCollection($column)
	{
		// Set custom filter for in product flag
		if ($column->getId() == 'in_products') {
			$ids = $this->_getSelectedProducts();
			if (empty($ids)) {
				$ids = 0;
			}
			if ($column->getFilter()->getValue()) {
				$this->getCollection()->addFieldToFilter('entity_id', array('in'=>$ids));
			} else {
				if($productIds) {
					$this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$ids));
				}
			}
		} else {
			parent::_addColumnFilterToCollection($column);
		}
		return $this;
	}

    private function _getProductType() {
        $res = array();
        $type = Mage::getSingleton('catalog/product_type')->getOptionArray();
        if ($type) {
            foreach ($type as $key => $value) {
                if ($key != Mage_Catalog_Model_Product_Type::TYPE_GROUPED
                        && $key != Mage_Catalog_Model_Product_Type::TYPE_BUNDLE) {
                    $res[$key] = $value;
                }
            }
        }
        return $res;
    }

    protected function _prepareColumns() {
        $helper = $this->_getHelper();
		
		$this->addColumn('in_products', array(
                'header_css_class'  => 'a-center',
                'type'              => 'checkbox',
                'name'              => 'in_products',
                'values'            => $this->_getSelectedProducts(),
                'align'             => 'center',
                'index'             => 'entity_id'
         ));
		 
		 $this->addColumn('entity_id', array(
            'header'    => Mage::helper('supplier')->__('ID'),
            'width'     => '30px',
            'index'     => 'entity_id',
            'type'  => 'number',
         ));

        $this->addColumn('productname', array(
            'header' => $helper->__('Name'),
            'index' => 'name',
        ));

        $this->addColumn('type', array(
            'header' => $helper->__('Type'),
            'width' => 100,
            'index' => 'type_id',
            'type' => 'options',
            'options' => $this->_getProductType(),
        ));

        $sets = Mage::getResourceModel('eav/entity_attribute_set_collection')
                        ->setEntityTypeFilter(Mage::getModel('catalog/product')->getResource()->getTypeId())
                        ->load()
                        ->toOptionHash();

        $this->addColumn('set_name', array(
            'header' => $helper->__('Attrib. Set Name'),
            'width' => 100,
            'index' => 'attribute_set_id',
            'type' => 'options',
            'options' => $sets,
        ));

        $this->addColumn('sku', array(
            'header' => $helper->__('SKU'),
            'width' => 100,
            'index' => 'sku',
        ));

        $this->addColumn('price', array(
            'header' => $helper->__('Price'),
            'index' => 'price',
            'type' => 'currency',
            'currency_code'
            => (string) Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
        ));

        $this->addColumn('visibility', array(
            'header' => $helper->__('Visibility'),
            'width' => 70,
            'index' => 'visibility',
            'type' => 'options',
            'options' => Mage::getSingleton('catalog/product_visibility')->getOptionArray(),
        ));
		
		$this->addColumn('supplier', array(
			'type'  => 'options',
            'header' => Mage::helper('catalog')->__('Supplier'),
            'width' => 150,
            'index' => 'supplier',
			'options' => Mage::helper('supplier')->getSupplierOptionsById()
        ));

        $this->addColumn('status', array(
            'header' => $helper->__('Status'),
            'width' => 70,
            'index' => 'status',
            'type' => 'options',
            'options' => Mage::getSingleton('catalog/product_status')->getOptionArray(),
        ));
		
        return parent::_prepareColumns();
    }

    protected function _getSelectedProducts()   // Used in grid to return selected customers values.
	{
		$products = array_keys($this->getSelectedProducts());

		return $products;
		
	}

	public function getGridUrl()
	{
		return $this->_getData('grid_url') ? $this->_getData('grid_url') : $this->getUrl('*/*/productsgrid', array('_current'=>true));
	}
	
	public function getSelectedProducts()
	{
		$supplierId = $this->getRequest()->getParam('id');
		$supplierName = Mage::getModel('supplier/supplier')->load($supplierId)->getName();
		
		if($supplierId) {
			$collection = Mage::getModel('supplier/supplier')->getProductRelations($supplierName);
			$custIds = array();		
			foreach($collection as $collect){
				$product_id = $collect['entity_id'];
				$position = 1;			
				$custIds[$product_id] = array('position'=>$position);
			}
		}
		return $custIds;
	}
}
