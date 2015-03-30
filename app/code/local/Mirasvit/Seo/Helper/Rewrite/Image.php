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


if (Mage::helper('mstcore')->isModuleInstalled('EM_Cloudzoom') && class_exists('EM_Cloudzoom_Helper_Image')) {
   abstract class Mirasvit_Seo_Helper_Rewrite_Image_Abstract extends EM_Cloudzoom_Helper_Image {

   }
} elseif (Mage::helper('mstcore')->isModuleInstalled('Amasty_Shopby') && class_exists('Amasty_Shopby_Helper_Image')) {
    abstract class Mirasvit_Seo_Helper_Rewrite_Image_Abstract extends Amasty_Shopby_Helper_Image {

   }
} elseif (Mage::helper('mstcore')->isModuleInstalled('Bintime_Sinchimport') && class_exists('Bintime_Sinchimport_Helper_Image')) {
    abstract class Mirasvit_Seo_Helper_Rewrite_Image_Abstract extends Bintime_Sinchimport_Helper_Image {
    }
} else {
    abstract class Mirasvit_Seo_Helper_Rewrite_Image_Abstract extends Mage_Catalog_Helper_Image {
    }
}

class Mirasvit_Seo_Helper_Rewrite_Image extends Mirasvit_Seo_Helper_Rewrite_Image_Abstract
{
	public function getConfig()
	{
		return Mage::getSingleton('seo/config');
	}

    public function init(Mage_Catalog_Model_Product $product, $attributeName, $imageFile=null, $useImageFriendlyUrls=true)
    {
        $config = $this->getConfig();
        if ($config->getIsEnableImageFriendlyUrls() && $useImageFriendlyUrls) {
            if ($template = $config->getImageUrlTemplate()) {
                $urlKey = Mage::helper('mstcore/parsevariables')->parse(
                    $template,
                    array('product' => $product)
                );
            } else {
                $urlKey = $product->getName();
            }
            $urlKey = Mage::getSingleton('catalog/product_url')->formatUrlKey($urlKey);

            $this->_reset();
            $this->_setModel(Mage::getModel('seo/rewrite_product_image'));
            $this->_getModel()->setDestinationSubdir($attributeName);
            $this->_getModel()->setUrlKey($urlKey);
            $this->setProduct($product);

            $this->setWatermark(Mage::getStoreConfig("design/watermark/{$this->_getModel()->getDestinationSubdir()}_image"));
            $this->setWatermarkImageOpacity(Mage::getStoreConfig("design/watermark/{$this->_getModel()->getDestinationSubdir()}_imageOpacity"));
            $this->setWatermarkPosition(Mage::getStoreConfig("design/watermark/{$this->_getModel()->getDestinationSubdir()}_position"));
            $this->setWatermarkSize(Mage::getStoreConfig("design/watermark/{$this->_getModel()->getDestinationSubdir()}_size"));
        } elseif ($config->getIsEnableImageAlt()) {
            $this->_reset();
            $this->_setModel(Mage::getModel('catalog/product_image'));
            $this->_getModel()->setDestinationSubdir($attributeName);
            $this->setProduct($product);

            $this->setWatermark(
                Mage::getStoreConfig("design/watermark/{$this->_getModel()->getDestinationSubdir()}_image")
            );
            $this->setWatermarkImageOpacity(
                Mage::getStoreConfig("design/watermark/{$this->_getModel()->getDestinationSubdir()}_imageOpacity")
            );
            $this->setWatermarkPosition(
                Mage::getStoreConfig("design/watermark/{$this->_getModel()->getDestinationSubdir()}_position")
            );
            $this->setWatermarkSize(
                Mage::getStoreConfig("design/watermark/{$this->_getModel()->getDestinationSubdir()}_size")
            );
        } else {
        	return parent::init($product, $attributeName, $imageFile);
        }

        if ($imageFile) {
            $this->setImageFile($imageFile);
        }
        else {
            // add for work original size
            $this->_getModel()->setBaseFile( $this->getProduct()->getData($this->_getModel()->getDestinationSubdir()) );
        }

        $this->setImageAlt($attributeName);
        return $this;
    }


    public function generateAlt()
    {
        if ($template = $this->getConfig()->getImageAltTemplate()) {
            $alt = Mage::helper('mstcore/parsevariables')->parse(
                $template,
                array('product' => $this->getProduct())
            );
        } else {
            $product = $this->getProduct();
            $alt = $product->getName();
        }
        $alt = trim($alt);
        return $alt;
    }

    protected function setImageAlt($attributeName) {
        if (!$this->getConfig()->getIsEnableImageAlt()) {
            return;
        }
        $alt = $this->generateAlt();
        $product = $this->getProduct();
        $key = $attributeName . '_label';
        if (!$product->getData($key)) {
            $product->setData($attributeName . '_label', $alt);
            if ($gallery = $product->getMediaGalleryImages()) {
                $alt = $this->generateAlt();
                foreach ($gallery as $image) {
                    if (! $image->getLabel()) {
                        $image->setLabel($alt);
                    }
                }
            }
        }
    }

}