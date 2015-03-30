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


class Mirasvit_SeoAutolink_Block_Adminhtml_Link_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $model  = Mage::registry('current_model');

        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getData('action'),
            'method'    => 'post',
            'enctype'   => 'multipart/form-data',
        ));

        $fieldset = $form->addFieldset('general_fieldset', array(
            'legend'    => Mage::helper('seoautolink')->__('General'),
            'class'     => 'fieldset-wide'
        ));

        if ($model->getId()) {
            $fieldset->addField('link_id', 'hidden', array(
                'name'      => 'link_id',
                'value'     => $model->getId(),
            ));
        }

        $fieldset->addField('keyword', 'text', array(
            'name'      => 'keyword',
            'label'     => Mage::helper('seoautolink')->__('Keyword'),
            'required'  => true,
            'value'     => $model->getKeyword(),
        ));

        $fieldset->addField('url', 'text', array(
            'name'      => 'url',
            'label'     => Mage::helper('seoautolink')->__('URL'),
            'required'  => true,
            'value'     => $model->getUrl(),
        ));

        $fieldset->addField('url_target', 'select', array(
            'label'     => Mage::helper('seoautolink')->__('URL Target'),
            'name'      => 'url_target',
            'values'    => Mage::getSingleton('seoautolink/config_source_urltarget')->toOptionArray(),
            'value'     => $model->getUrlTarget()
        ));

        $fieldset->addField('url_title', 'text', array(
            'name'      => 'url_title',
            'label'     => Mage::helper('seoautolink')->__('URL Title'),
            'required'  => false,
            'value'     => $model->getUrlTitle(),
        ));

        $fieldset->addField('is_nofollow', 'select', array(
            'name'  	=> 'is_nofollow',
            'label' 	=> Mage::helper('seoautolink')->__('Nofollow'),
            'options'	=> array('1' => Mage::helper('seoautolink')->__('Yes'), '0' => Mage::helper('seoautolink')->__('No')),
        	'value'     => $model->getIsNofollow(),
        ));

        $fieldset->addField('max_replacements', 'text', array(
            'name'  	=> 'max_replacements',
            'label' 	=> Mage::helper('seoautolink')->__('Number of substitutions'),
        	'value'     => (int)$model->getMaxReplacements(),
        ));

        $fieldset->addField('sort_order', 'text', array(
            'name'      => 'sort_order',
            'label'     => Mage::helper('seoautolink')->__('Sort order'),
            'value'     => (int)$model->getSortOrder(),
        ));

        $fieldset->addField('occurence', 'select', array(
            'name'  	=> 'occurence',
            'label' 	=> Mage::helper('seoautolink')->__('Occurence'),
            'values'	=> Mage::getSingleton('seoautolink/config_source_occurence')->toOptionArray(),
        	'value'     => $model->getOccurence(),
        ));

        $fieldset->addField('is_active', 'select', array(
            'name'  	=> 'is_active',
            'label' 	=> Mage::helper('seoautolink')->__('Status'),
            'options'	=> array('1' => Mage::helper('seoautolink')->__('Active'), '0' => Mage::helper('seoautolink')->__('Inactive')),
        	'value'     => $model->getIsActive(),
        ));

        $fieldset->addField('active_from', 'date', array(
            'label'     => Mage::helper('seoautolink')->__('Active From'),
            'required'  => false,
            'name'      => 'active_from',
            'value'     => $model->getActiveFrom(),
            'image' 	=> $this->getSkinUrl('images/grid-cal.gif'),
        	'format'	=> 'dd.MM.yyyy'
        ));

        $fieldset->addField('active_to', 'date', array(
            'label'     => Mage::helper('seoautolink')->__('Active To'),
            'required'  => false,
            'name'      => 'active_to',
            'value'     => $model->getActiveTo(),
            'image' 	=> $this->getSkinUrl('images/grid-cal.gif'),
        	'format'	=> 'dd.MM.yyyy'
        ));

       /**
         * Check is single store mode
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $field = $fieldset->addField('store_id', 'multiselect', array(
                'name'      => 'store_ids[]',
                'label'     => Mage::helper('seoautolink')->__('Visible in Store View'),
                'title'     => Mage::helper('seoautolink')->__('Visible in Store View'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            	'value'		=> $model->getStoreIds()
            ));
        }
        else {
            $fieldset->addField('store_id', 'hidden', array(
                'name'      => 'store_ids[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
            $model->setStoreId(Mage::app()->getStore(true)->getId());
        }


        $form->setAction($this->getUrl('*/*/save'));
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

}