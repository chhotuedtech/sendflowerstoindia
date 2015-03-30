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


class Mirasvit_Seo_Model_Object_Abstract extends Varien_Object
{
    protected $config, $_additional, $_store;

    public function _construct()
    {
        parent::_construct();
        $this->_config = Mage::getModel('seo/config');
        $this->_additional = array(
            'category' => array(),
            'product' => array(),
        );
    }

    protected function parse($str)
    {
        $uid = Mage::helper('mstcore/debug')->start();

        $storeId = false;
        if ($this->_store) {
            $storeId = $this->_store->getId();
        }

        $result = Mage::helper('seo/parse')->parse($str, $this->_parseObjects, $this->_additional, $storeId);

        Mage::helper('mstcore/debug')->end($uid, array(
            'str'           => $str,
            '_parseObjects' => $this->_parseObjects,
            '_additional'   => $this->_additional,
            'store_id'      => $storeId,
            'result'        => $result,
        ));

        return $result;
    }


    protected function setAdditionalVariable($objectName, $variableName, $value)
    {
        $this->_additional[$objectName][$variableName] = $value;
    }

    public function addAdditionalVariables($variables)
    {
        $this->_additional = array_merge_recursive($this->_additional, $variables);
    }

    public function getAdditionalVariables() {
        return $this->_additional;
    }
}
