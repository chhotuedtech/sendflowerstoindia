<?php

/**
 * @author Amasty
 */
class Amasty_Giftwrap_Block_Adminhtml_Cards_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $model = Mage::registry('amgiftwrap_cards');
        $form = new Varien_Data_Form(array(
                'id'      => 'edit_form',
                'action'  => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
                'method'  => 'post',
                'enctype' => 'multipart/form-data',
            )
        );

        $form->setUseContainer(true);
        $this->setForm($form);
        $hlp = Mage::helper('amgiftwrap');

        $fldInfo = $form->addFieldset('amgiftwrap_info', array('legend' => $hlp->__('Cards info')));

        $fldInfo->addField('enabled', 'select', array(
                'label'    => $hlp->__('Enabled'),
                'title'    => $hlp->__('Enabled'),
                'name'     => 'enabled',
                'required' => true,
                'options'  => array(
                    '0' => $this->__('No'),
                    '1' => $this->__('Yes'),
                ),
            )
        );
        $fldInfo->addField('sort', 'text', array(
                'label' => $hlp->__('Position'),
                'name'  => 'sort',
            )
        );
        $fldInfo->addField('name', 'text', array(
                'label'    => $hlp->__('Name'),
                'class'    => 'required-entry',
                'required' => true,
                'name'     => 'name',
            )
        );
        $fldInfo->addField('description', 'textarea', array(
                'label' => $hlp->__('Description'),
                'name'  => 'description',
            )
        );
        $fldInfo->addField('stores', 'multiselect', array(
                'name'     => 'stores[]',
                'label'    => $hlp->__('Store View'),
                'title'    => $hlp->__('Store View'),
                'required' => true,
                'values'   => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            )
        );
        $fldInfo->addField('imgage', 'file', array(
                'label'              => $hlp->__('Image'),
                'name'               => 'image',
                'after_element_html' => $this->getImageHtml($model->getImage()),
            )
        );
        $fldInfo->addField('price', 'text', array(
                'label'    => $hlp->__('Price'),
                'class'    => 'required-entry',
                'required' => true,
                'name'     => 'price',
            )
        );

        //set form values
        $data = Mage::getSingleton('adminhtml/session')->getFormData();
        $model = Mage::registry('amgiftwrap_cards');
        if ($data) {
            $form->setValues($data);
            Mage::getSingleton('adminhtml/session')->setFormData(null);
        } elseif ($model) {
            $form->setValues($model->getData());
        }

        return parent::_prepareForm();
    }


    protected function getImageHtml($img)
    {
        $html = '';
        if ($img) {
            $html .= '<p style="margin-top: 5px">';
            $html .= '<img src="' . Mage::helper('amgiftwrap')->getImageThumbnail($img, 'cards') . '" />';
            $html .= '<br/><input type="checkbox" value="1" name="remove_image"/> ' . Mage::helper('amgiftwrap')->__('Remove');
            $html .= '<input type="hidden" value="' . $img . '" name="old_image"/>';
            $html .= '</p>';
        }

        return $html;
    }
}