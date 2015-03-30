<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Block_Adminhtml_Sliders_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId( 'slidersGrid' );
        $this->setDefaultSort( 'id' );
        $this->setDefaultDir( 'ASC' );
        $this->setSaveParametersInSession( true );
        $this->setUseAjax( true );
    }
    
    protected function _prepareCollection()
    {
        $collection = Mage::getModel( 'dynamicslideshow/sliders' )->getCollection();
        $this->setCollection( $collection );
        return parent::_prepareCollection();
    }
    
    protected function _prepareColumns()
    {
        $this->addColumn( 'id', array(
             'header' => Mage::helper( 'dynamicslideshow' )->__( 'ID' ),
            'align' => 'right',
            'width' => '50px',
            'index' => 'id' 
        ) );
        
        $this->addColumn( 'title', array(
             'header' => Mage::helper( 'dynamicslideshow' )->__( 'Title' ),
            'align' => 'left',
            'index' => 'title' 
        ) );
        
        $this->addColumn( 'status', array(
            
             'header' => Mage::helper( 'dynamicslideshow' )->__( 'Status' ),
            'align' => 'left',
            'width' => '80px',
            'index' => 'status',
            'type' => 'options',
            'options' => Mage::getModel( 'dynamicslideshow/sliders' )->getOptStatus() 
        ) );
        
        $this->addColumn( 'preview', array(
             'header' => Mage::helper( 'dynamicslideshow' )->__( 'Preview' ),
            'type' => 'action',
            'getter' => 'getId',
            'width' => '80px',
            'actions' => array(
                 array(
                     'caption' => Mage::helper( 'dynamicslideshow' )->__( 'Preview' ),
                    'field' => 'id',
                    'target' => 'blank',
                    'url' => array(
                         'base' => 'dynamicslideshow/index/preview' 
                    ) 
                ) 
            ),
            'filter' => false,
            'sortable' => false 
        ) );
        
        $this->addColumn( 'action', array(
             'header' => Mage::helper( 'dynamicslideshow' )->__( 'Action' ),
            'width' => '100',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                 array(
                     'caption' => Mage::helper( 'dynamicslideshow' )->__( 'Edit' ),
                    'url' => array(
                         'base' => '*/*/edit' 
                    ),
                    'field' => 'id',
                    'class' => 'scalable ' 
                ) 
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'is_system' => true,
            'class' => 'scalable' 
        ) );
        return parent::_prepareColumns();
    }
    
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField( 'id' );
        $this->getMassactionBlock()->setFormFieldName( 'dynamicslideshow' );
        
        $this->getMassactionBlock()->addItem( 'delete', array(
             'label' => Mage::helper( 'dynamicslideshow' )->__( 'Delete' ),
            'url' => $this->getUrl( '*/*/massDelete' ),
            'confirm' => Mage::helper( 'dynamicslideshow' )->__( 'Are you sure?' ) 
        ) );
        
        $statuses = Mage::getModel( 'dynamicslideshow/sliders' )->getOptStatus();
        
        array_unshift( $statuses, array(
             'label' => '',
            'value' => '' 
        ) );
        $this->getMassactionBlock()->addItem( 'status', array(
             'label' => Mage::helper( 'dynamicslideshow' )->__( 'Change status' ),
            'url' => $this->getUrl( '*/*/massStatus', array(
                 '_current' => true 
            ) ),
            'additional' => array(
                 'visibility' => array(
                     'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper( 'dynamicslideshow' )->__( 'Status' ),
                    'values' => $statuses 
                ) 
            ) 
        ) );
        return $this;
    }
    
    
    public function getRowUrl( $row )
    {
        return $this->getUrl( '*/*/edit', array(
             'id' => $row->getId() 
        ) );
    }
    
    public function getGridUrl()
    {
        return $this->getUrl( '*/*/grid', array(
             '_current' => true 
        ) );
    }
    
    
}