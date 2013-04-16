<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Catalog_Model_ProductService extends Whale_Db_TableCached
{
    protected $_name = 'product';

    public function fetchAll($where = null, $order = null, $limit = null, $offset = null) {
        return $this->getAdapter()->select()
            ->from(
                array('p' => $this->getName()),
                array('*')
            )->joinLeft(
                array('c' => 'collection'),
                'p.id_collection = c.id',
                array(
                    'collection_title' => 'title'
                )
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