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


class Mirasvit_Seo_Model_Object_Wrapper_Filter extends Varien_Object
{
    public function _construct()
    {
        parent::_construct();
        $options = array();
        $names = array();
        foreach ($this->getActiveFilters() as $filter) {
    		if (!$filter->getFilter()->getData('attribute_model')) {
    			continue;
    		}
            $code = $filter->getFilter()->getAttributeModel()->getAttributeCode();
            $name = $filter->getName();
            $selected = $filter->getLabel();
            if (!isset($options[$code])) {
                $options[$code] = array();
            }
            $names[$code] = $name;
            $options[$code][] = $selected;
        }
        $allOptions = array();
        $allOptions2 = array();
        foreach ($options as $code => $values) {
            $this->setData($code, implode(', ', $values));
//
//            if ($code == 'brand') {
//                continue;
//            }
            $allOptions[] = $names[$code].': '.implode(', ', $values);
            $allOptions2[] = implode(', ', $values);
        }

        $this->setNamedSelectedOptions(implode(', ', $allOptions));
        $this->setSelectedOptions(implode(', ', $allOptions2));
    }

    /**
     * Retrieve Layer object
     *
     * @return Mage_Catalog_Model_Layer
     */
    public function getLayer()
    {
        if (!$this->hasData('layer')) {
            $this->setLayer(Mage::getSingleton('catalog/layer'));
        }
        return $this->_getData('layer');
    }

    /**
     * Retrieve active filters
     *
     * @return array
     */
    public function getActiveFilters()
    {
        $filters = $this->getLayer()->getState()->getFilters();
        if (!is_array($filters)) {
            $filters = array();
        }
        return $filters;
    }
}