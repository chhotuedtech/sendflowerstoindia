<?php

/**
 * @author    Amasty Team
 * @copyright Copyright (c) 2008-2014 Amasty (http://www.amasty.com)
 * @package   Amasty_Giftwrap
 */
class Amasty_Giftwrap_Model_Observer
{
    /**
     * inserts button on cart page
     *
     * @param Varien_Event_Observer $observer
     *
     * @return bool
     */
    public function insertHtml($observer)
    {
        if (!Mage::getStoreConfig('amgiftwrap/general/enabled')) {
            return false;
        }

        $block = $observer->getBlock();
        $class = get_class($block);

        if (strpos($class, '_Email_') !== false && !Mage::registry('amgiftwrap_email_template_style')) {
            Mage::register('amgiftwrap_email_template_style', true);
        }
        if ($block instanceof Mage_Sales_Block_Order_Creditmemo_Totals) {
            Mage::unregister('amgiftwrap_template_style');
            Mage::register('amgiftwrap_template_style', true);
        }

        if ($block instanceof Mage_Adminhtml_Block_Sales_Order_View_Tab_Info
            || $block instanceof Mage_Adminhtml_Block_Sales_Order_Invoice_View
            || $block instanceof Mage_Adminhtml_Block_Sales_Order_Shipment_View
        ) {
            $html = $observer->getTransport()->getHtml();
            $html = $this->_prepareBackendHtml($html);
            $observer->getTransport()->setHtml($html);
        }

        if ($block instanceof Mage_Sales_Block_Order_Info) {
            $html = $observer->getTransport()->getHtml();
            $html = $this->_prepareFrontendHtml($html);
            $observer->getTransport()->setHtml($html);
        }

        if ($block instanceof Mage_Sales_Block_Order_Totals
            || $block instanceof Mage_Sales_Block_Order_Creditmemo_Totals
            || $block instanceof Mage_Sales_Block_Order_Invoice_Totals
        ) {
            $html = $observer->getTransport()->getHtml();
            $html = $this->_prepareTotalsHtml($html);
            $observer->getTransport()->setHtml($html);
        }

        if ($block instanceof Mage_Checkout_Block_Cart_Shipping || $block instanceof Mage_Checkout_Block_Onepage_Shipping_Method) {
            if (Mage::helper('amgiftwrap')->isGiftwrapAllowed()) {
                $layoutHTML = $observer->getTransport()->getHtml();
                $buttonOnly = $block instanceof Mage_Checkout_Block_Onepage_Shipping_Method ? 1 : 0;
                $html = $this->_prepareFrontendButtonHtml($layoutHTML, $buttonOnly);
                $observer->getTransport()->setHtml($html);
            }
        }

        if ($block instanceof Mage_Adminhtml_Block_Sales_Order_Create_Shipping_Method) {
            $html = $observer->getTransport()->getHtml();
            $html = $this->_prepareCreateOrderButton($html);
            $observer->getTransport()->setHtml($html);
        }
    }

    /**
     * @param $html
     *
     * @return mixed
     */
    private function _prepareBackendHtml($html)
    {
        $order   = $this->getDataByParams();
        $storeId = $order->getStore()->getId();
        if (!$order) {
            return $html;
        }

        $design = Mage::getModel('amgiftwrap/design')->load($order->getAmgiftwrapDesignId());
        $card    = Mage::getModel('amgiftwrap/cards')->load($order->getAmgiftwrapCardId());
        if ($order->getAmgiftwrapDesignId() || $order->getAmgiftwrapCardId()) {
            $html_block = Mage::getModel('core/layout')
                ->createBlock('core/template')
                ->setDesign($design)
                ->setCard($card)
                ->setOrder($order)
                ->setTemplate('amasty/amgiftwrap/backend_giftwrap_block.phtml')
                ->toHtml();

            $html = preg_replace(
                '@<div class="clear"></div>(\s*)<div class="entry-edit">(\s*)<div class="entry-edit-head">(\s*)(.*?)head-products@',
                str_replace('$', '\$', $html_block) . '<div class="clear"></div><div class="entry-edit"><div class="entry-edit-head">$4head-products',
                $html, 1
            );
        }

        // find all items and check if it is restricted for GiftWrap
        $matches = array();
        preg_match_all('@id="order_item_(.*)"@', $html, $matches);
        if (count($matches) > 2 && !empty($matches[2])) {
            foreach ($matches[2] as $match) {
                $parts     = explode('_', $match, 2);
                $productId = (int)$parts[0];
                if ($productId > 0) {
                    $disabled = Mage::getResourceModel('catalog/product')->getAttributeRawValue($productId, 'amgiftwrap_blacklisted', $storeId);
                    if ($disabled) {
                        $html = $this->_addDisabledWarning($html);
                    }
                }
            }
        }

        return $html;
    }

    /**
     * @return bool|Mage_Core_Model_Abstract|Varien_Object
     */
    private function getDataByParams()
    {
        $data = false;
        $params = Mage::app()->getRequest()->getParams();
        /*
         * case: invoice view/edit/create
         */
        if (isset($params['come_from']) && $params['come_from'] == 'invoice') {
            $data = Mage::getModel('sales/order_invoice')->load($params['invoice_id']);
        } /*
         * case: order edit
         */
        elseif (isset($params['come_from']) && $params['come_from'] == 'order') {
            $data = Mage::getModel('sales/order')->load($params['order_id']);
        } /*
         * case: order view
         */
        elseif (!isset($params['come_from']) && isset($params['order_id'])) {
            $data = Mage::getModel('sales/order')->load($params['order_id']);
        }  /*
         * case: creditmemo view print
         */
        elseif (!isset($params['come_from']) && isset($params['creditmemo_id'])) {
            $data = Mage::getModel('sales/order_creditmemo')->load($params['creditmemo_id']);
        }  /*
         * case: invoice view print
         */
        elseif (!isset($params['come_from']) && isset($params['invoice_id'])) {
            $data = Mage::getModel('sales/order_invoice')->load($params['invoice_id']);
        } /*
         * case: Order Email Template Processing
         */
        elseif ($quote = Mage::getSingleton('checkout/session')->getQuote()) {
            if (count($quote->getData()) < 1) {
                return false;
            }
            $data = new Varien_Object();
            $data->setAmgiftwrapDesignId($quote->getAmgiftwrapDesignId());
            $data->setAmgiftwrapCardId($quote->getAmgiftwrapCardId());
            $data->setAmgiftwrapSeparateWrap($quote->getAmgiftwrapSeparateWrap());
            $amount = Mage::getModel('amgiftwrap/design')->load($quote->getAmgiftwrapDesignId())->getPrice() + Mage::getModel('amgiftwrap/cards')->load($quote->getAmgiftwrapCardId())->getPrice();
            $data->setAmgiftwrapAmount($amount);
        }

        return $data;
    }

    /**
     * @param $html
     *
     * @return string
     */
    private function _prepareFrontendHtml($html)
    {
        $params = Mage::app()->getRequest()->getParams();
        if (!isset($params['order_id'])) {
            return $html;
        }

        $order = Mage::getModel('sales/order')->load($params['order_id']);
        $design = Mage::getModel('amgiftwrap/design')->load($order->getAmgiftwrapDesignId());
        $card  = Mage::getModel('amgiftwrap/cards')->load($order->getAmgiftwrapCardId());
        if ($order->getAmgiftwrapDesignId() > 0 || $order->getAmgiftwrapCardId() > 0) {
            $html_block = Mage::getModel('core/layout')
                ->createBlock('core/template')
                ->setDesign($design)
                ->setCard($card)
                ->setOrder($order)
                ->setTemplate('amasty/amgiftwrap/frontend_giftwrap_block.phtml')
                ->toHtml();
            $html       = $html . $html_block;
        }

        return $html;
    }

    /**
     * @param $html
     *
     * @return mixed
     */
    private function _prepareTotalsHtml($html)
    {
        $order = $this->getDataByParams();
        if (!$order) {
            return $html;
        }


        if (Mage::registry('amgiftwrap_email_template_style')) {
            /*
            * email template
            */
            $styles1 = 'colspan="3" align="right" style="padding:3px 9px"';
            $styles2 = 'colspan="3" align="right" style="padding:3px 9px"';
        } else {
            /*
             * frontend page template
             */
            $collspan = Mage::registry('amgiftwrap_template_style') ? 6 : 4;
            $styles1 = 'colspan="' . $collspan . '" class="a-right"';
            $styles2 = 'class="last a-right"';
        }

        $html_block = '
            <tr class="">
                <td ' . $styles1 . '>' . Mage::helper('amgiftwrap')->__('Gift Wrap') . '</td>
                <td ' . $styles2 . '><span class="price">' . Mage::helper('core')->currency($order->getAmgiftwrapAmount(), true, false) . '</span></td>
            </tr>
        ';

        if ($order->getAmgiftwrapAmount() > 0) {
            $html = str_replace(
                '<tr class="shipping',
                $html_block . "\r\n" . ' <tr class="shipping',
                $html
            );
        }

        return $html;
    }

    private function _prepareFrontendButtonHtml($layoutHTML, $buttonOnly = false)
    {
        // get saved into quote data with GiftWrap options
        $quote                  = Mage::getSingleton('checkout/session')->getQuote();
        $amgiftwrapCardId       = $quote->getAmgiftwrapCardId() ? $quote->getAmgiftwrapCardId() : '';
        $amgiftwrapDesignId     = $quote->getAmgiftwrapDesignId() ? $quote->getAmgiftwrapDesignId() : '';
        $amgiftwrapSeparateWrap = $quote->getAmgiftwrapSeparateWrap() ? $quote->getAmgiftwrapSeparateWrap() : '';

        // load GiftWrap data
        if ($amgiftwrapDesignId || $amgiftwrapCardId) {
            $design = Mage::getModel('amgiftwrap/design')->load($amgiftwrapDesignId);
            $card   = Mage::getModel('amgiftwrap/cards')->load($amgiftwrapCardId);
        }

        // set renderer block template && vars
        $template = Mage::getModel('core/layout')
                        ->createBlock('core/template')
            ->setButtonOnly($buttonOnly)
            ->setTemplate('amasty/amgiftwrap/cart_button.phtml');

        // save data into renderer
        if ($amgiftwrapDesignId || $amgiftwrapCardId) {
            $template
                ->setDesign($design)
                ->setCard($card)
                ->setSeparateWrap($amgiftwrapSeparateWrap);
        }

        // render the block
        $templateHTML = $template->toHtml();

        // place block on page
        if (strpos($layoutHTML, 'shipping-method-buttons-container') !== false) {
            $html = str_replace('<div id="onepage-checkout-shipping-method-additional-load">', $templateHTML . '<div id="onepage-checkout-shipping-method-additional-load">', $layoutHTML);
        } else {
            $html = $templateHTML . $layoutHTML;
        }

        return $html;
    }

    /**
     * @param $html
     */
    public function _prepareCreateOrderButton($html)
    {
        $disabledItems = Mage::helper('amgiftwrap')->getGiftWrapDisabledItems();
        $template      = Mage::getModel('core/layout')
                             ->createBlock('core/template')
                             ->setDisabledItems($disabledItems)
                             ->setTemplate('amasty/amgiftwrap/cart_form.phtml')
                             ->toHtml();

        return $template;
    }

    /**
     * @param Varien_Event_Observer $observer
     *
     * @return $this
     */
    public function invoiceSaveAfter(Varien_Event_Observer $observer)
    {
        $invoice = $observer->getEvent()->getInvoice();
        if ($invoice->getBaseAmgiftwrapAmount()) {
            $order = $invoice->getOrder();
            $order->setAmgiftwrapAmountInvoiced($order->getAmgiftwrapAmountInvoiced() + $invoice->getAmgiftwrapAmount());
            $order->setBaseAmgiftwrapAmountInvoiced($order->getBaseAmgiftwrapAmountInvoiced() + $invoice->getBaseAmgiftwrapAmount());
        }

        return $this;
    }

    /**
     * @param Varien_Event_Observer $observer
     *
     * @return $this
     */
    public function creditmemoSaveAfter(Varien_Event_Observer $observer)
    {
        /* @var $creditmemo Mage_Sales_Model_Order_Creditmemo */
        $creditmemo = $observer->getEvent()->getCreditmemo();
        if ($creditmemo->getAmgiftwrapAmount()) {
            $order = $creditmemo->getOrder();
            $order->setAmgiftwrapAmountRefunded($order->getAmgiftwrapAmountRefunded() + $creditmemo->getAmgiftwrapAmount());
            $order->setBaseAmgiftwrapAmountRefunded($order->getBaseAmgiftwrapAmountRefunded() + $creditmemo->getBaseAmgiftwrapAmount());
        }

        return $this;
    }

    /**
     * @param $evt
     */
    public function updatePaypalTotal($evt)
    {
        $cart   = $evt->getPaypalCart();
        $amount = Mage::helper('amgiftwrap')->getTotalsByIds($cart->getSalesEntity());
        if ($amount) {
            $cart->addItem(Mage::helper('amgiftwrap')->__('Gift Wrap'), 1, $amount);
        }
    }

    /**
     * @param Varien_Event_Observer $observer
     *
     * @return $this
     */
    function convertQuoteToOrder(Varien_Event_Observer $observer)
    {
        $observer->getOrder()->setData('amgiftwrap_design_id', $observer->getQuote()->getAmgiftwrapDesignId());
        $observer->getOrder()->setData('amgiftwrap_card_id', $observer->getQuote()->getAmgiftwrapCardId());
        if (Mage::getStoreConfig('amgiftwrap/general/allow_separate_wrap')) {
            $observer->getOrder()->setData('amgiftwrap_separate_wrap', $observer->getQuote()->getAmgiftwrapSeparateWrap());
        }

        return $this;
    }

    /**
     * @param Varien_Event_Observer $observer
     *
     * @return $this
     */
    public function clearShoppingCart(Varien_Event_Observer $observer)
    {
        $post = Mage::app()->getRequest()->getPost('update_cart_action');
        if ($post == 'empty_cart') {
            $quote = Mage::helper('checkout/cart')->getQuote(); //quote
            $quote->setAmgiftwrapDesignId(0);
            $quote->setAmgiftwrapCardId(0);
            $quote->setAmgiftwrapSeparateWrap(0);
            $quote->save();
            $quote->setTotalsCollectedFlag(false)->collectTotals();
        }
    }

    /**
     * @param Varien_Event_Observer $observer
     *
     * @return $this
     */
    public function convertOrderToQuote(Varien_Event_Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $quote = $observer->getEvent()->getQuote();
        $storeId = $order->getStore()->getId();

        $quote->setData('amgiftwrap_design_id', $order->getAmgiftwrapDesignId());
        $quote->setData('amgiftwrap_card_id', $order->getAmgiftwrapCardId());
        if (Mage::getStoreConfig('amgiftwrap/general/allow_separate_wrap', $storeId)) {
            $quote->setData('amgiftwrap_separate_wrap', $order->getAmgiftwrapSeparateWrap());
        }

        $quote->save();
        $quote->setTotalsCollectedFlag(false)->collectTotals();

        return $this;
    }


    /**
     * @param Varien_Event_Observer $observer
     */
    public function mergeQuotes(Varien_Event_Observer $observer)
    {
        $source = $observer->getSource();
        $quote  = $observer->getQuote();

        $quote->setAmgiftwrapCardId($source->getAmgiftwrapCardId());
        $quote->setAmgiftwrapDesignId($source->getAmgiftwrapDesignId());
        $quote->setAmgiftwrapSeparateWrap($source->getAmgiftwrapSeparateWrap());
        $quote->setTotalsCollectedFlag(false)->collectTotals();
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function checkCartProducts(Varien_Event_Observer $observer)
    {
        $cartItems     = Mage::getSingleton('checkout/cart')->getItemsCount();
        $disabledItems = Mage::helper('amgiftwrap')->getGiftWrapDisabledItems();
        if ($cartItems - count($disabledItems) > 0) {
            $allowed = true;
        } else {
            $allowed = false;

            $quote = Mage::helper('checkout/cart')->getQuote();
            $quote->setAmgiftwrapDesignId(0);
            $quote->setAmgiftwrapSeparateWrap(0);
            if (!Mage::getStoreConfig('amgiftwrap/general/allow_separate_wrap')) {
                $quote->setAmgiftwrapCardId(0);
            }
            $quote->save();
            $quote->setTotalsCollectedFlag(false)->collectTotals();
        }

        return $allowed;
    }
}