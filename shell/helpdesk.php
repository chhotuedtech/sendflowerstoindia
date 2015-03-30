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


require_once 'abstract.php';

class Mirasvit_Shell_Helpdesk extends Mage_Shell_Abstract
{
    /**
     * Run script
     *
     */
    public function run()
    {
        if ($this->getArg('reprocess')) {
            $emails = Mage::getModel('helpdesk/email')->getCollection();
            foreach ($emails as $email) {
                Mage::helper('helpdesk/process')->processEmail($email);
            }
        } elseif ($this->getArg('fast')) {
            $cron = Mage::getModel('helpdesk/cron');
            $cron->run(true);
        } else {
            $cron = Mage::getModel('helpdesk/cron');
            $cron->run();
        }
    }
}

$shell = new Mirasvit_Shell_Helpdesk();
$shell->run();
