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


class Mirasvit_Helpdesk_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function toAdminUserOptionArray($emptyOption = false)
    {
        $arr = Mage::getModel('admin/user')->getCollection()->toArray();
        $result = array();
        foreach ($arr['items'] as $value) {
            $result[] = array('value'=>$value['user_id'], 'label' => $value['firstname'].' '.$value['lastname']);
        }
        if ($emptyOption) {
            array_unshift($result, array('value' => 0, 'label' => Mage::helper('helpdesk')->__('-- Please Select --')));
        }

        return $result;
    }

    public function getAdminUserOptionArray($emptyOption = false)
    {
        $arr = Mage::getModel('admin/user')->getCollection()->toArray();
        $result = array();
        foreach ($arr['items'] as $value) {
            $result[$value['user_id']] = $value['firstname'].' '.$value['lastname'];
        }
        if ($emptyOption) {
            $result[0] = Mage::helper('helpdesk')->__('-- Please Select --');
        }

        return $result;
    }

    // public function getCoreStoreOptionArray() {
    //     $arr = Mage::getModel('core/store')->getCollection()->toArray();
    //     foreach ($arr['items'] as $value) {
    //         $result[$value['store_id']] = $value['name'];
    //     }
    //     return $result;
    // }

    public function toAdminRoleOptionArray($emptyOption = false)
    {
        $arr = Mage::getModel('admin/role')->getCollection()
                    ->addFieldToFilter('role_type', 'G')
                    ->toArray();
        $result = array();
        foreach ($arr['items'] as $value) {
            $result[] = array('value'=>$value['role_id'], 'label' => $value['role_name']);
        }
        if ($emptyOption === true) {
            $emptyOption = '-- Please Select --';
        }
        if ($emptyOption) {
            array_unshift($result, array('value' => 0, 'label' => Mage::helper('helpdesk')->__($emptyOption)));
        }

        return $result;
    }

    public function getAdminRoleOptionArray($emptyOption = false)
    {
        $arr = Mage::getModel('admin/role')->getCollection()
                    ->addFieldToFilter('role_type', 'G')
                    ->toArray();
        $result = array();
        foreach ($arr['items'] as $value) {
            $result[$value['role_id']] = $value['role_name'];
        }
        if ($emptyOption === true) {
            $emptyOption = '-- Please Select --';
        }
        if ($emptyOption) {
            $result[0] = Mage::helper('helpdesk')->__($emptyOption);
        }

        return $result;
    }

    /************************/

    public function getCoreStoreOptionArray($emptyOption = false)
    {
        $arr = Mage::getModel('core/store')->getCollection()->toArray();
        foreach ($arr['items'] as $value) {
            $result[$value['store_id']] = $value['name'];
        }
        if ($emptyOption) {
            $result[0] = Mage::helper('helpdesk')->__('-- Please Select --');
        }

        return $result;
    }

    public function getAdminOwnerOptionArray($emptyOption = false)
    {
        $result = array();
        if ($emptyOption) {
            $result['0_0'] = Mage::helper('helpdesk')->__('-- Please Select --');
        }
        $collection = Mage::getModel('helpdesk/department')->getCollection()
                        ->addFieldToFilter('is_active', true)
                        ->setOrder('sort_order', 'asc');
        foreach($collection as $department) {
            $result[$department->getId().'_0'] = $department->getName();
            foreach($department->getUsers() as $user) {
                $result[$department->getId().'_'.$user->getId()] = '- '.$user->getFirstname().' '.$user->getLastname();
            }
        }

        return $result;
    }

    public function getCustomerArray($q = false, $customerId = false, $addressId = false)
    {
        $firstnameId = Mage::getModel('eav/entity_attribute')->loadByCode(1, 'firstname')->getId();
        $lastnameId  = Mage::getModel('eav/entity_attribute')->loadByCode(1, 'lastname')->getId();

        $collection = Mage::getModel('customer/customer')
            ->getCollection()
            ->addAttributeToSelect('*');

        $collection->getSelect()->limit(20);

        if ($q) {
            $resource = Mage::getSingleton('core/resource');
            $collection->getSelect()
                ->joinLeft(
                    array('varchar1' => $resource->getTableName('customer/entity').'_varchar'),
                    'e.entity_id = varchar1.entity_id and varchar1.attribute_id = '.$firstnameId,
                    array('firstname' => 'varchar1.value')
                )
                ->joinLeft(
                    array('varchar2' => $resource->getTableName('customer/entity').'_varchar'),
                    'e.entity_id = varchar2.entity_id and varchar2.attribute_id = '.$lastnameId,
                    array('lastname' => 'varchar2.value')
                )->joinLeft(
                    array('orders' => $resource->getTableName('sales/order')),
                    'e.entity_id = orders.customer_id',
                    array('order' => 'orders.increment_id')
                )->group('e.entity_id');
            $search = Mage::getModel('helpdesk/search');
            $search->setSearchableCollection($collection);
            $search->setSearchableAttributes(array(
                'e.entity_id'  => 0,
                'e.email'      => 0,
                'firstname'    => 0,
                'lastname'     => 0,
                'order' => 0,
            ));
            $search->setPrimaryKey('entity_id');
            $search->joinMatched($q, $collection, 'e.entity_id');
        }

        if ($customerId !== false) {
            $collection->addFieldToFilter('entity_id', $customerId);
        }

        $result = array();
        foreach ($collection as $customer) {
            $result[] = array(
                'id'    => $customer->getId(),
                'name'  => $customer->getFirstname().' '.$customer->getLastname().' ('.$customer->getEmail().')',
                'email' => $customer->getEmail()
            );
        }

        if (Mage::getVersion() <= '1.4.1.1') {
            //unregstered search
            $collection = Mage::getModel('sales/quote_address')->getCollection();
            $collection
                ->getSelect()
                ->group('email')
                ->limit(20);
            if ($q) {
                $search = Mage::getModel('helpdesk/search');
                $search->setSearchableCollection($collection);
                $search->setSearchableAttributes(array(
                    'email'     => 0,
                    'firstname' => 0,
                    'lastname'  => 0,
                ));
                $search->setPrimaryKey('address_id');
                $search->joinMatched($q, $collection, 'address_id');
            }
            if ($addressId !== false) {
                $collection->addFieldToFilter('address_id', $addressId);
            }

            foreach ($collection as $address) {
                $result[] = array(
                    'id'       => 'address_'.$address->getId(),
                    'order_id' => $address->getOrderId(),
                    'name'     => $address->getFirstname().' '.$address->getLastname().' ('.$address->getEmail().') [unregstered]',
                    'email'    => $address->getEmail()
                );
            }
            // print_r($result);die;
        } else {
            //unregstered search
            $collection = Mage::getModel('sales/order_address')->getCollection();
            $collection
                ->getSelect()
                ->group('email')
                ->limit(20);
            if ($q) {
                $search = Mage::getModel('helpdesk/search');
                $search->setSearchableCollection($collection);
                $search->setSearchableAttributes(array(
                    'email'     => 0,
                    'firstname' => 0,
                    'lastname'  => 0,
                ));
                $search->setPrimaryKey('entity_id');
                $search->joinMatched($q, $collection, 'main_table.entity_id');
            }
            if ($addressId !== false) {
                $collection->addFieldToFilter('main_table.entity_id', $addressId);
            }


            foreach ($collection as $address) {
                if (!$address->getEmail()) {
                    continue;
                }
                $result[] = array(
                    'id'       => 'address_'.$address->getId(),
                    'order_id' => $address->getOrderId(),
                    'name'     => $address->getFirstname().' '.$address->getLastname().' ('.$address->getEmail().') [unregstered]',
                    'email'    => $address->getEmail()
                );
            }
        }

        return $result;
    }

    public function getOrderArray($customerEmail, $customerId = false)
    {
        $orders = array();
        $collection = Mage::getModel('sales/order')->getCollection()
            ->addAttributeToSelect('*')
            ->setOrder('created_at', 'desc')
            ;
        if ($customerId) {
            $collection->addFieldToFilter(
                array('customer_email','customer_id'), array($customerEmail, $customerId)
            );
        } else {
            $collection->addFieldToFilter('customer_email', $customerEmail);
        }
        foreach($collection as $order) {
            $orders[] = array(
                'id'   => $order->getId(),
                'name' => $this->getOrderLabel($order)
            );
        }

        return $orders;
    }

    public function findCustomer($q)
    {
        $customers = $this->getCustomerArray($q);
        foreach ($customers as $key => $customer) {
            $customerId = false;
            if (isset($customer['id'])) {
                $customerId = (int)$customer['id'];
            }
            $orders = $this->getOrderArray($customer['email'], $customerId);
            array_unshift($orders, array('id' => 0, 'name' => $this->__('Unassigned')));
            $customers[$key]['orders'] = $orders;
        }

        return $customers;
    }

	public function saveAttachments($message)
    {
		if (!isset($_FILES['attachment']['name'])) {
			return;
		}
		$i = 0;
		foreach($_FILES['attachment']['name'] as $name) {
            // echo $name;
			if ($name == '') {
				continue;
			}
            //@tofix - need to check for max upload size and alert error
			$body = file_get_contents(addslashes($_FILES['attachment']['tmp_name'][$i]));

			$attachment = Mage::getModel('helpdesk/attachment')
				->setName($name)
				->setType(strtoupper($_FILES['attachment']['type'][$i]))
				->setSize($_FILES['attachment']['size'][$i])
				->setBody($body)
				->setMessageId($message->getId())
				->save();
			$i++;
		}
	}

    public function getOrderLabel($order, $url = false)
    {
        if (!is_object($order)) {
            $order = Mage::getModel('sales/order')->load($order);
        }
        $res = "#{$order->getRealorderId()}";
        if ($url) {
            $res = "<a href='{$url}' target='_blank'>$res</a>";
        }
        $res .= Mage::helper('helpdesk')->__(" at %s (%s) - %s",
            Mage::helper('core')->formatDate($order->getCreatedAt(), 'medium'),
            strip_tags($order->formatPrice($order->getGrandTotal())),
            Mage::helper('helpdesk')->__(ucwords($order->getStatus()))
        );

        return $res;
    }

    public function getHistoryHtml($ticket)
    {
        $block = Mage::app()->getLayout()->createBlock('helpdesk/email_history')
            ->setTemplate('mst_helpdesk/email/history.phtml')
            ->setTicket($ticket);
        return $block->toHtml();
    }
}
