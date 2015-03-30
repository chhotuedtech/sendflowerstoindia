<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Block_Adminhtml_Dinterval_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('dintervalGrid');
        $this->setDefaultSort('dinterval_id');
    }
    
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('amdeliverydate/dinterval')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    
    protected function _prepareColumns()
    {
        $hlp =  Mage::helper('amdeliverydate'); 
        $this->addColumn('dinterval_id', array(
          'header' => $hlp->__('ID'),
          'align'  => 'right',
          'width'  => '50px',
          'index'  => 'dinterval_id',
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
        
        $format = explode('/', Mage::getStoreConfig('amdeliverydate/general/format'));
        $pointer = 0;
        foreach ($format as $part) {
            switch ($part) {
                case 'Y':
                    $this->addColumn('from_year', array(
                        'header'  => $hlp->__('From Year'),
                        'index'   => 'from_year',
                        'width'   => '100px',
                        'align'   => $hlp->getAlignForColumn($pointer),
                        'type'    => 'options',
                        'options' => $hlp->getYears(0, true, 'dinterval'),
                    ));
                    $pointer++;
                    break;
                case 'M':
                    $this->addColumn('from_month', array(
                        'header'  => $hlp->__('From Month'),
                        'index'   => 'from_month',
                        'width'   => '150px',
                        'align'   => $hlp->getAlignForColumn($pointer),
                        'type'    => 'options',
                        'options' => $hlp->getMonths(),
                    ));
                    $pointer++;
                    break;
                case 'D':
                    $this->addColumn('from_day', array(
                        'header' => $hlp->__('From Day'),
                        'index'  => 'from_day',
                        'width'  => '50px',
                        'align'  => $hlp->getAlignForColumn($pointer),
                    ));
                    $pointer++;
                    break;
            }
        }
        
        $pointer = 0;
        foreach ($format as $part) {
            switch ($part) {
                case 'Y':
                    $this->addColumn('to_year', array(
                        'header'  => $hlp->__('To Year'),
                        'index'   => 'to_year',
                        'width'   => '100px',
                        'align'   => $hlp->getAlignForColumn($pointer),
                        'type'    => 'options',
                        'options' => $hlp->getYears(0, true, 'dinterval', 'to_year'),
                    ));
                    $pointer++;
                    break;
                case 'M':
                    $this->addColumn('to_month', array(
                        'header'  => $hlp->__('To Month'),
                        'index'   => 'to_month',
                        'width'   => '150px',
                        'align'   => $hlp->getAlignForColumn($pointer),
                        'type'    => 'options',
                        'options' => $hlp->getMonths(),
                    ));
                    $pointer++;
                    break;
                case 'D':
                    $this->addColumn('to_day', array(
                        'header' => $hlp->__('To Day'),
                        'index'  => 'to_day',
                        'width'  => '50px',
                        'align'  => $hlp->getAlignForColumn($pointer),
                    ));
                    $pointer++;
                    break;
            }
        }
        
        $this->addColumn('description', array(
            'header' => $hlp->__('Description'),
            'index'  => 'description',
        ));
        
        return parent::_prepareColumns();
    }
    
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('dinterval_id' => $row->getId()));
    }
      
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('dinterval_id');
        $this->getMassactionBlock()->setFormFieldName('dintervals');
        
        $this->getMassactionBlock()->addItem('delete', array(
             'label'   => Mage::helper('amdeliverydate')->__('Delete'),
             'url'     => $this->getUrl('*/*/massDelete'),
             'confirm' => Mage::helper('amdeliverydate')->__('Are you sure?')
        ));
        
        return $this; 
    }
}