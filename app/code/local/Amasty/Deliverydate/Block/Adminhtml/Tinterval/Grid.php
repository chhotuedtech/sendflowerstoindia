<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Block_Adminhtml_Tinterval_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('tintervalGrid');
        $this->setDefaultSort('tinterval_id');
    }
    
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('amdeliverydate/tinterval')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    
    protected function _prepareColumns()
    {
        $hlp =  Mage::helper('amdeliverydate'); 
        $this->addColumn('sorting_order', array(
          'header' => $hlp->__('Position'),
          'align'  => 'center',
          'width'  => '50px',
          'index'  => 'sorting_order',
        ));
        
        if (!Mage::app()->isSingleStoreMode()) {
            $options = Mage::getModel('adminhtml/system_store')->getStoreOptionHash();
            $options[0] = $hlp->__('All Store Views');
            ksort($options);
            $this->addColumn('store_ids', array(
                'header'     => $hlp->__('Store'),
                'index'      => 'store_ids',
                'type'       => 'options',
                'options'    => $options,
                'renderer'   => 'amdeliverydate/adminhtml_renderer_multiselect',
                'filter'     => 'amdeliverydate/adminhtml_filter_multiselect',
            ));
        }
        
        $this->addColumn('from', array(
            'header' => $hlp->__('From'),
            'index'  => 'from',
        ));
        
        $this->addColumn('to', array(
            'header' => $hlp->__('To'),
            'index'  => 'to',
        ));
        
        return parent::_prepareColumns();
    }
    
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('tinterval_id' => $row->getId()));
    }
      
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('tinterval_id');
        $this->getMassactionBlock()->setFormFieldName('tintervals');
        
        $this->getMassactionBlock()->addItem('delete', array(
             'label'   => Mage::helper('amdeliverydate')->__('Delete'),
             'url'     => $this->getUrl('*/*/massDelete'),
             'confirm' => Mage::helper('amdeliverydate')->__('Are you sure?')
        ));
        
        return $this; 
    }
}