<?php class Magentomasters_Supplier_Block_Product extends Mage_Core_Block_Template { 
	private function _getProductModel(){
		return Mage::getModel('catalog/product');
	}	 

	public function getCollection(){
		
		$session = Mage::getSingleton('core/session');
		$supplierId = $session->getData('supplierId');
		$supplier =  Mage::getModel('supplier/supplier')->getSupplierById($supplierId);
		
		$suppliername = $supplier['name'];
		$attribute = Mage::getModel('supplier/supplier')->getSupplierOptionsId($suppliername);
		
		$collection = Mage::getModel('catalog/product')->getCollection();
		$collection->addFieldToFilter('supplier', $attribute['option_id']);
		//$collection->addAttributeToFilter('type_id', array ('simple','downloadable'));
		$collection->addAttributeToFilter('type_id',array('in' => array ('simple','downloadable')));
		$collection->addAttributeToSelect('*');
		
		return $collection;
		
	}
	
	public function getProduct(){
		$id= $this->getRequest()->getParam('id');	
		if($id){
			return Mage::getModel('catalog/product' )->load($id);
		} else{
			return Mage::getModel('catalog/product' );
		}
	}

	public function getStock($_product){
		return Mage::getModel('cataloginventory/stock_item')->loadByProduct($_product);
	}
	
	public function getCategories(){
		$tree = Mage::getModel( 'catalog/category' )->getTreeModel()->getCollection()->addAttributeToSort('position', 'asc');	
		foreach($tree as $cat ){
			$childrenIds = array();
	        $childr = $cat->getChildrenCategories();
	       
		    foreach($childr as $childCat) {
	                $childrenIds[] = $childCat->getId();
	        }
	
	        if($cat->getId() != 0  || $cat->getIsActive() == 1 ){
	                $menus[$cat->getId()] = array(
	                        'name' => $cat->getName() ,
	                        'id' => $cat->getId(),
	                        'url' => $cat->getUrl(),
	                        'productCount' => $cat->getProductCount(),
	                        'childrenIds' => $childrenIds,
	                        'parentId' => $cat->parentId
	                );
			}
    	}
		
		$id= $this->getRequest()->getParam('id');	
		
		if($id){
			$categories = $this->getProduct()->getCategoryIds();
		} else {
			$categories = array();
		}
			
		return $this->getCategoriesTree($menus,$categories);
	}

	public function getCategoriesTree( $array, $checkedCategories = array() , $index = 1 , $counter = 0 ) {
        $menu = '<ul id="tree" class="treeview">';
        $menu .= $this->getCategoriesTree_($array, $checkedCategories, $index);
        $menu .= '</ul>';
        return $menu;
    }
    
    private function getCategoriesTree_($array, $checkedCategories, $index){
        static $menu = '';
        if(!empty( $array[$index]["childrenIds"][0]))
        {
            foreach( $array[$index]["childrenIds"] as $key => $eachId )
            {
                $checked = '';
                if( in_array($array[$eachId]["id"], $checkedCategories) || $index == 1 ) {
                    $class = 'collapsable';
                    $display = 'block';
					$checked = 'checked'; 
                }else{
                    $class = 'expandable';
                    $display = '';
					$checked = ''; 
                }
                if(!empty($array[$eachId]['childrenIds'][0])) {
                    $menu .= '<li><div class="hitarea '.$class.'-hitarea"></div><span>'.$array[$eachId]['name'].' ('.$array[$eachId]['productCount'].')'.'</span>';
                    //$menu .= '<li><div class="hitarea '.$class.'-hitarea"></div><span>'.$array[$eachId]['name'].'</span>';
                    $menu .= '<ul style="display: '.$display.';">';
                    $this->getCategoriesTree_( $array, $checkedCategories, $eachId);
                    $menu .= '</ul>';
                    $menu .= '</li>';
                }else{
                    $menu .= '<li><input type="checkbox" value="'.$array[$eachId]["id"].'" name="categories[]" '.$checked.'>'.$array[$eachId]['name'].'('.$array[$eachId]['productCount'].')'.'</li>';
                }
            }
        }
        return $menu;
    }

	public function getWebsites()
    {
        $websites = Mage::app()->getWebsites();
		$product = $this->getProduct();
		$checked = false;
		$html = "<div class='supplier_product_field supplier_product_field_websites'><ul>"; 
        foreach($websites as $website){
        	$checked = (in_array($website->getWebsiteId(), $product->getWebsiteIds())) ? 'CHECKED' : true;	
			$html .= "<li>";	
			$html .= "<input type='checkbox' value='". $website->getWebsiteId() . "' name='website[]' ".$checked."/> ";
			$html .= $website->getName();
			$html .= "</li>";
        }
        $html .= "</ul></div>"; 
        return $html;
    }
	
	public function getTypeId(){
		$id	= $this->getRequest()->getParam('id');	
		if($id){
			$product = Mage::getModel('catalog/product' )->load($id); 
			return $product->getTypeId();
		} else {
			$post = $this->getRequest()->getPost();
			if($post && $post['producttype']){	 
				return $post['producttype'];
			}
		}
	}
	
	public function getAttributeSetId(){
		$id	= $this->getRequest()->getParam('id');	
		if($id){
			$product = Mage::getModel('catalog/product' )->load($id); 
			return $product->getAttributeSetId();
		} else {
			$post = $this->getRequest()->getPost();
			if($post && $post['attributeset']){	 
				return $post['attributeset'];
			}
		}
	}

	public function getAttributes(){
		$id	= $this->getRequest()->getParam('id');	
		if($id){
			$product = Mage::getModel('catalog/product' )->load($id); 
			$attributeSetId = $product->getAttributeSetId();
			$productTypeId = $product->getTypeId();
		} else {
			$post = $this->getRequest()->getPost();
			if($post && $post['attributeset'] && $post['producttype']){	 
				$attributeSetId = $post['attributeset'];
				$productTypeId = $post['producttype'];
			}
		}

		//return Mage::getModel("supplier/attributes")->getProductAttributes($attributeSetId , $productTypeId);

		$attributesByGroup = new Varien_Object();
		
		$groups = Mage::getModel('eav/entity_attribute_group')
            ->getResourceCollection()
            ->setAttributeSetFilter($attributeSetId)
            ->setSortOrder()
            ->load();
		
		foreach($groups as $group) {		
			 $attributes = Mage::getResourceModel('catalog/product_attribute_collection')
		            ->addFieldToFilter('edit_by_supplier',1)
		            ->setAttributeGroupFilter($group->getId())
		            ->addVisibleFilter()
		            ->checkConfigurableProducts()
		            ->load();	
			 $group->setAttributes($attributes->getData());		
		}
		
		return $groups; 
	}

	public function getAttributeHtml($attribute){
		//return $attribute['attribute_code'];
		
		//print_r($attribute);	
			
		$showPrice = true;

		$function = "get" . $this->_getMethodName($attribute['attribute_code']);
		
		if($this->getProduct()){
			$value = $this->getProduct()->$function();
		} else {
			$value = '';
		}	
			
		return $this->_getAttributeHtml($attribute['attribute_code'] ,$attribute['frontend_input'] ,$attribute['frontend_label'] ,$attribute['backend_type'],$attribute['attribute_id'] ,$attribute['is_required'],$value,$showPrice,$attribute['frontend_class']);
	}

	private function _getMethodName($value){
		$nameParts = explode("_",$value);
		$methodName = '';	
		foreach($nameParts as $namePart){
			$part = ucfirst($namePart);
			$methodName .= $part; 
		}
		return $methodName;
	}

    private function _getAttributeHtml($attributeCode = '', $attributeType = '', $attributeLabel = '', $attributeBackendType = '' , $attributeId = 0 , $isRequired , $value = '' , $showPrice = true, $attributeClass = '') {
        
        $this->_attributeCode = $attributeCode;
        $this->_attributeBackendType = $attributeBackendType;
        $this->_attributeLabel = $attributeLabel;
        $this->_attributeType = $attributeType;
        $this->_attributeId = $attributeId;
        $this->_attributeValue = $value;
        $this->_isRequired = $isRequired;
        $this->_attributeClass = $attributeClass;
				
		$method = "get" . $this->_getMethodName($attributeType);
		
		// echo 'Attribute Code: ' . $attributeCode .'<br/>';
		//echo 'Method Name: ' . $method .'<br/>';
		// echo 'Value: '. $value .'<br/>';
		// echo 'Setname: set' . $this->getSetName($attributeCode) . '<br>';

        if(method_exists($this , $method)) {
            return $this->$method();
        }

    }

    public function getTextarea() {
        if($this->_isRequired) {
            $labelString = "*";
            $class = "required-entry";
        }else {
            $labelString = "";
            $class = "";
        }
        $html = "<div class='supplier_product_field supplier_product_field_$this->_attributeCode'>";
        $html .= "<label class='supplier_product_add_label'>" . $this->_attributeLabel . " " . $labelString . "</label>";
        $html .= "<textarea class='" . $class . " supplier_product_add_textarea' name='product[$this->_attributeCode]'>" . $this->_attributeValue . "</textarea></div>";
        return $html;
    }

    public function getPrice(){
        if($this->_isRequired) {
            $labelString = "*";
            $class = "required-entry";
        }else {
            $labelString = "";
            $class = "";
        }
        $html = "<div class='supplier_product_field supplier_product_field_$this->_attributeCode'>";
        $html .= "<label class='supplier_product_add_label'>" . $this->_attributeLabel . " " . $labelString . "</label>";
        $html .= "<input class='" . $class . ' ' . $this->_attributeClass . " supplier_product_add_price' value='".round($this->_attributeValue,2)."' type='text' name='product[$this->_attributeCode]' /></div>";
        return $html;
    }
  
    public function getText() {
        if($this->_isRequired) {
            $labelString = "*";
            $class = "required-entry";
        }else {
            $labelString = "";
            $class = "";
        }
        $html = "<div class='supplier_product_field supplier_product_field_$this->_attributeCode'>";
        $html .= "<label class='supplier_product_add_label'>" . $this->_attributeLabel . " " . $labelString . "</label>";
        $html .= "<input class='" . $class . ' ' . $this->_attributeClass . " supplier_product_add_text' value='$this->_attributeValue' type='text' name='product[$this->_attributeCode]' /></div>";
        return $html;
    }

    public function getWeight() { 
        if($this->_isRequired) {
            $labelString = "*";
            $class = "required-entry";
        }else {
            $labelString = "";
            $class = "";
        }
        $html = "<div class='supplier_product_field supplier_product_field_$this->_attributeCode'>";
        $html .= "<label class='supplier_product_add_label'>" . $this->_attributeLabel . " " . $labelString . "</label>";
        $html .= "<input class='" . $class . ' ' . $this->_attributeClass . " supplier_product_add_text' value='$this->_attributeValue' type='text' name='product[$this->_attributeCode]' /></div>";
        return $html;
    }
    
    public function getDate() {
        if($this->_isRequired) {
            $labelString = "*";
            $class = "required-entry";
        }else {
            $labelString = "";
            $class = "";
        }
        $html = "<div class='supplier_product_field supplier_product_field_$this->_attributeCode'>";
        $html .= "<div><label class='supplier_product_add_label'>" . $this->_attributeLabel . " " . $labelString . "</label></div>";
        $html .= "<div>
            <input class='" . $class . " supplier_product_add_date' value='$this->_attributeValue' type='text' name='product[" . $this->_attributeCode . "]' />
            <img title='Select Date' id='" . $this->_attributeCode . "_trig' src='" . Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . "/skin/adminhtml/default/default/images/grid-cal.gif'>
                <script type='text/javascript'>
            //&lt;![CDATA[
                Calendar.setup({
                    inputField: '" . $this->_attributeCode . "',
                    ifFormat: '%m/%e/%y',
                    showsTime: false,
                    button: '" . $this->_attributeCode . "_trig',
                    align: 'Bl',
                    singleClick : true
                });
            //]]&gt;
            </script>
            </div></div>";
        return $html;
    }

    public function getStatusSelect() {
        $options = array(
                '' => '-- Please Select --',
                '1' => 'Enabled',
                '0' => 'Disabled'
        );
        $optionsString = "";
        foreach($options as $value => $option) {
            if($this->_attributeValue == $value) {
                $selected = "selected";
            }else {
                $selected = "";
            }
            $optionsString .= "<option $selected value='" . $value . "'>" . $option . "</option>";
        }
        if($this->_isRequired) {
            $labelString = "*";
            $class = "required-entry";
        }else {
            $labelString = "";
            $class = "";
        }
        $html = '<div class="supplier_product_field supplier_product_field_' . $this->_attributeCode . '">';
        $html .= '<label class="supplier_product_add_label">' . $this->_attributeLabel . " " . $labelString . '</label>';
        $html .= '<select class="' . $class . ' " name="product[status]">
                    ' . $optionsString . '
                  </select>';
        $html .='</div>';
        return $html;
    }

    public function getTaxClassSelect() {
        if($this->_isRequired) {
            $labelString = "*";
            $class = "required-entry";
        }else {
            $labelString = "";
            $class = "";
        }
        $option = array("id" => 0 , "name" => "None");
        $options = $taxes = Mage::getModel( 'supplier/product' )->getDinamicTaxClasses();
        array_unshift($options, $option);
        $optionsString = '';
        foreach($options as $option) {
            if($this->_attributeValue == $option['id']) {
                $selected = "selected";
            }else {
                $selected = "";
            }
            $optionsString .= '<option ' . $selected . ' value="' . $option['id'] . '">' . $option['name'] . '</option>';
        }
        $html = '<div class="supplier_product_field supplier_product_field_' . $this->_attributeCode . '">';
        $html .= '<label class="supplier_product_add_label">' . $this->_attributeLabel . " " . $labelString . '</label>';
        $html .= '<select class="' . $class . ' " name="product[' . $this->_attributeCode . ']">
                    <option value="">-- Please Select --</option>
                    ' . $optionsString . '
                  </select>';
        $html .='</div>';
        return $html;
    }

    public function getBoolean() {
        if($this->_isRequired) {
            $labelString = "*";
            $class = "required-entry";
        }else {
            $labelString = "";
            $class = "";
        }

        $options = array(
                '1' => 'Yes',
                '0' => 'No',
        );
        $optionsString = "";
//        var_dump($this->_attributeValue);
        foreach($options as $value => $option) {
            if($this->_attributeValue == $value) {
                $selected = "selected";
            }else {
                $selected = "";
            }

            $optionsString .= '<option ' . $selected . ' value="' . $value . '">' . $option . '</option>';
        }
        $html = '<div class="supplier_product_field supplier_product_field_' . $this->_attributeCode . '">';
        $html .= '<label class="supplier_product_add_label">' . $this->_attributeLabel . " " . $labelString . '</label>';
        $html .= '<select class="' . $class . ' " name="product[' . $this->_attributeCode . ']">
                    ' . $optionsString . '
                  </select>';
        $html .='</div>';
        return $html;
    }

    public function getMultiselect() {
        if($this->_isRequired) {
            $labelString = "*";
            $class = "required-entry";
        }else {
            $labelString = "";
            $class = "";
        }
        $options = Mage::getModel('supplier/product')->getSelectOptions($this->_attributeId);
        $optionsString = '';
        $values = explode("," , $this->_attributeValue);
        foreach($options as $option) {
            if(in_array($option['value'] , $values)) {
                $selected = "selected";
            }else {
                $selected = "";
            }
            $optionsString .= '<option ' . $selected . ' value="' . $option['value'] . '">' . $option['option'] . '</option>';
        }
        $html = '<div class="supplier_product_field supplier_product_field_' . $this->_attributeCode . '">';
        $html .= '<label class="supplier_product_add_label">' . $this->_attributeLabel . " " . $labelString . '</label>';
        $html .= '<select class="' . $class . '" multiple="multiple" name="product[' . $this->_attributeCode . '][]">
                    ' . $optionsString . '
                  </select>';
        $html .='</div>';
        return $html;
    }

    public function getDefaultSelect() {
        if($this->_isRequired) {
            $labelString = "*";
            $class = "required-entry";
        }else {
            $labelString = "";
            $class = "";
        }
        $options = Mage::getModel('supplier/product')->getSelectOptions($this->_attributeId);
        $optionsString = '<option value="0">&nbsp;&nbsp;-- '.$this->__('Please select').' --&nbsp;&nbsp;</option>';
        foreach($options as $option) {
            if($this->_attributeValue == $option['value']) {
                $selected = "selected";
            }else {
                $selected = "";
            }
            $optionsString .= '<option ' . $selected . ' value="' . $option['value'] . '">' . $option['option'] . '</option>';
        }
        $html = '<div class="supplier_product_field supplier_product_field_' . $this->_attributeCode . '">';
        $html .= '<label class="supplier_product_add_label">' . $this->_attributeLabel . " " . $labelString . '</label>';
        $html .= '<select class="' . $class . '" name="product[' . $this->_attributeCode . ']">
                    ' . $optionsString . '
                  </select>';
        $html .='</div>';
        return $html;
    }

	public function getCountrySelect(){
		$countries = Mage::getModel('directory/country_api')->items();
		$options = array('' => '-- Please Select --');
		foreach($countries as $country){
			$options[$country['country_id']] = $country['name']; 
		}
		$optionsString = "";
        foreach($options as $value => $option) {
            if($this->_attributeValue == $value) {
                $selected = "selected";
            }else {
                $selected = "";
            }
            $optionsString .= "<option $selected value='" . $value . "'>" . $option . "</option>";
        }
		if($this->_isRequired) {
            $labelString = "*";
            $class = "required-entry";
        }else {
            $labelString = "";
            $class = "";
        }
        $html = '<div class="supplier_product_field supplier_product_field_' . $this->_attributeCode . '">';
        $html .= '<label class="supplier_product_add_label">' . $this->_attributeLabel . " " . $labelString . '</label>';
        $html .= '<select class="' . $class . '" name="product[' . $this->_attributeCode . ']">
                    ' . $optionsString . '
                  </select>';
        $html .='</div>';
        return $html;
	}

    public function getPageLayoutSelect() {
        $options = array(
                '' => 'No layout updates',
                'empty' => 'Empty',
                'one_column' => '1 column',
                'two_columns_left' => '2 columns with left bar',
                'two_columns_right' => '2 columns with right bar',
                'three_columns' => '3 columns'
        );
        $optionsString = "";
        foreach($options as $value => $option) {
            if($this->_attributeValue == $value) {
                $selected = "selected";
            }else {
                $selected = "";
            }
            $optionsString .= "<option $selected value='" . $value . "'>" . $option . "</option>";
        }

        if($this->_isRequired) {
            $labelString = "*";
            $class = "required-entry";
        }else {
            $labelString = "";
            $class = "";
        }
        $html = '<div class="supplier_product_field supplier_product_field_' . $this->_attributeCode . '">';
        $html .= '<label class="supplier_product_add_label">' . $this->_attributeLabel . " " . $labelString . '</label>';
        $html .= '<select class="' . $class . '" name="product[' . $this->_attributeCode . ']">
                    ' . $optionsString . '
                  </select>';
        $html .='</div>';
        return $html;
    }

    public function getVisibilitySelect() {
        $options = array(
                '' => '-- Please Select --',
                '1' => 'Not Visible Individually',
                '2' => 'Catalog',
                '3' => 'Search',
                '4' => 'Catalog, Search'
        );
        $optionsString = "";
        foreach($options as $value => $option) {
            if($this->_attributeValue == $value) {
                $selected = "selected";
            }else {
                $selected = "";
            }
            $optionsString .= "<option $selected value='" . $value . "'>" . $option . "</option>";
        }
        if($this->_isRequired) {
            $labelString = "*";
            $class = "required-entry";
        }else {
            $labelString = "";
            $class = "";
        }
        $html = '<div class="supplier_product_field supplier_product_field_' . $this->_attributeCode . '">';
        $html .= '<label class="supplier_product_add_label">' . $this->_attributeLabel . " " . $labelString . '</label>';
        $html .= '<select class="' . $class . '" name="product[' . $this->_attributeCode . ']">
                    ' . $optionsString . '
                  </select>';
        $html .='</div>';
        return $html;
    }

    public function getSelect() {
        switch($this->_attributeCode) {
            case 'status' :
                return $this->getStatusSelect();
                break;
            case 'tax_class_id':
                return $this->getTaxClassSelect();
                break;
            case 'page_layout' :
                return $this->getPageLayoutSelect();
                break;
            case 'visibility' :
                return $this->getVisibilitySelect();
                break;
            case 'is_imported' :
                return $this->getBoolean();
                break;
			case 'country_of_manufacture':
				return $this->getCountrySelect();
				break;    
            case 'is_recurring':
            case 'options_container':
            case 'enable_googlecheckout':
            case 'gift_message_available':
            case 'custom_design':
            case 'price_view':
                return $this->getDefaultSelect();
                break;
            default:
                return $this->getDefaultSelect();
                break;
        }

    }

	
	// Media attributes
	
	public function getMediaImage(){
    	$this->_addMediaAttributes($this->_attributeCode,$this->_attributeValue,$this->_attributeLabel);
		$html ='';
		return $html;
	}
	
	public function getGallery(){
		$product = $this->getProduct();
		$html = '<table>' . $this->_getMediaImageTypesHeader();
        foreach ($product->getMediaGallery('images') as $image) {
        	$image['url'] = $product->getMediaConfig()->getMediaUrl($image['file']);
            $image['id'] = isset($image['value_id']) ? $image['value_id'] : null;
            $image['path'] = $product->getMediaConfig()->getMediaPath($image['file']);
            $html .= "<tr><td><img src='". $image['url'] . "' width='100'/></td>" . $this->_getMediaImageTypes($image) . "</tr>";
        }
        $html .= '</table>';
		
		$qty = 4;
		$count = 1;
		
		$html .= '<div id="supplier-upload"><h3 class="upload">' . $this->__('Add Images') . '</h3>';
		$html .= '<table>';
		while($count < $qty){
			$html .='<tr><td><input type="file" name="file['.$count.']"/></td></tr>';
			$count++;
		}
		$html .= '</table></div>';
		return $html;
	}
	
	private function _addMediaAttributes($attributeCode,$attributeValue,$attributeLabel){
		if(!$this->_attributeMedia){
			$mediaAttributes = array();
		} else{
			$mediaAttributes = $this->_attributeMedia;
		}		
		
		$mediaAttributes[$attributeCode]['code'] = $attributeCode;
		$mediaAttributes[$attributeCode]['value'] = $attributeValue;
		$mediaAttributes[$attributeCode]['label'] = $attributeLabel;
		
		$this->_attributeMedia = $mediaAttributes;
	}
	
	private function _getMediaImageTypesHeader(){
		$html = '<th>'. $this->__('Image') .'</th>';
		$html .= '<th>'. $this->__('Label') .'</th>';
		$html .= '<th>'. $this->__('Position') .'</th>';		
		foreach($this->_attributeMedia as $imageType){
			$html .= "<th>".$imageType['label']."</th>";	
		}
		$html .= '<th>'. $this->__('Exclude') .'</th>';
		$html .= '<th>'. $this->__('Remove') .'</th>';
		return $html;
	}

	private function _getMediaImageTypes($image){
		$html = '';
		$html .= "<td><input name='images[label][".$image['file']."]' type='text' value='".$image['label']."'/></td>";
		$html .= "<td><input name='images[position][".$image['file']."]' type='text' value='".$image['position']."'/></td>";		
		foreach($this->_attributeMedia as $imageType){
			$radioChecked = ($image['file'] == $imageType['value']) ? 'CHECKED' : '';
			$html .= "<td><input name='imagetypes[".$imageType['code']."]' value='".$image['file']."' type='radio' ".$radioChecked."/></td>";	
		}
		$checked = ($image['disabled']) ? $checked = 'CHECKED' : '';
		$html .= "<td><input name='images[disable][]' type='checkbox' value='".$image['file']."' ".$checked."/></td>";	
		$html .= "<td><input name='images[remove][]' type='checkbox' value='".$image['file']."'/></td>";
		return $html;
	}

}