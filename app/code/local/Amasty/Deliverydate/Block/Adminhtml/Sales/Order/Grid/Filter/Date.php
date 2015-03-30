<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Block_Adminhtml_Sales_Order_Grid_Filter_Date extends Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Date
{
    public function getHtml()
    {
        $htmlId = $this->_getHtmlId() . microtime(true);
        $format = $this->getLocale()->getDateStrFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
        $html = '<div class="range"><div class="range-line date">'
            . '<span class="label">' . Mage::helper('adminhtml')->__('From').':</span>'
            . '<input type="text" name="'.$this->_getHtmlName().'[from]" id="'.$htmlId.'_from"'
                . ' value="'.$this->getEscapedValue('from').'" class="input-text no-changes"/>'
            . '<img src="' . Mage::getDesign()->getSkinUrl('images/grid-cal.gif') . '" alt="" class="v-middle"'
                . ' id="'.$htmlId.'_from_trig"'
                . ' title="' . $this->escapeHtml(Mage::helper('adminhtml')->__('Date selector')) . '"/>'
            . '<input type="hidden" name="'.$this->_getHtmlName().'[from_hidden]" id="'.$htmlId.'_from_hidden"'
                . ' value="'.$this->getEscapedValue('from_hidden').'"/>'
            . '</div>';
        $html.= '<div class="range-line date">'
            . '<span class="label">' . Mage::helper('adminhtml')->__('To').' :</span>'
            . '<input type="text" name="'.$this->_getHtmlName().'[to]" id="'.$htmlId.'_to"'
                . ' value="'.$this->getEscapedValue('to').'" class="input-text no-changes"/>'
            . '<img src="' . Mage::getDesign()->getSkinUrl('images/grid-cal.gif') . '" alt="" class="v-middle"'
                . ' id="'.$htmlId.'_to_trig"'
                . ' title="'.$this->escapeHtml(Mage::helper('adminhtml')->__('Date selector')).'"/>'
            . '<input type="hidden" name="'.$this->_getHtmlName().'[to_hidden]" id="'.$htmlId.'_to_hidden"'
                . ' value="'.$this->getEscapedValue('to_hidden').'"/>'
            . '</div></div>';
        $html.= '<input type="hidden" name="'.$this->_getHtmlName().'[locale]"'
            . 'value="'.$this->getLocale()->getLocaleCode().'"/>';
        $html.= '<script type="text/javascript">
            Calendar.setup({
                inputField : "'.$htmlId.'_from",
                ifFormat : "'.$format.'",
                button : "'.$htmlId.'_from_trig",
                align : "Bl",
                singleClick : true
            });
            Calendar.setup({
                inputField : "'.$htmlId.'_to",
                ifFormat : "'.$format.'",
                button : "'.$htmlId.'_to_trig",
                align : "Bl",
                singleClick : true
            });

            $("'.$htmlId.'_to_trig").observe("click", showCalendar);
            $("'.$htmlId.'_from_trig").observe("click", showCalendar);

            function showCalendar(event){
                var element = event.element(event);
                var offset = $(element).viewportOffset();
                var scrollOffset = $(element).cumulativeScrollOffset();
                var dimensionsButton = $(element).getDimensions();
                var index = $("widget-chooser").getStyle("zIndex");

                $$("div.calendar").each(function(item){
                    if ($(item).visible()) {
                        var dimensionsCalendar = $(item).getDimensions();

                        $(item).setStyle({
                            "zIndex" : index + 1,
                            "left" : offset[0] + scrollOffset[0] - dimensionsCalendar.width
                                + dimensionsButton.width + "px",
                            "top" : offset[1] + scrollOffset[1] + dimensionsButton.height + "px"
                        });
                    };
                });
            };
        </script>';
        return $html;
    }
    
    public function setValue($value)
    {
        if (isset($value['locale'])) {
            if (!empty($value['from'])) {
                $value['orig_from'] = $value['from'];
                $value['orig_from_hidden'] = $value['from_hidden'];
                $value['from'] = $this->_convertDate($value['from'], $value['locale']);
            }
            if (!empty($value['to'])) {
                $value['orig_to'] = $value['to'];
                $value['orig_to_hidden'] = $value['to_hidden'];
                $value['to'] = $this->_convertDate($value['to'], $value['locale']);
            }
        }
        if (empty($value['from']) && empty($value['to'])) {
            $value = null;
        }
        $this->setData('value', $value);
        return $this;
    }
}