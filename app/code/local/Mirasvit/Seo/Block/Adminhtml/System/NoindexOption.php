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


class Mirasvit_Seo_Block_Adminhtml_System_NoindexOption extends Mage_Core_Block_Html_Select
{

    protected function _getOptions($groupId = null)
    {
        return array(
            Mirasvit_Seo_Model_Config::NOINDEX_FOLLOW =>"NOINDEX, FOLLOW",
            Mirasvit_Seo_Model_Config::INDEX_NOFOLLOW =>"INDEX, NOFOLLOW",
            Mirasvit_Seo_Model_Config::NOINDEX_NOFOLLOW =>"NOINDEX, NOFOLLOW"
        );
    }

    public function setInputName($value)
    {
        return $this->setName($value);
    }

    public function _toHtml()
    {
        if (!$this->getOptions()) {
            foreach ($this->_getOptions() as $groupId => $groupLabel) {
                $this->addOption($groupId, addslashes($groupLabel));
            }
        }

        return parent::_toHtml();
    }

    public function calcOptionHash($optionValue)
    {
        return sprintf('%u', crc32($this->getName() . $this->getId() . $optionValue));
    }

    protected function _optionToHtml($option, $selected = false)
    {
        $selectedHtml = $selected ? ' selected="selected"' : '';
        if ($this->getIsRenderToJsTemplate() === true) {
            $selectedHtml .= ' #{option_extra_attr_' . self::calcOptionHash($option['value']) . '}';
        }
        $html = '<option value="'.$this->htmlEscape($option['value']).'"'.$selectedHtml.'>'.$this->htmlEscape($option['label']).'</option>';
        return $html;
    }
}