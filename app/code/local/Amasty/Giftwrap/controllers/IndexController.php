<?php

/**
 * @copyright  Amasty (http://www.amasty.com)
 */
class Amasty_Giftwrap_IndexController extends Mage_Core_Controller_Front_Action
{
    public function getFormHtmlAction()
    {
        $storeId  = Mage::app()->getStore()->getStoreId();
        $quote    = Mage::getSingleton('checkout/session')->getQuote();
        $disabledItems = Mage::helper('amgiftwrap')->getGiftWrapDisabledItems();
        $designId = $quote->getData('amgiftwrap_design_id');
        $cardId   = $quote->getData('amgiftwrap_card_id');

        $designCollection = Mage::getModel('amgiftwrap/design')
            ->getCollection()
            ->addFieldToFilter('enabled', array('eq' => '1'))
            ->addFieldToFilter(array('stores', 'stores'), array(array('like' => "%,0,%"), array('like' => "%,$storeId,%")))
            ->setOrder('sort', 'DESC');

        // sort and place selected design first
        $i                     = 1;
        $designCollectionArray = array();
        foreach ($designCollection as $design) {
            if ($design->getDesignId() == $designId) {
                array_unshift($designCollectionArray, $design);
            } else {
                $designCollectionArray[] = $design;
            }
        }


        $cardCollection = Mage::getModel('amgiftwrap/cards')
            ->getCollection()
            ->addFieldToFilter('enabled', array('eq' => '1'))
            ->addFieldToFilter(array('stores', 'stores'), array(array('like' => "%,0,%"), array('like' => "%,$storeId,%")))
            ->setOrder('sort', 'DESC');

        // sort and place selected design first
        $i                   = 1;
        $cardCollectionArray = array();
        foreach ($cardCollection as $card) {
            if ($card->getCardsId() == $cardId) {
                array_unshift($cardCollectionArray, $card);
            } else {
                $cardCollectionArray[] = $card;
            }
        }

        $giftMessage = Mage::getModel('giftmessage/message')->load($quote->getGiftMessageId());;

        $template = Mage::getModel('core/layout')
            ->createBlock('core/template')
            ->setDesignCollection($designCollectionArray)
            ->setCardCollection($cardCollectionArray)
            ->setDisabledItems($disabledItems)
            ->setGiftMessage($giftMessage)
            ->setTemplate('amasty/amgiftwrap/cart_form.phtml')
            ->toHtml();
        echo $template;

        return true;
    }

    public function saveFormDataAction()
    {
        if (!Mage::getStoreConfig('amgiftwrap/general/enabled')) {
            echo '<div style="padding-top:10px;min-height:30px;min-width:200px;position:absolute;top:0px;left:0px;margin-top:-2px;color:maroon;font-weight:bold;background-color: #fbfbef">Error occurred: module do not enabled. Form was not saved.</div>';

            return false;
        }

        $params = Mage::app()->getRequest()->getParams();
        if (is_array($params) && count($params) > 0) {
            /*
             * add Gift Wrap options into Quote
             */
            $quote = Mage::getSingleton('checkout/session')->getQuote();
            if (Mage::getStoreConfig('amgiftwrap/general/allow_separate_wrap') && isset($params['amgiftwrap_separate_wrap'])) {
                $quote->setData('amgiftwrap_separate_wrap', 1);
            } else {
                $quote->setData('amgiftwrap_separate_wrap', 0);
            }
            $quote->setData('amgiftwrap_design_id', $params['amgiftwrap_design_id']);
            $quote->setData('amgiftwrap_card_id', $params['amgiftwrap_card_id']);

            /*
             * add Message Card info
             */
            if ($params['gift-message-whole-enabled'] == "1") {
                $giftMessage   = Mage::getModel('giftmessage/message');
                $giftMessageId = $quote->getGiftMessageId();

                // clear empty fields
                if (!strlen($params['gift-message-whole-from'])) {
                    $params['gift-message-whole-from'] = ' ';
                }
                if (!strlen($params['gift-message-whole-to'])) {
                    $params['gift-message-whole-to'] = ' ';
                }

                // save sata
                if (!$giftMessageId) {
                    $giftMessage->setCustomerId($quote->getCustomerId());
                    $giftMessage->setSender($params['gift-message-whole-from']);
                    $giftMessage->setRecipient($params['gift-message-whole-to']);
                    $giftMessage->setMessage($params['gift-message-whole-message']);
                    $giftObj = $giftMessage->save();
                    $quote->setGiftMessageId($giftObj->getId());
                } else {
                    $giftMessage->load($quote->getGiftMessageId());
                    $giftMessage->setSender($params['gift-message-whole-from']);
                    $giftMessage->setRecipient($params['gift-message-whole-to']);
                    $giftMessage->setMessage($params['gift-message-whole-message']);
                    $giftMessage->save();
                }
            } else if (!$params['gift-message-whole-enabled']) {
                /*
                 * delete message from order because no any Message Card was selected
                 */
                $giftMessage   = Mage::getModel('giftmessage/message');
                $giftMessageId = $quote->getGiftMessageId();
                if ($giftMessageId) {
                    $giftMessage->setSender('');
                    $giftMessage->setRecipient('');
                    $giftMessage->setMessage('');
                    $giftMessage->delete();
                }
            }


            $quote->save();
            $quote->setTotalsCollectedFlag(false)->collectTotals();

        }

        return true;
    }
}