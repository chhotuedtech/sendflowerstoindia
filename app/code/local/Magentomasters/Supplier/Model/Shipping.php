<?php

class Magentomasters_Supplier_Model_Shipping{

	public function saveTableRate($file,$supplier_id,$condition_name){

		if($file && file_exists($file)){
			mage::log('Table Rate File Found',null,'tablerate.log');
			mage::log($file,null,'tablerate.log'); 
			$file = fopen($file, "r");
			$csv = array();	
			
			if($file){
				while (($result = fgetcsv($file)) !== false)
				{
	    			$csv[] = $result;
				}	
				fclose($file);
			}
  			
  			unset($csv['0']);

			if(!$condition_name){
				$condition_name = 'package_value';
			}
  			
  			$this->insertTableRateData($csv,$supplier_id,$condition_name);
		}
	}

	private function insertTableRateData($csv,$supplier_id,$condition_name){
		if(isset($csv)){
			
			// delete old data by supplier_id and condition name
			$this->deleteTableRateData($supplier_id,$condition_name);	
			
			// get csv info into a array	
			$lines = '';
			foreach ($csv as $item) {
				$line = '(';
				$line .= "'".$supplier_id."'"; 
				foreach($item as $key=>$value){
					if($key==3){
						$line .= ", '".$condition_name."'";
					}
					if($key==2 && !$value){
						$value="*";
					}
					$line .= ", '" . $value . "'";
				}
				$line .= '),';
				$lines .= $line;		
			}
	
			$lines = substr_replace($lines ,"",-1);			
			$lines .= ";";

			// Save the data
			try{
				$table = Mage::getSingleton('core/resource')->getTableName('supplier_tablerate');
				$connect = Mage::getSingleton( 'core/resource' )->getConnection('core_read');
				$query = "INSERT INTO `". $table ."` (`supplier_id`, `dest_country_id`, `dest_region_id`, `dest_zip`, `condition_name`, `condition_value`, `price`) VALUES " . $lines;
				$connect->query($query);
			} catch(exception $e){
				Mage::getSingleton('adminhtml/session')->addError("TABLE RATE SHIPPING ERROR: " . $e);
			}

		}
	}
	
	private function deleteTableRateData($supplier_id,$condition_name){
		try{
			$table = Mage::getSingleton('core/resource')->getTableName('supplier_tablerate');
			$connect = Mage::getSingleton( 'core/resource' )->getConnection('core_read');
			$query = "DELETE FROM ". $table ." WHERE supplier_id='".$supplier_id."' AND condition_name='".$condition_name."'";
			$connect->query($query);
		} catch(exception $e){
			Mage::getSingleton('adminhtml/session')->addError("TABLE RATE SHIPPING ERROR: " . $e);
		}
	}
	
	public function getRate($supplier_id,$dest_country_id,$dest_region_id,$dest_zip,$condition_name,$condition_value){
		try{
			mage::log($supplier_id." ".$dest_country_id." ".$dest_region_id." ".$dest_zip." ".$condition_name." ".$condition_value,null,'shipping-'. date("Y-m-d-H-i-s").'.log');
			$table = Mage::getSingleton('core/resource')->getTableName('supplier_tablerate');
			$connect = Mage::getSingleton( 'core/resource' )->getConnection('core_read');
			$query  = "SELECT * FROM ". $table ." WHERE supplier_id='".$supplier_id."'";
			$query .= " AND ((dest_country_id ='".$dest_country_id."' AND dest_region_id ='".$dest_region_id."' AND dest_zip ='".$dest_zip."') 
						OR (dest_country_id ='".$dest_country_id."' AND dest_region_id ='".$dest_region_id."' AND dest_zip = '') 
						OR (dest_country_id ='".$dest_country_id."' AND dest_region_id ='".$dest_region_id."' AND dest_zip = '*') 
						OR (dest_country_id ='".$dest_country_id."' AND dest_region_id = '*' AND dest_zip = '*') 
						OR (dest_country_id = '0' AND dest_region_id ='".$dest_region_id."' AND dest_zip = '*') 
						OR (dest_country_id = '0' AND dest_region_id = '*' AND dest_zip = '*') 
						OR (dest_country_id ='".$dest_country_id."' AND dest_region_id = '*' AND dest_zip = '') 
						OR (dest_country_id ='".$dest_country_id."' AND dest_region_id = '*' AND dest_zip ='".$dest_zip."') 
						OR (dest_country_id ='".$dest_country_id."' AND dest_region_id = '*' AND dest_zip = '*'))";
			$query .= " AND condition_name='".$condition_name."' AND  condition_value<='".$condition_value."'";
			$query .= " ORDER BY  `id` DESC, `dest_country_id` DESC, `dest_region_id` DESC, `dest_zip` DESC";
			$query .= " LIMIT 1";
			$result = $connect->query($query);
			$result = $result->fetchAll();
			return $result['0']['price']; 
		} catch(exception $e){
			Mage::log("TABLE RATE SHIPPING ERROR: " . $e, null, 'supplier.log');
		}
		
	}

} 

?>