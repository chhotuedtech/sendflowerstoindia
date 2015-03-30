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


class Mirasvit_Seo_Block_Adminhtml_Rewrite_Grid extends Mage_Adminhtml_Block_Widget_Grid {
    public function __construct() {
        parent::__construct();
        $this->setId('rewriteGrid');
        $this->setDefaultSort('rewrite_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _getStore() {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('seo/rewrite')->getCollection();
        $store = $this->_getStore();
        if ($store->getId()) {
            $collection->addStoreFilter($store);
        }

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('rewrite_id', array(
                'header'    => Mage::helper('seo')->__('ID'),
                'align'     =>'right',
                'width'     => '50px',
                'index'     => 'rewrite_id',
        ));

        $this->addColumn('url', array(
                'header'    => Mage::helper('seo')->__('Url'),
                'align'     =>'left',
                'index'     => 'url',
        ));

        $this->addColumn('action',
                array(
                'header'    =>  Mage::helper('seo')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                        array(
                                'caption'   => Mage::helper('seo')->__('Edit'),
                                'url'       => array('base'=> '*/*/edit'),
                                'field'     => 'id'
                        ),
                        array(
                                'caption'   => Mage::helper('seo')->__('Delete'),
                                'url'       => array('base'=> '*/*/delete'),
                                'field'     => 'id'
                        )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('rewrite_id');
        $this->getMassactionBlock()->setFormFieldName('rewrite');

        $this->getMassactionBlock()->addItem('delete', array(
                'label'    => Mage::helper('seo')->__('Delete'),
                'url'      => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('seo')->__('Are you sure?')
        ));

        $statuses = array(
                1 => Mage::helper('seo')->__('Enabled'),
                2 => Mage::helper('seo')->__('Disabled'));
        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
                'label'=> Mage::helper('seo')->__('Change status'),
                'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                'additional' => array(
                        'visibility' => array(
                                'name' => 'status',
                                'type' => 'select',
                                'class' => 'required-entry',
                                'label' => Mage::helper('seo')->__('Status'),
                                'values' => $statuses
                        )
                )
        ));
        return $this;
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}