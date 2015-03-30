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


class Mirasvit_Helpdesk_Model_Attachment extends Mage_Core_Model_Abstract
{

    protected function _construct()
    {
        $this->_init('helpdesk/attachment');
    }

    public function toOptionArray($emptyOption = false)
    {
    	return $this->getCollection()->toOptionArray($emptyOption);
    }

	/************************/

	public function getBackendUrl() {
		return Mage::helper("adminhtml")->getUrl("helpdesk/adminhtml_ticket/attachment", array('id'=>$this->getId()));
	}

	public function getUrl() {
		return Mage::getUrl("helpdesk/ticket/attachment", array('id'=>$this->getExternalId()));
	}

	public function getExternalId()
	{
        if (!$this->getData('external_id')) {
            $this->setExternalId(md5(time().Mage::helper('helpdesk/string')->generateRandNum(10)))
            		->save();
        }
        return $this->getData('external_id');
	}

    public function getName()
    {
        //in some cases attachment can have empty name.
        if ($this->getData('name')) {
            return $this->getData('name');
        }
        return 'noname';
    }
}
