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


class Mirasvit_Seo_Block_Adminhtml_Rewrite extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_rewrite';
        $this->_blockGroup = 'seo';
        $this->_headerText = Mage::helper('seo')->__('Rewrite Manager');
        parent::__construct();

        $this->setTemplate('seo/rewrite.phtml');
    }

    protected function _prepareLayout()
    {
        $this->setChild('add_new_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                'label'     => Mage::helper('seo')->__('Add Rewrite'),
                'onclick'   => "setLocation('" . $this->getUrl('*/*/add') . "')",
                'class'     => 'add'
            ))
        );
        /**
         * Display store switcher if system has more one store
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $this->setChild('store_switcher',
                $this->getLayout()->createBlock('adminhtml/store_switcher')
                    ->setUseConfirm(false)
                    ->setSwitchUrl($this->getUrl('*/*/*', array('store'=> null)))
            );
        }
        $this->setChild('grid', $this->getLayout()->createBlock('seo/adminhtml_rewrite_grid', 'rewrite.grid'));
        return parent::_prepareLayout();
    }

    public function getAddNewButtonHtml()
    {
        return $this->getChildHtml('add_new_button');
    }

    public function getGridHtml()
    {
        return $this->getChildHtml('grid');
    }

    public function getStoreSwitcherHtml()
    {
        return $this->getChildHtml('store_switcher');
    }
}