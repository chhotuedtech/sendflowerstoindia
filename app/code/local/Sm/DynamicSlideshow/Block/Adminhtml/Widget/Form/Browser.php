<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Block_Adminhtml_Widget_Form_Browser extends Sm_DynamicSlideshow_Block_Adminhtml_Widget_Form_Browsers
{
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $browserBtn = $this->getLayout()->createBlock( 'adminhtml/widget_button', 'button', array(
            'label' => '...',
            'title' => Mage::helper( 'dynamicslideshow' )->__( 'Click to browser media' ),
            'type' => 'button',
            'onclick' => sprintf( '_MediabrowserUtility.openDialog(\'%s\', \'browserVideoWindow\', null, null, \'%s\')', Mage::getSingleton( 'adminhtml/url' )->getUrl( 'adminhtml/cms_wysiwyg_images/index', array(
                 'static_urls_allowed' => 1,
                'target_element_id' => $this->getElement()->getHtmlId(),
                'type' => 'media',
                'onInsertCallback' => 'DnmSl.onSelectHtml5Video',
                'onInsertCallbackParams' => 'browserVideoWindow' 
            ) ), Mage::helper( 'dynamicslideshow' )->__( 'Select Video' ) ) 
        ) );
        $this->setChild( 'browserBtn', $browserBtn );
        return $this;
    }
}