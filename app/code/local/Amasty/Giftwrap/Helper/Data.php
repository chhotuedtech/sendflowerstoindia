<?php

/**
 * @copyright  Amasty (http://www.amasty.com)
 */
class Amasty_Giftwrap_Helper_Data extends Mage_Core_Helper_Abstract
{

    function prepareToSaveDataArray($data, $type)
    {
        $path = Mage::getBaseDir('media') . DS . 'amasty' . DS . 'amgiftwrap' . DS . $type . DS;
        $data['stores'] = ',' . implode(',', $data['stores']) . ',';
        /*
         * flag to remove image was set
         */
        if (isset($data['remove_image']) && $data['remove_image'] == 1) {
            @unlink($path . $data['old_image']);
            @unlink($path . DS . 'resized ' . DS . $data['old_image']);
            $data['image'] = '';
        } else
            /*
             * there were no errors on upload
             */
            if ($_FILES['image']['error'] == 0) {
                /*
                 * remove any old images
                 */
                if (isset($data['image']) || isset($data['old_image'])) {
                    @unlink($path . $data['old_image']);
                    @unlink($path . 'resized' . DS . $data['old_image']);
                }

                /*
                 * generate new image name
                 */
                $newName = substr(md5($_FILES['image']['name']), rand(0, 25), 3) . '_' .
                           substr(md5($_FILES['image']['name']), rand(0, 25), 3) . '_' .
                           md5($_FILES['image']['name']) .
                           '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

                /*
                 * save image
                 */
                $uploader = new Varien_File_Uploader('image');
                $uploader->setFilesDispersion(false);
                $uploader->setAllowRenameFiles(false);
                $uploader->setAllowedExtensions(array('png', 'gif', 'jpg', 'jpeg'));
                $uploader->save($path, $newName);
                $data['image'] = $newName;

                $this->getImageThumbnail($newName, $type);
            } else
                if ($_FILES['image']['error'] != 4) { /* code 4 = image was not uploaded (filed lived blank) */
                    Mage::getSingleton('adminhtml/session')->addError($_FILES['image']['error']);
                }

        return $data;
    }


    function getImageThumbnail($name, $type)
    {
        $basePath = Mage::getBaseDir('media') . DS . 'amasty' . DS . 'amgiftwrap' . DS . $type . DS;
        $path = Mage::getBaseUrl('media') . 'amasty' . DS . 'amgiftwrap' . DS . $type . DS;
        $width = Mage::getStoreConfig('amgiftwrap/general/image_width');
        $height = Mage::getStoreConfig('amgiftwrap/general/image_height');

        /* if original file exists*/
        if (!empty($name) && file_exists($basePath . $name)) {
            $imgSize = false;
            $pathResize = $basePath . 'resized' . DS;
            if (file_exists($pathResize . $name)) {
                $imgSize = getimagesize($pathResize . $name);
            }
            /* if resized image do not exist OR resized image width\height mismatch */
            if (!is_array($imgSize) || $imgSize[0] != $width || $imgSize[1] != $height) {
                $imageObj = new Varien_Image($basePath . $name);
                $imageObj->constrainOnly(TRUE);
                $imageObj->keepAspectRatio(FALSE);
                $imageObj->keepFrame(FALSE);
                $imageObj->resize($width, $height);
                $imageObj->save($pathResize, $name);
            }

            return $path . 'resized' . DS . $name;
        } else {
            return Mage::getBaseUrl('media') . DS . 'amasty' . DS . 'amgiftwrap' . DS . 'thumbnail.jpg';
        }
    }

    public function formatAmgiftwrap($amount)
    {
        return Mage::helper('amgiftwrap')->__('Gift Wrap');
    }

    public function isGiftWrapAllowed()
    {
        $storeId           = Mage::app()->getStore()->getStoreId();
        $cartItems         = Mage::getSingleton('checkout/cart')->getItemsCount();
        $cartItemsDisabled = count($this->getGiftWrapDisabledItems());
        $cartItemsAllowed  = ($cartItems - $cartItemsDisabled > 0) ? true : false;

        $designCollection = Mage::getModel('amgiftwrap/design')
                                ->getCollection()
                                ->addFieldToFilter('enabled', array('eq' => '1'))
                                ->addFieldToFilter(array('stores', 'stores'), array(array('like' => "%,0,%"), array('like' => "%,$storeId,%")));

        $cardCollection = Mage::getModel('amgiftwrap/cards')
                              ->getCollection()
                              ->addFieldToFilter('enabled', array('eq' => '1'))
                              ->addFieldToFilter(array('stores', 'stores'), array(array('like' => "%,0,%"), array('like' => "%,$storeId,%")));

        if (($designCollection->count() || $cardCollection->count()) && ($cartItemsAllowed || Mage::getStoreConfig('amgiftwrap/general/allow_message_card', $storeId))) {
            return true;
        } else {
            return false;
        }
    }

    public function getGiftWrapDisabledItems()
    {
        $disabled  = array();
        $storeId   = Mage::app()->getStore()->getStoreId();
        $cartItems = Mage::getModel('checkout/cart')->getQuote();
        foreach ($cartItems->getAllItems() as $item) {
            $productId = $item->getProduct()->getId();
            if (Mage::getResourceModel('catalog/product')->getAttributeRawValue($productId, 'amgiftwrap_blacklisted', $storeId)) {
                $disabled[$productId] = $item->getProduct();
            }
        }

        return $disabled;
    }

    public function getTotalsByIds($cart)
    {
        $amount             = 0;
        $amgiftwrapDesignId = $cart->getAmgiftwrapDesignId();
        $amgiftwrapCardId   = $cart->getAmgiftwrapCardId();

        /*
         * only if any design or card was chosen
         */
        if ($amgiftwrapDesignId) {
            $design = Mage::getModel('amgiftwrap/design')->load($amgiftwrapDesignId);
            $amount = $design->getPrice();
        }
        if ($amgiftwrapCardId) {
            $card = Mage::getModel('amgiftwrap/cards')->load($amgiftwrapCardId);
            $amount += $card->getPrice();
        }

        if ($amount) {
            $baseCurrencyCode    = Mage::app()->getStore()->getBaseCurrencyCode();
            $currentCurrencyCode = Mage::app()->getStore()->getCurrentCurrencyCode();
            if ($baseCurrencyCode != $currentCurrencyCode) {
                $amount = Mage::helper('directory')->currencyConvert($amount, $baseCurrencyCode, $currentCurrencyCode);
                $amount = round($amount, 2);
            }

            return $amount;
        }

        return false;
    }

}