<?php

class Amasty_Giftwrap_Model_Sales_Order_Total_Creditmemo_Amgiftwrap extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract
{
    public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {
        $order                = $creditmemo->getOrder();
        $amgiftwrapAmountLeft = $order->getAmgiftwrapAmountInvoiced() - $order->getAmgiftwrapAmountRefunded();
        $baseAmgiftwrapAmountLeft = $order->getBaseAmgiftwrapAmountInvoiced() - $order->getBaseAmgiftwrapAmountRefunded();
        if ($baseAmgiftwrapAmountLeft != 0) {
            $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $amgiftwrapAmountLeft);
            $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $baseAmgiftwrapAmountLeft);
            $creditmemo->setAmgiftwrapAmount($amgiftwrapAmountLeft);
            $creditmemo->setBaseAmgiftwrapAmount($baseAmgiftwrapAmountLeft);
        }

        return $this;
    }
}
