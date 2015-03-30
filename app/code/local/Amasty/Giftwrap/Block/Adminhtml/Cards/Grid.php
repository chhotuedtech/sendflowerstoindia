<?php

/**
 * @copyright  Amasty (http://www.amasty.com)
 */
class Amasty_Giftwrap_Block_Adminhtml_Cards_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('cardsGrid');
        $this->setDefaultSort('cards_id');
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('amgiftwrap/cards')->getCollection()->setOrder('sort', 'DESC');
        /*
         * just do something here with collection
         * apply filters \ sorting or etc
        */

        $this->setDefaultSort('sort');
        $this->setDefaultDir('desc');
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {

        $hlp = Mage::helper('amgiftwrap');
        $this->addColumn('cards_id', array(
                'header' => $hlp->__('ID'),
                'align'  => 'right',
                'width'  => '50px',
                'index'  => 'cards_id',
            )
        );

        $this->addColumn('name', array(
                'header' => $hlp->__('Card Name'),
                'index'  => 'name',
            )
        );
        $this->addColumn('description', array(
                'header'   => $hlp->__('Description'),
                'index'    => 'description',
                'renderer' => 'amgiftwrap/renderer_description',
            )
        );
        $this->addColumn('sort', array(
                'header' => $hlp->__('Position'),
                'index'  => 'sort',
                'width'  => '40px',
            )
        );
        $this->addColumn('price', array(
                'header'        => $hlp->__('Price'),
                'type'          => 'currency',
                'width'         => '100px',
                'currency_code' => (string)Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
                'index'         => 'price'
            )
        );
        $this->addColumn('enabled', array(
                'header'  => $hlp->__('Enabled'),
                'align'   => 'center',
                'width'   => '80px',
                'index'   => 'enabled',
                'type'    => 'options',
                'options' => array(
                    '0' => $this->__('No'),
                    '1' => $this->__('Yes'),
                ),
            )
        );

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('cards_id');
        $this->getMassactionBlock()->setFormFieldName('ids');

        $actions = array(
            'massEnable'  => 'Enable',
            'massDisable' => 'Disable',
            'massDelete'  => 'Delete',
        );
        foreach ($actions as $code => $label) {
            $this->getMassactionBlock()->addItem($code, array(
                    'label'   => Mage::helper('amgiftwrap')->__($label),
                    'url'     => $this->getUrl('*/*/' . $code),
                    'confirm' => ($code == 'massDelete' ? Mage::helper('amgiftwrap')->__('Are you sure?') : null),
                )
            );
        }

        return $this;
    }
}