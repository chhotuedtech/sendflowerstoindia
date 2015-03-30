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


class Mirasvit_SeoAutolink_Block_Adminhtml_Link_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct ()
    {
        parent::__construct();
        $this->_objectId = 'link_id';
        $this->_controller = 'adminhtml_link';
        $this->_blockGroup = 'seoautolink';
        return $this;
    }

    public function getHeaderText ()
    {
        if (Mage::registry('current_model') && Mage::registry('current_model')->getId()) {
            return Mage::helper('seoautolink')->__("Edit '%s'", $this->htmlEscape(Mage::registry('current_model')->getKeyword()));
        } else {
            return Mage::helper('seoautolink')->__('Add New Link');
        }
    }
}