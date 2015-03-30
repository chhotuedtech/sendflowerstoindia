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


class Mirasvit_Helpdesk_Helper_Permission extends Mage_Core_Helper_Abstract
{
    public function isDepartmentAccessAllowed($department)
    {
        return false;
    }

    public function setTicketRestrictions($ticketCollection)
    {
        if (!$permission = $this->getPermission()) {
            $ticketCollection->addFieldToFilter('department_id', -1);
            return $ticketCollection;
        }
        $departmentIds = $permission->getDepartmentIds();

        if (in_array(0, $departmentIds)) {
            return $ticketCollection;
        }
        $ticketCollection->addFieldToFilter('department_id', $departmentIds);

        return $ticketCollection;
    }

    public function setDepartmentRestrictions($departmentCollection)
    {
        if (!$permission = $this->getPermission()) {
            $departmentCollection->addFieldToFilter('department_id', -1);

            return $departmentCollection;
        }

        $departmentIds = $permission->getDepartmentIds();
        if (in_array(0, $departmentIds)) {
            return $departmentCollection;
        }
        $departmentCollection->addFieldToFilter('department_id', $departmentIds);

        return $departmentCollection;
    }

    public function getPermission()
    {
        $user = Mage::getSingleton('admin/session')->getUser();
        $permissions = Mage::getModel('helpdesk/permission')->getCollection()
            ->addFieldToFilter('role_id',
                array(
                    array(
                        'attribute' => 'role_id',
                        'null'      => 'this_value_doesnt_matter'
                    ),
                    array(
                        'attribute' => 'role_id',
                        'in'        => $user->getRoles()
                    )
                )
            );

        if ($permissions->count()) {
            $permission = $permissions->getFirstItem();
            $permission->loadDepartmentIds();
            return $permission;
        }
    }

    public function checkReadTicketRestrictions($ticket)
    {
        $allow = false;
        if ($permission = $this->getPermission()) {
            $departmentIds = $permission->getDepartmentIds();
            if (in_array(0, $departmentIds)) {
                $allow = true;
            } else {
                if (in_array($ticket->getDepartmentId(), $departmentIds)) {
                    $allow = true;
                }
            }
        }
        if (!$allow) {
            echo $this->__('You don\'t have permissions to read this ticket. Please, contact your administrator.');
            die;
        }
    }

    public function isTicketRemoveAllowed()
    {
        if ($permission = $this->getPermission()) {
            return $permission->getIsTicketRemoveAllowed();
        }

        return false;
    }
}