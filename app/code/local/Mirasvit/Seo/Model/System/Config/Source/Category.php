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


/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at http://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   Advanced SEO Suite
 * @version   1.0.3
 * @revision  274
 * @copyright Copyright (C) 2013 Mirasvit (http://mirasvit.com/)
 */


class Mirasvit_Seo_Model_System_Config_Source_Category extends Mage_Eav_Model_Entity_Attribute_Backend_Abstract
{
    public function getAllOptions()
    {
        $store = Mage::app()->getRequest()->getParam('store');
        if ($store) {
            $rootForStore = Mage::app()->getStore($store)->getRootCategoryId();
        }
    	$product = Mage::registry('current_product');
    	if ($product) {
			$collection = $product->getCategoryCollection();
            if (isset($rootForStore)) {
                $collection->addFieldToFilter('path', array("like" => '1/'.$rootForStore.'%'));
            }
		} else {
			$collection = Mage::getModel('catalog/category')->getCollection();
		}
		$collection->addAttributeToSelect('name');
		$collection->setOrder('path');
        $options = array();
        $options = array(array('value'=>'0', 'label'=> ''));
        $inactiveCat = Mage::helper('seo')->getInactiveCategories();
        foreach ($collection as $category){
            if (!in_array($category->getId(), $inactiveCat)) {
                $options[] = array('value'=>$category->getId(), 'label'=> $category->getName());
            }
        }
        // pr((string)$collection->getSelect());
        // die;
        return $options;
    }

    public function getOptionText($value)
    {
        $options = $this->getAllOptions();
        foreach ($options as $option) {
            if ($option['value'] == $value) {
                return $option['label'];
            }
        }
        return false;
    }
}
