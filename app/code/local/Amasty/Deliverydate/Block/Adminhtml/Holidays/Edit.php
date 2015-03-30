<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Block_Adminhtml_Holidays_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId   = 'holiday_id'; 
        $this->_blockGroup = 'amdeliverydate';
        $this->_controller = 'adminhtml_holidays';
        
        parent::__construct();
        
        $this->_addButton('save_and_continue', array(
                'label'     => Mage::helper('amdeliverydate')->__('Save and Continue Edit'),
                'onclick'   => 'saveAndContinueEdit()',
                'class' => 'save'
            ), 10);
        $this->_formScripts[] = " function saveAndContinueEdit() { editForm.submit($('edit_form').action + 'continue/edit') } ";        
    }

    public function getHeaderText()
    {
        $model = Mage::registry('amdeliverydate_holidays');
        if ($model->getId()) {
            return Mage::helper('amdeliverydate')->__('Edit Holiday `%s`', $this->htmlEscape($model->getDescription()));
        } else {
            return Mage::helper('amdeliverydate')->__('New Holiday');
        }
    }
}