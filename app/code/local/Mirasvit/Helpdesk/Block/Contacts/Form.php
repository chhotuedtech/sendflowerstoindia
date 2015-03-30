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



class Mirasvit_Helpdesk_Block_Contacts_Form extends Mage_Core_Block_Template
{
	public function __construct() {

		if (Mage::getSingleton('helpdesk/config')->getGeneralContactUsIsActive()) {
			$this->setTemplate('mst_helpdesk/contacts/form.phtml');
		} else {
			$this->setTemplate('contacts/form.phtml');
		}
		parent::__construct();
	}

    public function getConfig()
    {
        return Mage::getSingleton('helpdesk/config');
    }

    public function getFormAction()
    {
        return Mage::getUrl('helpdesk/contacts/post');
    }

    public function getFrontendIsAllowPriority()
    {
        return $this->getConfig()->getFrontendIsAllowPriority();
    }

    public function getFrontendIsAllowDepartment()
    {
        return $this->getConfig()->getFrontendIsAllowDepartment();
    }

    public function getPriorityCollection()
    {
        return Mage::getModel('helpdesk/priority')->getCollection()
                ->setOrder('sort_order', 'asc');
    }

    public function getDepartmentCollection()
    {
        return Mage::getModel('helpdesk/department')->getCollection()
                ->setOrder('sort_order', 'asc');
    }

    public function getCustomFields()
    {
        $collection = Mage::helper('helpdesk/field')->getContactFormCollection();
        return $collection;
    }

    public function getInputHtml($field)
    {
        return Mage::helper('helpdesk/field')->getInputHtml($field);
    }
}

