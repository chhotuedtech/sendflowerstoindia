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


class Mirasvit_Helpdesk_Helper_Customer extends Mage_Core_Helper_Abstract
{
	public function getCustomerByEmail(Mirasvit_Helpdesk_Model_Email $email)
	{
        $customers = Mage::getModel('customer/customer')->getCollection()
            ->addAttributeToSelect('*')
            ->addFieldToFilter('email', $email->getFromEmail())
            ->addFieldToFilter('store_id', $email->getGateway()->getStoreId());
        if ($customers->count()) {
        	return $customers->getFirstItem();
        }
        $collection = Mage::getModel('sales/quote_address')->getCollection()
            // ->addFieldToSelect('*')
            ->addFieldToFilter('email', $email->getFromEmail());
		$address = $customers->getLastItem();
        if ($address->getId()) {
	        $customer = new Varien_Object();
	        $customer->setName($address->getName());
	        $customer->setEmail($address->getEmail());
	        $customer->setQuoteAddressId($address->getId());
	        return $customer;
        }
        $customer = new Varien_Object();
		if ($email->getSenderName() == '') {
            $customer->setName($email->getFromEmail());
        } else {
            $customer->setName($email->getSenderName());
        }
        $customer->setEmail($email->getFromEmail());

        return $customer;
	}

    protected function _getCustomer()
    {
        return Mage::getSingleton('customer/session')->getCustomer();
    }

	public function getCustomerByPost($params)
	{
		$customer = $this->_getCustomer();
		if ($customer->getId() > 0) {
			return $customer;
		}
		$email = $params['customer_email'];
		$name = $params['customer_name'];
        $customers = Mage::getModel('customer/customer')->getCollection()
                        ->addAttributeToSelect('*')
                        ->addAttributeToFilter('email', $email);
        if ($customers->count() > 0) {
            return $customers->getFirstItem();
        }
        $collection = Mage::getModel('sales/quote_address')->getCollection()
            // ->addFieldToSelect('*')
            ->addFieldToFilter('email', $email);
		$address = $customers->getFirstItem();
        if ($address->getId()) {
	        $customer = new Varien_Object();
	        $customer->setName($address->getName());
	        $customer->setEmail($address->getEmail());
	        $customer->setQuoteAddressId($address->getId());
	        return $customer;
        }
        $customer = new Varien_Object();
        $customer->setName($name);
        $customer->setEmail($email);

        return $customer;
	}
}