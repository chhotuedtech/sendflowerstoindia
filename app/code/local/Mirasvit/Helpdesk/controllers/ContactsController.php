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


require Mage::getBaseDir('app').'/code/core/Mage/Contacts/controllers/IndexController.php';
class Mirasvit_Helpdesk_ContactsController extends Mage_Contacts_IndexController
{
    public function indexAction()
    {
        if (Mage::getSingleton('helpdesk/config')->getGeneralContactUsIsActive()) {
            if (Mage::getSingleton('customer/session')->getCustomer()->getId()){
                $this->_redirect('helpdesk/ticket');
                return;
            }
        }
        // $this->loadLayout();
        $update = $this->getLayout()->getUpdate();
        $update->addHandle('default');
        $this->addActionLayoutHandles();
        $update->addHandle('contacts_index_index');
        $this->loadLayoutUpdates();
        $this->generateLayoutXml();
        $this->generateLayoutBlocks();
        $this->_isLayoutLoaded = true;

        $this->getLayout()->getBlock('contactForm')
            ->setFormAction( Mage::getUrl('*/*/post') );

        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');
        $this->renderLayout();
    }

    public function postAction()
    {
        $post = $this->getRequest()->getPost();
        if ( $post ) {
            $translate = Mage::getSingleton('core/translate');
             //@var $translate Mage_Core_Model_Translate
            $translate->setTranslateInline(false);
            try {
                $postObject = new Varien_Object();
                $postObject->setData($post);

                $error = false;

                if (!Zend_Validate::is(trim($post['name']) , 'NotEmpty')) {
                    $error = true;
                }

                if (!Zend_Validate::is(trim($post['comment']) , 'NotEmpty')) {
                    $error = true;
                }

                if (!isset($post['mail']) && !Zend_Validate::is(trim($post['email']), 'EmailAddress')) {
                    $error = true;
                }

                if (Zend_Validate::is(trim($post['hideit']), 'NotEmpty')) {
                    $error = true;
                }

                if ($error) {
                    throw new Exception();
                }
                if (Mage::getSingleton('helpdesk/config')->getGeneralContactUsIsActive()) {
                    //POST
                    //name
                    //email
                    //comment
                    //telephone
                    //priority_id
                    //department_id
    	            $params = array();
    	            $params['name'] = $post['subject'];
    	            $params['message'] = $post['comment'];
    	            if ($phone = $post['telephone']) {
    	            	$params['message'] .= "\n".Mage::helper('helpdesk')->__('Telephone').": ".$phone;
    	            }
                    if (isset($post['priority_id'])) {
                        $params['priority_id'] = $post['priority_id'];
                    }
                    if (isset($post['department_id'])) {
                        $params['department_id'] = $post['department_id'];
                    }

                    $params['customer_name'] = $post['name'];
                    $params['customer_email'] = $post['mail'];
                    $collection = Mage::helper('helpdesk/field')->getContactFormCollection();
                    foreach ($collection as $field) {
                        if (isset($post[$field->getCode()])) {
                            $params[$field->getCode()] = $post[$field->getCode()];
                        }
                    }
                    if (empty($post['email'])) { //spam protection
                        Mage::helper('helpdesk/process')->createFromPost($params, Mirasvit_Helpdesk_Model_Config::CHANNEL_CONTACT_FORM);
                    }
                } else {
                    $mailTemplate = Mage::getModel('core/email_template');
                    /* @var $mailTemplate Mage_Core_Model_Email_Template */
                    $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                        ->setReplyTo($post['email'])
                        ->sendTransactional(
                            Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE),
                            Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                            Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT),
                            null,
                            array('data' => $postObject)
                        );

                    if (!$mailTemplate->getSentSuccess()) {
                        throw new Exception();
                    }
                    $translate->setTranslateInline(true);
                }
                Mage::getSingleton('customer/session')->addSuccess(Mage::helper('contacts')->__('Your inquiry was submitted and will be responded to as soon as possible. Thank you for contacting us.'));
                $this->_redirect('contacts/index/');

                return;
            } catch (Mage_Exception $e) {
                $translate->setTranslateInline(true);
                // throw $e;

                Mage::getSingleton('customer/session')->addError(Mage::helper('contacts')->__('Unable to submit your request. Please, try again later'));
                $this->_redirect('contacts/index/');
                return;
            }

        } else {
            $this->_redirect('contacts/index/');
        }
    }
}