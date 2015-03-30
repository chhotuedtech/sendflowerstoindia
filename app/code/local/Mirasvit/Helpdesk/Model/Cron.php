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
 * @package   Help Desk MX
 * @version   1.0.7
 * @build     907
 * @copyright Copyright (C) 2014 Mirasvit (http://mirasvit.com/)
 */



class Mirasvit_Helpdesk_Model_Cron extends Varien_Object
{
    protected $_lockFile    = null;
    protected $_fast    = false;

    public function run_every_hour() {
        Mage::helper('helpdesk/ruleevent')->newEventCheck(Mirasvit_Helpdesk_Model_Config::RULE_EVENT_CRON_EVERY_HOUR);
    }

    public function setFast($fast)
    {
        $this->_fast = $fast;
    }

    public function run() {
        if (!$this->isLocked() || $this->_fast) {
            $this->lock();

            $this->fetchEmails();
            $this->processEmails();
            $this->runFollowUp();

            $this->unlock();
        }
    }

    public function runFollowUp()
    {
        $collection = Mage::getModel('helpdesk/ticket')->getCollection()
                    ->addFieldToFilter('fp_execute_at', array('lteq' => Mage::getSingleton('core/date')->gmtDate()));
        foreach ($collection as $ticket) {
            Mage::helper('helpdesk/followup')->process($ticket);
        }
    }

    public function fetchEmails() {
        $gateways = Mage::getModel('helpdesk/gateway')->getCollection()
                    ->addFieldToFilter('is_active', true)
                    ;
        foreach($gateways as $gateway) {
            $timeNow = Mage::getSingleton('core/date')->gmtDate();
            if (!$this->_fast) {
                if (strtotime($timeNow) - strtotime($gateway->getFetchedAt()) < $gateway->getFetchFrequency() * 60) {
                    continue;
                }
            }
            $message = Mage::helper('helpdesk')->__('Success');
            try {
                Mage::helper('helpdesk/fetch')->fetch($gateway);
            } catch (Exception $e) {
                $message = $e->getMessage();
                Mage::log("Can't connect to gateway {$gateway->getName()}. ".$e->getMessage(), null, 'helpdesk.log');
            }
            //Ð½Ð°Ð¼ Ð½ÑÐ¶Ð½Ð¾ Ð·Ð°Ð³ÑÑÐ·Ð¸ÑÑ Ð³ÐµÐ¹ÑÐ²ÐµÐ¹ ÐµÑÐµ ÑÐ°Ð·, Ñ.Ðº. ÐµÐ³Ð¾ Ð´Ð°Ð½Ð½ÑÐµ Ð¼Ð¾Ð³Ð»Ð¸ Ð¸Ð·Ð¼ÐµÐ½Ð¸ÑÑÑÑ Ð¿Ð¾ÐºÐ° Ð¸Ð´ÐµÑ ÑÐµÑÑ
            $gateway = Mage::getModel('helpdesk/gateway')->load($gateway->getId());
            $gateway->setLastFetchResult($message)
                    ->setFetchedAt($timeNow)
                    ->save();
        }
    }

    public function processEmails()
    {
        $emails = Mage::getModel('helpdesk/email')->getCollection()
            ->addFieldToFilter('is_processed', false);
        foreach ($emails as $email) {
            Mage::helper('helpdesk/email')->processEmail($email);
        }
    }


    /**
     * ÐÐ¾Ð·Ð²ÑÐ°ÑÐ°ÐµÑ ÑÐ°Ð¹Ð» Ð»Ð¾ÐºÐ°
     *
     * @return resource
     */
    protected function _getLockFile()
    {
        if ($this->_lockFile === null) {
            $varDir = Mage::getConfig()->getVarDir('locks');
            $file   = $varDir.DS.'helpdesk.lock';

            if (is_file($file)) {
                $this->_lockFile = fopen($file, 'w');
            } else {
                $this->_lockFile = fopen($file, 'x');
            }
            fwrite($this->_lockFile, date('r'));
        }

        return $this->_lockFile;
    }

 /**
     * ÐÐ¾ÑÐ¸Ð¼ ÑÐ°Ð¹Ð», Ð»ÑÐ±Ð¾Ð¹ Ð´ÑÑÐ³Ð¾Ð¹ php Ð¿ÑÐ¾ÑÐµÑÑ Ð¼Ð¾Ð¶ÐµÑ ÑÐ·Ð½Ð°ÑÑ
     * ÑÑÐ¾ ÑÐ°Ð¹Ð» Ð·Ð°Ð»Ð¾ÑÐµÐ½.
     * ÐÑÐ»Ð¸ Ð¿ÑÐ¾ÑÐµÑÑ ÑÐ¿Ð°Ð», ÑÐ°Ð¹Ð» ÑÐ°Ð·Ð»Ð¾ÑÐ¸ÑÑÑÑ
     *
     * @return object
     */
    public function lock()
    {
        flock($this->_getLockFile(), LOCK_EX | LOCK_NB);

        return $this;
    }

    /**
     * Lock and block process.
     * If new instance of the process will try validate locking state
     * script will wait until process will be unlocked
     */
    public function lockAndBlock()
    {
        flock($this->_getLockFile(), LOCK_EX);

        return $this;
    }

    /**
     * Ð Ð°Ð·Ð»Ð¾ÑÐ¸Ñ ÑÐ°Ð¹Ð»
     *
     * @return object
     */
    public function unlock()
    {
        flock($this->_getLockFile(), LOCK_UN);

        return $this;
    }

    /**
     * ÐÑÐ¾Ð²ÐµÑÑÐµÑ, Ð·Ð°Ð»Ð¾ÑÐµÐ½ Ð»Ð¸ ÑÐ°Ð¹Ð»
     *
     * @return bool
     */
    public function isLocked()
    {
        $fp = $this->_getLockFile();
        if (flock($fp, LOCK_EX | LOCK_NB)) {
            flock($fp, LOCK_UN);
            return false;
        }

        return true;
    }


    public function __destruct()
    {
        if ($this->_lockFile) {
            fclose($this->_lockFile);
        }
    }
}

