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



class Mirasvit_Seo_Block_Adminhtml_Rewrite_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('rewrite_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('seo')->__('Rewrite Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('general_section', array(
            'label'     => Mage::helper('seo')->__('General Information'),
            'title'     => Mage::helper('seo')->__('General Information'),
            'content'   => $this->getLayout()->createBlock('seo/adminhtml_rewrite_edit_tab_form')->toHtml(),
        ));
        return parent::_beforeToHtml();
    }
}