<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at http://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   Advanced SEO Suite
 * @version   1.1.1
 * @build     803
 * @copyright Copyright (C) 2014 Mirasvit (http://mirasvit.com/)
 */


class Mirasvit_Seo_Block_Adminhtml_Rewrite_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $model = Mage::registry('rewrite_data');
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fieldset = $form->addFieldset('rewrite_form', array('legend'=>Mage::helper('seo')->__('General Information')));
        $fieldset->addField('url', 'text', array(
            'label'     => Mage::helper('seo')->__('Pattern of Url or Action name'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'url',
            'value'     => $model->getUrl(),
            'note'=>
                    'Can be a full action name or a request path. Wildcard allowed.
                    Examples:<br>
                    /customer/account/login/</br>
                    /customer/account/*<br>
                    customer_account_*<br>
                    *?mode=list'
        ));

        $fieldset->addField('title', 'text', array(
            'label'     => Mage::helper('seo')->__('Title'),
            'name'      => 'title',
            'value'     => $model->getTitle()
        ));

        $fieldset->addField('description', 'textarea', array(
            'label'     => Mage::helper('seo')->__('Seo Description'),
            'name'      => 'description',
            'value'     => $model->getDescription()
        ));

        $fieldset->addField('meta_title', 'text', array(
            'label'     => Mage::helper('seo')->__('Meta Title'),
            'name'      => 'meta_title',
            'value'     => $model->getMetaTitle()
        ));

        $fieldset->addField('meta_keywords', 'textarea', array(
            'label'     => Mage::helper('seo')->__('Meta Keywords'),
            'name'      => 'meta_keywords',
            'value'     => $model->getMetaKeywords()
        ));

        $fieldset->addField('meta_description', 'textarea', array(
            'label'     => Mage::helper('seo')->__('Meta Description'),
            'name'      => 'meta_description',
            'value'     => $model->getMetaDescription()
        ));

        $fieldset->addField('is_active', 'select', array(
            'label'     => Mage::helper('seo')->__('Is Active'),
            'name'      => 'is_active',
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            'value'     => $model->getIsActive()
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('stores', 'multiselect', array(
                'label'     => Mage::helper('seo')->__('Visible In'),
                'required'  => true,
                'name'      => 'stores[]',
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(),
                'value'     => $model->getStoreId()
            ));
        }
        else {
            $fieldset->addField('stores', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
        }
        return parent::_prepareForm();
    }
}
