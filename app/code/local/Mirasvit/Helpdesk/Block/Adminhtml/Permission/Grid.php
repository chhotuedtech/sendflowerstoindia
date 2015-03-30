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
 * @package   Help Desk MX
 * @version   1.0.7
 * @build     907
 * @copyright Copyright (C) 2014 Mirasvit (http://mirasvit.com/)
 */


class Mirasvit_Helpdesk_Block_Adminhtml_Permission_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('grid');
        $this->setDefaultSort('permission_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('helpdesk/permission')
            ->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {

        $this->addColumn('role_id', array(
            'header'    => Mage::helper('helpdesk')->__('Role'),
//          'align'     => 'right',
//          'width'     => '50px',
            'index'     => 'role_id',
            'filter_index'     => 'main_table.role_id',
            'type'      => 'options',
            'options'   => Mage::helper('helpdesk')->getAdminRoleOptionArray(),
            'frame_callback'   => array($this, '_roleFormat'),
            )
        );
        $this->addColumn('departments', array(
            'header'    => Mage::helper('helpdesk')->__('Has Access to Tickets of Departments'),
//          'align'     => 'right',
//          'width'     => '50px',
            'index'     => 'departments',
            'type'      => 'text',
            'frame_callback'   => array($this, '_departmentsFormat'),
            )
        );
        $this->addColumn('is_ticket_remove_allowed', array(
            'header'    => Mage::helper('helpdesk')->__('Can Remove Tickets'),
//          'align'     => 'right',
//          'width'     => '50px',
            'index'     => 'is_ticket_remove_allowed',
            'filter_index'     => 'main_table.is_ticket_remove_allowed',
            'type'      => 'options',
            'options'   => array(
                0 => $this->__('No'),
                1 => $this->__('Yes')
            ),
            )
        );
        return parent::_prepareColumns();
    }

    public function _roleFormat($renderedValue, $row, $column, $isExport)
    {
        if (!$renderedValue) {
            $renderedValue = $this->__('All Roles');
        }
        return $renderedValue;
    }

    public function _departmentsFormat($renderedValue, $row, $column, $isExport)
    {
        $row->loadDepartmentIds();
        $values = array();
        foreach ($row->getDepartmentIds() as $id) {
            if ($id) {
                $department = Mage::getModel('helpdesk/department')->load($id);
                $values[] = $department->getName();
            } else {
                $values[] = $this->__('All Departments');
            }
        }
        return implode(', ', $values);
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('permission_id');
        $this->getMassactionBlock()->setFormFieldName('permission_id');
        $statuses = array(
                array('label'=>'', 'value'=>''),
                array('label'=>$this->__('Disabled'), 'value'=> 0),
                array('label'=>$this->__('Enabled'), 'value'=> 1),
        );
        $this->getMassactionBlock()->addItem('delete', array(
            'label'    => Mage::helper('helpdesk')->__('Delete'),
            'url'      => $this->getUrl('*/*/massDelete'),
            'confirm'  => Mage::helper('helpdesk')->__('Are you sure?')
        ));
        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /************************/

}