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


class Mirasvit_Helpdesk_SatisfactionController extends Mage_Core_Controller_Front_Action
{

    public function formAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function rateAction()
    {
        $rate = $this->getRequest()->getParam('rate');
        $uid = $this->getRequest()->getParam('uid');
        if ($satisfaction = Mage::helper('helpdesk/satisfaction')->addRate($uid, $rate)) {
            $this->_redirect('helpdesk/satisfaction/form', array('uid' => $uid, 'satisfaction' => $satisfaction->getId()));
        }
    }

    public function postAction()
    {
        $uid = $this->getRequest()->getParam('uid');

        $comment = array();

        foreach ($this->getRequest()->getParams() as $key => $value) {
            if ($key != 'uid' && $key != 'satisfaction') {
                $comment[] = ucfirst($key).': '.$value;
            }
        }
        if (count($comment) > 1) {
            $comment = implode(PHP_EOL, $comment);
        } else {
            $comment = $this->getRequest()->getParam('comment');
        }

        if ($comment) {
            Mage::helper('helpdesk/satisfaction')->addComment($uid, $comment);
        }

        $this->loadLayout();
        $this->renderLayout();
    }
}