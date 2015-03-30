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



class Mirasvit_Seo_Block_Adminhtml_Catalog_Category_Tab_Seo extends Mage_Adminhtml_Block_Catalog_Form
{
    protected $_category;

    public function __construct()
    {
        parent::__construct();
        $this->setShowGlobalIcon(true);
    }

    public function getCategory()
    {
        if (!$this->_category) {
            $this->_category = Mage::registry('category');
        }
        return $this->_category;
    }

    public function _prepareLayout()
    {
        parent::_prepareLayout();
        $form = new Varien_Data_Form();
        $form->setDataObject($this->getCategory());
        $model = new Varien_Object();
        $attributes = $this->getCategory()->getAttributes();
/*********** CATEGORY *************/
        $fieldset = $form->addFieldset('category', array('legend'=>Mage::helper('seo')->__('SEO of Nested (Child) Categories')));
        $fieldset->addField('category_meta_title_tpl', 'text', array(
            'label'     => Mage::helper('seo')->__('Meta Title'),
            'name'      => 'category_meta_title_tpl',
            'value'     => $model->getCategoryMetaTitleTpl(),
            'note'=>''
        ))->setEntityAttribute($attributes['category_meta_title_tpl']);

        $fieldset->addField('category_meta_description_tpl', 'textarea', array(
            'label'     => Mage::helper('seo')->__('Meta Description'),
            'name'      => 'category_meta_description_tpl',
            'value'     => $model->getCategoryMetaDescriptionTpl(),
            'note'=>''
        ))->setEntityAttribute($attributes['category_meta_description_tpl']);
        $fieldset->addField('category_meta_keywords_tpl', 'textarea', array(
            'label'     => Mage::helper('seo')->__('Meta Keywords'),
            'name'      => 'category_meta_keywords_tpl',
            'value'     => $model->getCategoryMetaDescriptionTpl(),
            'note'=>''
        ))->setEntityAttribute($attributes['category_meta_keywords_tpl']);
        $fieldset->addField('category_title_tpl', 'text', array(
            'label'     => Mage::helper('seo')->__('H1'),
            'name'      => 'category_title_tpl',
            'value'     => $model->getCategoryMetaDescriptionTpl(),
            'note'=>''
        ))->setEntityAttribute($attributes['category_title_tpl']);
        $fieldset->addField('category_description_tpl', 'textarea', array(
            'label'     => Mage::helper('seo')->__('SEO description'),
            'name'      => 'category_description_tpl',
            'value'     => $model->getCategoryMetaDescriptionTpl(),
            'note'=>'<b>Template variables</b><br>
[category_name], [category_description], [category_url], [category_parent_name], [category_parent_url], [category_parent_parent_name], [category_page_title], [store_name], [store_url], [store_address], [store_phone], [store_email]'
        ))->setEntityAttribute($attributes['category_description_tpl']);
/*********** FILTER *************/
        $fieldset = $form->addFieldset('filter', array('legend'=>Mage::helper('seo')->__('SEO of Nested (Child) Layred Navigation')));
        $fieldset->addField('filter_meta_title_tpl', 'text', array(
            'label'     => Mage::helper('seo')->__('Meta Title'),
            'name'      => 'filter_meta_title_tpl',
            'value'     => $model->getFilterMetaTitleTpl(),
            'note'=>''
        ))->setEntityAttribute($attributes['filter_meta_title_tpl']);
        $fieldset->addField('filter_meta_description_tpl', 'textarea', array(
            'label'     => Mage::helper('seo')->__('Meta Description'),
            'name'      => 'filter_meta_description_tpl',
            'value'     => $model->getFilterMetaDescriptionTpl(),
            'note'=>''
        ))->setEntityAttribute($attributes['filter_meta_description_tpl']);
        $fieldset->addField('filter_meta_keywords_tpl', 'textarea', array(
            'label'     => Mage::helper('seo')->__('Meta Keywords'),
            'name'      => 'filter_meta_keywords_tpl',
            'value'     => $model->getFilterMetaDescriptionTpl(),
            'note'=>''
        ))->setEntityAttribute($attributes['filter_meta_keywords_tpl']);
        $fieldset->addField('filter_title_tpl', 'text', array(
            'label'     => Mage::helper('seo')->__('H1'),
            'name'      => 'filter_title_tpl',
            'value'     => $model->getFilterMetaDescriptionTpl(),
            'note'=>''
        ))->setEntityAttribute($attributes['filter_title_tpl']);
        $fieldset->addField('filter_description_tpl', 'textarea', array(
            'label'     => Mage::helper('seo')->__('SEO description'),
            'name'      => 'filter_description_tpl',
            'value'     => $model->getFilterMetaDescriptionTpl(),
            'note'=>'<b>Template variables</b><br>
[category_name],  [category_description], [category_url], [category_parent_name], [category_parent_url], <br>
[filter_selected_options], [filter_named_selected_options]<br>
[store_name], [store_url], [store_address], [store_phone], [store_email]'
        ))->setEntityAttribute($attributes['filter_description_tpl']);

/*********** PRODUCT *************/
        $fieldset = $form->addFieldset('product', array('legend'=>Mage::helper('seo')->__('SEO of Nested (Child) Products')));
        $fieldset->addField('product_meta_title_tpl', 'text', array(
            'label'     => Mage::helper('seo')->__('Meta Title'),
            'name'      => 'product_meta_title_tpl',
            'value'     => $model->getProductMetaTitleTpl(),
            'note'=>''
        ))->setEntityAttribute($attributes['product_meta_title_tpl']);
        $fieldset->addField('product_meta_description_tpl', 'textarea', array(
            'label'     => Mage::helper('seo')->__('Meta Description'),
            'name'      => 'product_meta_description_tpl',
            'value'     => $model->getProductMetaDescriptionTpl(),
            'note'=>''
        ))->setEntityAttribute($attributes['product_meta_description_tpl']);
        $fieldset->addField('product_meta_keywords_tpl', 'textarea', array(
            'label'     => Mage::helper('seo')->__('Meta Keywords'),
            'name'      => 'product_meta_keywords_tpl',
            'value'     => $model->getProductMetaDescriptionTpl(),
            'note'=>''
        ))->setEntityAttribute($attributes['product_meta_keywords_tpl']);
        $fieldset->addField('product_title_tpl', 'text', array(
            'label'     => Mage::helper('seo')->__('H1'),
            'name'      => 'product_title_tpl',
            'value'     => $model->getProductMetaDescriptionTpl(),
            'note'=>''
        ))->setEntityAttribute($attributes['product_title_tpl']);
        $fieldset->addField('product_short_description_tpl', 'textarea', array(
            'label'     => Mage::helper('seo')->__('ShortDescription'),
            'name'      => 'product_short_description_tpl',
            'value'     => $model->getProductShortDescriptionTpl(),
            'note'=>''
        ))->setEntityAttribute($attributes['product_short_description_tpl']);
        $fieldset->addField('product_full_description_tpl', 'textarea', array(
            'label'     => Mage::helper('seo')->__('Description'),
            'name'      => 'product_full_description_tpl',
            'value'     => $model->getProductFullDescriptionTpl(),
            'note'=>''
        ))->setEntityAttribute($attributes['product_full_description_tpl']);
        $fieldset->addField('product_description_tpl', 'textarea', array(
            'label'     => Mage::helper('seo')->__('SEO description'),
            'name'      => 'product_description_tpl',
            'value'     => $model->getProductMetaDescriptionTpl(),
            'note'=>'<b>Template variables</b><br>
[product_&lt;product field or attribute&gt;] (e.g. [product_name], [product_price], [product_color]) <br>
[category_name], [category_description], [category_url], [category_parent_name], [category_parent_url], <br>
[store_name], [store_url], [store_address], [store_phone], [store_email]'
        ))->setEntityAttribute($attributes['product_description_tpl']);

        $form->addValues($this->getCategory()->getData());
        $form->setFieldNameSuffix('general');
        $this->setForm($form);
    }

}
