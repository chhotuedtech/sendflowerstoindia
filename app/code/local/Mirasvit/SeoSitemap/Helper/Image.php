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


class Mirasvit_SeoSitemap_Helper_Image extends Mirasvit_MstCore_Helper_Image
{
    public function init (Varien_Object $item, $attributeName, $imageFolder = null, $imageFile = null)
    {
        $this->_reset();
        $this->_setModel(Mage::getModel('seositemap/image'));
        $this->_getModel()->setDestinationSubdir($attributeName);
        $this->_getModel()->setSubdir($imageFolder);
        $this->setItem($item);
        $this->addWatermark();

        if ($imageFile) {
            $this->setImageFile($imageFile);
        } else {
            $this->_getModel()->setBaseFile(
                    $this->getItem()
                        ->getData($this->_getModel()
                        ->getDestinationSubdir()));
        }
        return $this;
    }

    public function setUrlKey($urlKey)
    {
        $this->_getModel()->setUrlKey($urlKey);
        return $this;
    }


    public function setUrldir($urlKey)
    {
        $this->_getModel()->setUrldir($urlKey);
        return $this;
    }

    public function toStr ()
    {
        Varien_Profiler::start(__CLASS__.'::'.__FUNCTION__);
        // try {
            if ($this->getImageFile()) {
                $this->_getModel()->setBaseFile($this->getImageFile());
            } else {
                $this->_getModel()->setBaseFile(
                        $this->getItem()
                            ->getData($this->_getModel()
                            ->getDestinationSubdir()));
            }

            if ($this->_getModel()->isCached()) {
                return $this->_getModel()->getUrl();
            } else {
                if ($this->_scheduleRotate) {
                    $this->_getModel()->rotate($this->getAngle());
                }

                if ($this->_scheduleResize) {
                    $this->_getModel()->resize();
                }

                if ($this->_scheduleCrop) {
                    $this->_getModel()->crop();
                }

                if ($this->getWatermark()) {
                    $this->_getModel()->setWatermark($this->getWatermark());
                }

                $url = $this->_getModel()
                    ->saveFile()
                    ->getUrl();
            }
        // } catch (Mage_Exception $e) {
        //     $url = Mage::getDesign()->getSkinUrl($this->getPlaceholder());
        // }
        Varien_Profiler::stop(__CLASS__.'::'.__FUNCTION__);
        return $url;
    }
}