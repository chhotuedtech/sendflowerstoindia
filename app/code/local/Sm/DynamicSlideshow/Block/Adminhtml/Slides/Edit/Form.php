<?php
/**
 * @package SM Dynamic Slideshow 
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */

class Sm_DynamicSlideshow_Block_Adminhtml_Slides_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
	{
        $form = new Varien_Data_Form(array(
            'id'        => 'smdnsl_slides_form',
            'action'    => $this->getUrl('*/*/saveSlides'),
            'method'    => 'post'
        ));
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}