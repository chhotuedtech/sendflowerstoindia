<?php

class Magentomasters_Supplier_Model_Shipping_Carrier_Suppliershipping extends Mage_Shipping_Model_Carrier_Abstract implements Mage_Shipping_Model_Carrier_Interface
{

	protected $_code = 'suppliershipping';
	protected $_request = null;
	protected $_result = null;

	public function collectRates(Mage_Shipping_Model_Rate_Request $request)
	{
		if (!$this->getConfigFlag('active')) {
			return false;
		}

		$this->setRequest($request);

		$value = Mage::getModel('shipping/rate_result_method');
		$value->setCarrier('suppliershipping');
		$value->setMethodTitle('Shipping Costs');
		$value->setMethod('suppliershipping');

		$result = Mage::getModel('shipping/rate_result');

		$cart = Mage::getSingleton('checkout/cart');
		$totals = $cart->getQuote()->getTotals();
		$country = $cart->getQuote()->getShippingAddress()->getCountry();
		$region = $cart->getQuote()->getShippingAddress()->getRegion();
		$postcode = $cart->getQuote()->getShippingAddress()->getPostcode();
		$nosupplier = false;

		foreach($request->getAllItems() as $item ){	
			$itemData = $item->getData();
			$qty = $item->getQty();
			$supplierModel = Mage::getModel('supplier/supplier');
			$attribute_id = $supplierModel->getSupplierAttributeId();
			$product_id = $item->getProductId();
			$suppliercheck = $supplierModel->getSavedOptionValue($attribute_id,$product_id); 								
			if($suppliercheck) {
				$suppliername = $supplierModel->getOptionValue($suppliercheck);	
				$supplierRes = $supplierModel->getSupplierByName($suppliername);
				$supplierId = $supplierRes['id'];
				$suppliers[$supplierId]['supplier'] = $supplierRes;
				$suppliers[$supplierId]['products'][$item->getProductId()] = $item;
			} else {
				$nosupplier = true;
				$nosuppliertotal += $item->getRowTotal();
				$nosupplierweight += $item->getRowWeight();
			}
		}
		
		$total = "";
				
		foreach ($suppliers as $key => $supplier){	

			$totalprice = 0;
			$shipping_method = $supplier['supplier']['shipping_method'];		
			$supplierId = $key;
	
			if ($shipping_method==1){
				
				$freeshipping = $supplier['supplier']['shipping_cost_free']; 
				$supplierproducts =  $supplier['products'];
				$suppliertotal = '';
				$totalweight = '';
				$cost = $supplier['supplier']['shipping_cost'];
				
				foreach($supplierproducts as $item){
					$suppliertotal += $item->getRowTotal();
					$totalweight += $item->getRowWeight();
				}
				
				if($suppliertotal > $freeshipping){
					$cost = 0;
				}
	
			} elseif($shipping_method==2){
				$supplierproducts =  $supplier['products'];	
				if($supplier['supplier']['shipping_condition']=="package_weight"){
					foreach($supplierproducts as $item){
						$totalweight += $item->getRowWeight();
					}
					$rate = Mage::getModel('supplier/shipping')->getRate($supplierId,$country,$region,$postcode,'package_weight',$totalweight);
				} elseif($supplier['supplier']['shipping_condition']=="package_value"){
					foreach($supplierproducts as $item){
						$totalprice += $item->getRowTotal();
					}
					$rate = Mage::getModel('supplier/shipping')->getRate($supplierId,$country,$region,$postcode,'package_value',$totalprice);
				} elseif($supplier['supplier']['shipping_condition']=="package_qty"){
					$count=0;	
					foreach($supplierproducts as $item){
						$count += $item->getQty();
					}
					$rate = Mage::getModel('supplier/shipping')->getRate($supplierId,$country,$region,$postcode,'package_qty',$count);
				}			
				$cost = $rate;
			} elseif($shipping_method==3){
				$supplierproducts =  $supplier['products'];
				$count=0;
				$peritem = $supplier['supplier']['shipping_cost'];		
				foreach($supplierproducts as $item){
					$count += $item->getQty();
				}
				$cost = $peritem * $count;
			}
			
			$cost_calculation = $this->getConfigData('cost_calculation');
			
			if($cost_calculation==1) {
				$total += $cost; 
			}  			
			elseif($cost_calculation==2) {
				if($cost > $highest){
					 $highest = $cost; 
				}
				$total = $highest;
			} 
			
		}
		
		if($nosupplier){
			$base_minimum = Mage::getStoreConfig('carriers/suppliershipping/default_order_amount_free');
			$base_costs = Mage::getStoreConfig('carriers/suppliershipping/default_shipping_rate_no_supplier');
			if($nosuppliertotal < $base_minimum){
				$total = $total + $base_costs;
			}
		}
		
		if($request->getFreeShipping() === true || $request->getPackageQty() == $this->getFreeBoxes()) {
            		$total = '0.00';
        	}
		
		$value->setPrice($total);

		$result->append($value);

		$this->_result = $result;

		return $this->getResult();
	}

	public function setRequest(Mage_Shipping_Model_Rate_Request $request)
	{
		$this->_request = $request;

		$r = new Varien_Object();

		$this->_rawRequest = $r;

		return $this;
	}

	public function getResult()
	{
	   return $this->_result;
	}

	public function getCode($type, $code='')
	{
		$codes = array(
			'method'=>array(
				'FREIGHT'    => Mage::helper('usa')->__('Freight')
			)
		);

		if (!isset($codes[$type])) {
			//throw Mage::exception('Mage_Shipping', Mage::helper('usa')->__('Invalid ODFL CGI code type: %s', $type));
			return false;
		} elseif (''===$code) {
			return $codes[$type];
		}

		if (!isset($codes[$type][$code])) {
			// throw Mage::exception('Mage_Shipping', Mage::helper('usa')->__('Invalid ODFL CGI code for type %s: %s', $type, $code));
			return false;
		} else {
			return $codes[$type][$code];
		}
	}

	/**
	 * Get allowed shipping methods
	 *
	 * @return array
	 */
	public function getAllowedMethods()
	{
		$allowed = explode(',', $this->getConfigData('allowed_methods'));
		$arr = array();
		foreach ($allowed as $k) {
			$arr[$k] = $this->getCode('method', $k);
		}
		return $arr;
	}

	public function proccessAdditionalValidation( Mage_Shipping_Model_Rate_Request $request )
	{

		if(!count($request->getAllItems())) {
			return $this;
		}

		$errorMsg = '';
		$configErrorMsg = $this->getConfigData('specificerrmsg');
		$defaultErrorMsg = Mage::helper('shipping')->__('The shipping module is not available.');
		$showMethod = $this->getConfigData('showmethod');

		if (!$errorMsg && !$request->getDestPostcode() && $this->isZipCodeRequired()) {
			$errorMsg = Mage::helper('shipping')->__('This shipping method is not available, please specify ZIP-code');
		}

		if ($errorMsg && $showMethod) {
			$error = Mage::getModel('shipping/rate_result_error');
			$error->setCarrier($this->_code);
			$error->setCarrierTitle($this->getConfigData('title'));
			$error->setErrorMessage($errorMsg);
			return $error;
		} elseif ($errorMsg) {
			return false;
		}
		return $this;

		return $this;

	}

	public function isStateProvinceRequired()
	{
		return false;
	}

	public function isCityRequired()
	{
		return false;
	}

	public function isZipCodeRequired()
	{
		return false;
	}

}

?>