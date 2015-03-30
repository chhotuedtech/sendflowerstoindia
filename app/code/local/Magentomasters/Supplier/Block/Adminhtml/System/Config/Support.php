<?php 
class Magentomasters_Supplier_Block_Adminhtml_System_Config_Support extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface
{
    protected $_template = 'supplier/system/support.phtml';
    
    protected function _prepareLayout()
    {
        $this->getLayout()->getBlock('head')->addCss('css/supplier.css');
        return parent::_prepareLayout();
    }

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        return $this->toHtml();
    }
}