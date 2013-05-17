<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Catalog_Model_BrandService extends Whale_Db_TableCached
{
    protected $_name = 'brand';
    protected $_sequence = false;

    public function fetchAll($where = null, $order = null, $limit = null, $offset = null) {
        return $this->getAdapter()->select()
            ->from(
                array('b' => $this->getName()),
                array('*')
            )->joinLeft(
                array('ct' => 'category'),
                'b.id_parent = ct.id',
                array(
                    'category_title' => 'title'
                )
            )->query()->fetchAll();
    }
}