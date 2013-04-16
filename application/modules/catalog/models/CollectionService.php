<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Catalog_Model_CollectionService extends Whale_Db_TableCached
{
    protected $_name = 'collection';

    public function fetchAll($where = null, $order = null, $limit = null, $offset = null) {
        return $this->getAdapter()->select()
            ->from(
                array('c' => $this->getName()),
                array('*')
            )->joinLeft(
                array('b' => 'brand'),
                'c.id_brand = b.id',
                array(
                    'brand_title' => 'title'
                )
            )->joinLeft(
                array('ct' => 'category'),
                'b.id_category = ct.id',
                array(
                    'category_title' => 'title'
                )
            )->query()->fetchAll();
    }
}