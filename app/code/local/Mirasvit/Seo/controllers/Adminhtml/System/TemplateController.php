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


/*******************************************
Mirasvit
This source file is subject to the Mirasvit Software License, which is available at http://mirasvit.com/license/.
Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
If you wish to customize this module for your needs
Please refer to http://www.magentocommerce.com for more information.
@category Mirasvit
@copyright Copyright (C) 2012 Mirasvit (http://mirasvit.com.ua), Vladimir Drok <dva@mirasvit.com.ua>, Alexander Drok<alexander@mirasvit.com.ua>
*******************************************/

class Mirasvit_Seo_Adminhtml_System_TemplateController extends Mage_Adminhtml_Controller_Action
{
    public function preDispatch()
    {
        parent::preDispatch();
        Mage::getDesign()->setTheme('mirasvit');
        return $this;
    }

    public function applyUrlTemplateAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function applyUrlTemplateStepAction()
    {
        if ($step = $this->getRequest()->getParam('step')) {
            $worker = Mage::getSingleton('seo/system_template_worker');
            $worker->setStep($step);
            if ($worker->run()) {
                $this->loadLayout();
                $this->renderLayout();
            }
        }
    }
}