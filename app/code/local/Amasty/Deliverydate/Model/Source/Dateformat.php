<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Deliverydate
*/
class Amasty_Deliverydate_Model_Source_Dateformat
{
    public function toOptionArray() 
    {
        return array(
            array(
                'value' => 'yyyy-MM-dd',
                'label' => 'yyyy-mm-dd (' . date('Y-m-d') . ')'
            ),
            array(
                'value' => 'MM/dd/yyyy',
                'label' => 'mm/dd/yyyy (' . date('m/d/Y') . ')'
            ),
            array(
                'value' => 'dd/MM/yyyy',
                'label' => 'dd/mm/yyyy (' . date('d/m/Y') . ')'
            ),
            array(
                'value' => 'd/M/yy',
                'label' => 'd/m/yy (' . date('j/n/y') . ')'
            ),
            array(
                'value' => 'd/M/yyyy',
                'label' => 'd/m/yyyy (' . date('j/n/Y') . ')'
            ),
            array(
                'value' => 'dd.MM.yyyy',
                'label' => 'dd.mm.yyyy (' . date('d.m.Y') . ')'
            ),
            array(
                'value' => 'dd.MM.yy',
                'label' => 'dd.mm.yy (' . date('d.m.y') . ')'
            ),
            array(
                'value' => 'd.M.yy',
                'label' => 'd.m.yy (' . date('j.n.y') . ')'
            ),
            array(
                'value' => 'd.M.yyyy',
                'label' => 'd.m.yyyy (' . date('j.n.Y') . ')'
            ),
            array(
                'value' => 'dd-MM-yy',
                'label' => 'dd-mm-yy (' . date('d-m-y') . ')'
            ),
            array(
                'value' => 'yyyy.MM.dd',
                'label' => 'yyyy.mm.dd (' . date('Y.m.d') . ')'
            ),
            array(
                'value' => 'dd-MM-yyyy',
                'label' => 'dd-mm-yyyy (' . date('d-m-Y') . ')'
            ),
            array(
                'value' => 'yyyy/MM/dd',
                'label' => 'yyyy/mm/dd (' . date('Y/m/d') . ')'
            ),
            array(
                'value' => 'yy/MM/dd',
                'label' => 'yy/mm/dd (' . date('y/m/d') . ')'
            ),
            array(
                'value' => 'dd/MM/yy',
                'label' => 'dd/mm/yy (' . date('d/m/y') . ')'
            ),
            array(
                'value' => 'MM/dd/yy',
                'label' => 'mm/dd/yy (' . date('m/d/y') . ')'
            ),
            array(
                'value' => 'dd/MM yyyy',
                'label' => 'dd/mm yyyy (' . date('d/m Y') . ')'
            ),
            array(
                'value' => 'yyyy MM dd',
                'label' => 'yyyy mm dd (' . date('Y m d') . ')'
            ),
        );
    }
}