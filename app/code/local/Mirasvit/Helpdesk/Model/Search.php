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


class Mirasvit_Helpdesk_Model_Search extends Varien_Object
{
    protected $_collection = null;
    protected $_attributes = null;
    protected $_primaryKey = null;

    public function setSearchableCollection($collection)
    {
        $this->_collection = $collection;

        return $this;
    }

    public function setSearchableAttributes($attributes)
    {
        $this->_attributes = $attributes;

        return $this;
    }

    public function setPrimaryKey($key)
    {
        $this->_primaryKey = $key;
    }


    protected function _getMatchedIds($query)
    {
        if (!is_array($this->_attributes) || !count($this->_attributes)) {
            Mage::throwException('Searchable attributes not defined');
        }

        $query = Mage::helper('core/string')->splitWords($query, true, 100);
        $select = $this->_collection->getSelect();

        $having = array();
        foreach ($query as $word) {
            $subhaving = array();
            foreach ($this->_attributes as $attr => $weight) {
                $subhaving[] = $this->_getCILike($attr, $word, array('position' => 'any'));
            }
            $having[] = '('.implode(' OR ', $subhaving).')';
        }

        $havingCondition = implode(' AND ', $having);

        if ($havingCondition != '') {
            $select->having($havingCondition);
        }

        $read = Mage::getSingleton('core/resource')->getConnection('core_read');

        $stmt = $read->query($select);
        $result = array();
        while ($row = $stmt->fetch(Zend_Db::FETCH_ASSOC)) {
            $result[$row[$this->_primaryKey]] = 0;
        }

        return $result;
    }


    protected function _getCILike($field, $value, $options = array(), $type = 'LIKE')
    {
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');;
        $quotedField = $read->quoteIdentifier($field);

        return $quotedField.' '.$type.' "'.$this->_escapeLikeValue($value, $options).'"';
    }

    protected function _escapeLikeValue($value, $options = array())
    {
        $value = addslashes($value);

        $from = array();
        $to = array();
        if (empty($options['allow_string_mask'])) {
            $from[] = '%';
            $to[] = '\%';
        }
        if ($from) {
            $value = str_replace($from, $to, $value);
        }

        if (isset($options['position'])) {
            switch ($options['position']) {
                case 'any':
                    $value = '%' . $value . '%';
                    break;
                case 'start':
                    $value = $value . '%';
                    break;
                case 'end':
                    $value = '%' . $value;
                    break;
            }
        }

        return $value;
    }

    public function joinMatched($query, $collection, $mainTableKeyField = 'e.entity_id')
    {
        $matchedIds = $this->_getMatchedIds($query);
        $this->_createTemporaryTable($matchedIds);

        $collection->getSelect()->joinLeft(
            array('tmp_table' => $this->_getTemporaryTableName()),
            '(tmp_table.entity_id='.$mainTableKeyField.')',
            array('relevance' => 'tmp_table.relevance')
        );

        $collection->getSelect()->where('tmp_table.id IS NOT NULL');

        return $this;
    }

    protected function _createTemporaryTable($matchedIds)
    {
        $values = array();

        foreach ($matchedIds as $id => $relevance) {
            $values[] = '('.$id.','.$relevance.')';
        }

        $connection = Mage::getSingleton('core/resource')->getConnection('core_read');

        $query = '';
        $query .= "CREATE TEMPORARY TABLE IF NOT EXISTS `".$this->_getTemporaryTableName()."` ("
                ."`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `entity_id` int(11) unsigned NOT NULL,
                `relevance` int(11) unsigned NOT NULL,
                PRIMARY KEY (`id`)
                )ENGINE=MYISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

        if (count($values)) {
            $query .= "INSERT INTO `".$this->_getTemporaryTableName()."` (`entity_id`, `relevance`)".
                "VALUES ".implode(',', $values).";";
        }

        $connection->raw_query($query);

        return $this;
    }

    protected function _getTemporaryTableName()
    {
        return 'search_results';
    }
}