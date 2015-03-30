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
 * ÐÐ»Ð¾Ðº Ð´Ð»Ñ Ð²ÑÐ²Ð¾Ð´Ð° Goodrelations Ð½Ð° ÑÑÑÐ°Ð½Ð¸ÑÐµ Ð¿ÑÐ¾Ð´ÑÐºÑÐ°
 */
class Mirasvit_Seo_Block_Goodrelations extends Mage_Core_Block_Template
{
    public function getProduct() {
        return Mage::registry('current_product');
    }

    /**
     * ÐÐ¾Ð·Ð²ÑÐ°ÑÐ°ÐµÑ Ð¼Ð¸Ð½Ð¸Ð¼Ð°Ð»ÑÐ½ÑÑ ÑÐµÐ½Ñ Ð´Ð»Ñ Ð³ÑÑÐ¿Ð¿Ð¾Ð²Ð¾Ð³Ð¾ Ð¿ÑÐ¾Ð´ÑÐºÑÐ°
     * @return int
     */
    public function getGroupedMinimalPrice() {
        $product = Mage::getModel('catalog/product')->getCollection()
                        ->addMinimalPrice()
                        ->addFieldToFilter('entity_id',$this->getProduct()->getId())
                        ->getFirstItem();
        return Mage::helper('tax')->getPrice($product, $product->getMinimalPrice(), $includingTax = true);
    }


    public function getCurrentCurrencyCode()
    {
        return Mage::app()->getStore()->getCurrentCurrencyCode();
    }

    /**
     * ÐÐ¾Ð·Ð²ÑÐ°ÑÐ°ÐµÑ Ð´Ð¾ÑÑÑÐ¿Ð½ÑÐµ Ð¿Ð»Ð°ÑÐµÐ¶Ð½ÑÐµ Ð¼ÐµÑÐ¾Ð´Ñ Ð¼Ð°Ð³Ð°Ð·Ð¸Ð½Ð°
     * @return array
     */
    public function getActivePaymentMethods()
    {
//        $codes = array(
//        '' => 'ByBankTransferInAdvance',
//        '' => 'ByInvoice',
//        '' => 'Cash',
//        '' => 'CheckInAdvance',
//        '' => 'COD',
//        '' => 'DirectDebit',
//        '' => 'PayPal',
//        '' => 'PaySwarm',
//        '' => 'AmericanExpress',
//        '' => 'DinersClub',
//        '' => 'Discover',
//        '' => 'MasterCard',
//        '' => 'VISA',
//        '' => 'JCB',
//        '' => 'GoogleCheckout',
//        );

       $payments = Mage::getSingleton('payment/config')->getActiveMethods();
       $methods = array();
       foreach ($payments as $paymentCode=>$paymentModel) {
            $paymentTitle = Mage::getStoreConfig('payment/'.$paymentCode.'/title');
            $code = false;
            if (strpos($paymentCode, 'paypal') !== false) {
                $methods[] = 'PayPal';
            } elseif (strpos($paymentCode, 'googlecheckout') !== false) {
                $methods[] = 'GoogleCheckout';
            } elseif ($paymentCode == 'ccsave') {
                $methods[] = 'MasterCard';
                $methods[] = 'AmericanExpress';
                $methods[] = 'VISA';
                $methods[] = 'JCB';
                $methods[] = 'Discover';
            }
        }
        return array_unique($methods);
    }

}
