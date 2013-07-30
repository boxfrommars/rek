<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Catalog_Model_ProductInterService extends Whale_Db_TableCached
{
    protected $_name = 'product_inter';

    public function fetchAll($where = null, $order = null, $limit = null, $offset = null) {
        $select = $this->getAdapter()->select()
            ->from(
                array('pc' => $this->getName()),
                array('*')
            )->joinLeft(
                array('p' => 'product'),
                'pc.id_product = p.id',
                array(
                    'product_title' => 'title',
                )
            )->joinLeft(
                array('b' => 'brand'),
                'p.id_parent = b.id',
                array(
                    'brand_title' => 'title',
                )
            )->joinLeft(
                array('ct' => 'category'),
                'b.id_parent = ct.id',
                array(
                    'category_title' => 'title',
                )
            );

        if (is_array($where)) {
            foreach ($where as $key => $value) {
                $select->where($key, $value);
            }
        } else if (is_scalar($where)) {
            $select->where($where);
        }
        return $select->query()->fetchAll();
    }
}