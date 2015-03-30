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


class Mirasvit_Seo_Model_System_Config_Source_Crossdomain
{
    public function toOptionArray()
    {
        $options = array(array('value'=>'0', 'label'=> 'Default Store URL'));
        foreach (Mage::app()->getStores() as $store){
            $options[] = array('value'=>$store->getId(), 'label'=> $store->getName() . ' â '. $store->getBaseUrl());
        }
        return $options;
    }
}
