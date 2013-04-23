<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Catalog_Model_ProductService extends Whale_Db_TableCached
{
    protected $_name = 'product';

    public function getBaseSelect($where = null) {
        $select = $this->getAdapter()->select()
            ->from(
                array('p' => $this->getName()),
                array('*')
            )->joinLeft(
                array('b' => 'brand'),
                'p.id_brand = b.id',
                array(
                    'brand_title' => 'title',
                    'id_brand' => 'id',
                )
            )->joinLeft(
                array('ct' => 'category'),
                'b.id_category = ct.id',
                array(
                    'category_title' => 'title',
                    'id_category' => 'id',
                )
            )->joinLeft(
                array('s' => 'surface'),
                'p.id_surface = s.id',
                array(
                    'surface_title' => 'title',
                )
            )->joinLeft(
                array('c' => 'country'),
                'p.id_country = c.id',
                array(
                    'country_title' => 'title',
                )
            );

        if (null !== $where) {
            foreach ($where as $key => $value) {
                if ($key == 'is_published = ?') {
                    $key = 'p.is_published = ?';
                }
                $select->where($key, $value);
            }
        }

        return $select;
    }

    public function fetch($where) {
        $select = $this->getBaseSelect($where);
        return $select->query()->fetch();
    }

    public function fetchAll($where = null, $order = null, $limit = null, $offset = null) {
        $select = $this->getBaseSelect($where);

        if (null !== $limit) {
            $select->limit($limit);
        }
        return $select->query()->fetchAll();
    }
}