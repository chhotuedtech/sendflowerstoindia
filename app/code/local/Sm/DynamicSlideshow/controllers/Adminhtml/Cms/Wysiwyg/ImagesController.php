<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

require_once 'Mage/Adminhtml/controllers/Cms/Wysiwyg/ImagesController.php';

class Sm_DynamicSlideshow_Adminhtml_Cms_Wysiwyg_ImagesController extends Mage_Adminhtml_Cms_Wysiwyg_ImagesController
{
    public function indexAction()
    {
        if ( $this->getRequest()->getParam( 'static_urls_allowed' ) )
        {
            $this->_getSession()->setStaticUrlsAllowed( true );
        }
        parent::indexAction();
    }
    
    public function onInsertAction()
    {
        parent::onInsertAction();
        $this->_getSession()->setStaticUrlsAllowed();
    }
}