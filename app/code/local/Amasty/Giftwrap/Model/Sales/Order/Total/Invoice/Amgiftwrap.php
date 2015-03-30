<?php

class Amasty_Giftwrap_Model_Sales_Order_Total_Invoice_Amgiftwrap extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{
    public function collect(Mage_Sales_Model_Order_Invoice $invoice)
    {
        $order                = $invoice->getOrder();
        $amgiftwrapAmountLeft = $order->getAmgiftwrapAmount() - $order->getAmgiftwrapAmountInvoiced();
        $baseAmgiftwrapAmountLeft = $order->getBaseAmgiftwrapAmount() - $order->getBaseAmgiftwrapAmountInvoiced();

        $invoice->setGrandTotal($invoice->getGrandTotal() + $amgiftwrapAmountLeft);
        $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $baseAmgiftwrapAmountLeft);

        $invoice->setAmgiftwrapAmount($amgiftwrapAmountLeft);
        $invoice->setBaseAmgiftwrapAmount($baseAmgiftwrapAmountLeft);

        return $this;
    }
}
