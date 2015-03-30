<?php

class Magentomasters_Supplier_Model_Product extends Mage_Core_Model_Abstract {

    public function getSelectOptions($selectId){
        $connection = Mage::getSingleton( 'core/resource' )->getConnection('core_read');
        $query = "SELECT ov.option_id AS `value`,ov.value AS `option` FROM mage_eav_attribute_option AS o
		INNER JOIN mage_eav_attribute_option_value AS ov
                    ON o.option_id=ov.option_id

                    WHERE o.attribute_id = $selectId AND ov.store_id=0";
        $result = $connection->query($query);
        return $result->fetchAll();
    }

	public function getDinamicTaxClasses() {
        $connect = Mage::getSingleton( 'core/resource' )->getConnection('core_read');
        $result = $connect->query( "
                SELECT `tax`.class_id as `id`,`tax`.class_name as `name`
                FROM " . Mage::getSingleton('core/resource')->getTableName('tax_class') . " as `tax`
                WHERE `tax`.class_type = 'PRODUCT'
                ORDER BY `tax`.class_id
                " );
        return $result->fetchAll();
    }
}

?>