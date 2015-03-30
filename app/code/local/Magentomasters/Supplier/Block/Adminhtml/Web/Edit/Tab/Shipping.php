<?php

class Magentomasters_Supplier_Block_Adminhtml_Web_Edit_Tab_Shipping extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
	  $fieldset = $form->addFieldset('form_shipping', array('legend'=>Mage::helper('supplier')->__('Shipping Settings (First enable the supplier Shipping Method)')));	  
	  $id = $this->getRequest()->getParam('id');
      if (isset($id)) {
          $nameDisabled = true;
		  $passwd = false;
		  $supplier = mage::getModel('supplier/supplier')->load($id);
		  if($supplier->getShippingFile()){
		  		$url = Mage::helper("adminhtml")->getUrl("supplier/adminhtml_web/download", array('id'=>$id));
		  		$file = "<a href='".$url."'>".Mage::helper('supplier')->__('Download Current File '). $supplier->getShippingFile() ."</a>";
		  }

      } else {
          $nameDisabled = false;
		  $passwd = true;
		  $file = "No table rate file found";
      }
	  
	  	$fieldset->addField('shipping_method', 'select', array(
                'label'     => Mage::helper('supplier')->__('Shipping Method'),
                'name'      => 'shipping_method',
                'values'    => Mage::getModel('supplier/calculateoptions')->supplierOptions()
      ));
	
	    $fieldset->addField('shipping_cost', 'text', array(
          'label'     => Mage::helper('supplier')->__('Default shipping cost'),
          'name'      => 'shipping_cost',
		  'class'     => 'validate-number',
        ));
		
		$fieldset->addField('shipping_cost_free', 'text', array(
		  'label'     => Mage::helper('supplier')->__('Free Shipping Above'),
		  'name'      => 'shipping_cost_free',
		  'class'     => 'validate-number',
		));  

      $fieldset->addField('shipping_file', 'file', array(
        'label'     => Mage::helper('supplier')->__('Import'),
        'required'  => false,
        'name'      => 'shipping_file',
        'note'      => 'Same type of csv files as default magento table rate shipping<br/>' . $file
      )); 
	  
	  $fieldset->addField('shipping_condition', 'select', array(
                'label'     => Mage::helper('supplier')->__('Condition'),
                'name'      => 'shipping_condition',
                'values'    => Mage::getModel('adminhtml/system_config_source_shipping_tablerate')->toOptionArray() 
	  ));

      if ( Mage::getSingleton('adminhtml/session')->getWebData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getWebData());
          Mage::getSingleton('adminhtml/session')->setWebData(null);
      } elseif ( Mage::registry('web_data') ) {
          $form->setValues(Mage::registry('web_data')->getData());
      }
      return parent::_prepareForm();
  }
}