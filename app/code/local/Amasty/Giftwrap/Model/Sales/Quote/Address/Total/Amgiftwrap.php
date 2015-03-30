<?php

class Amasty_Giftwrap_Model_Sales_Quote_Address_Total_Amgiftwrap extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
    protected $_code = 'amgiftwrap';

    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        parent::collect($address);

        /*
         * check if only address type shipping coming through
         */
        $items = $this->_getAddressItems($address);
        if (!count($items) || Mage::registry('amgiftwrap_quote_processing')) {
            return $this;
        }

        /*
         * refresh variables
         */
        Mage::register('amgiftwrap_quote_processing', 1);
        $this->_setAmount(0);
        $this->_setBaseAmount(0);
        $amount = 0;

        $quote            = Mage::getSingleton('checkout/session')->getQuote();
        $amgiftwrapDesignId = $quote->getAmgiftwrapDesignId();
        $amgiftwrapCardId = $quote->getAmgiftwrapCardId();

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
            $baseCurrencyCode = Mage::app()->getStore()->getBaseCurrencyCode();
            $currentCurrencyCode = Mage::app()->getStore()->getCurrentCurrencyCode();
            if ($baseCurrencyCode != $currentCurrencyCode) {
                $amount = Mage::helper('directory')->currencyConvert($amount, $baseCurrencyCode, $currentCurrencyCode);
                $amount = round($amount, 2);
            }

            $this->_setBaseAmount($amount);
            $this->_setAmount($amount);
        }

        Mage::unregister('amgiftwrap_quote_processing');

        return $this;
    }

    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        $amgiftwrapAmount = $address->getAmgiftwrapAmount();
        if ($amgiftwrapAmount) {
            $address->addTotal(
                array(
                    "code" => $this->getCode(),
                    "title" => Mage::helper('amgiftwrap')->__("Gift Wrap"),
                    "value" => $amgiftwrapAmount
                )
            );
        }

        return $this;
    }
}