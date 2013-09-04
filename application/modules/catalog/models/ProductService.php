<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Catalog_Model_ProductService extends Whale_Db_TableCached
{
    protected $_name = 'product';
    protected $_sequence = 'page_id_seq';

    public function getBaseSelect($where = null) {
        $select = $this->getAdapter()->select()
            ->from(
                array('p' => $this->getName()),
                array('*')
            )->joinLeft(
                array('b' => 'brand'),
                'p.id_parent = b.id',
                array(
                    'brand_title' => 'title',
                    'brand_page_url' => 'page_url',
                    'id_brand' => 'id',
                )
            )->joinLeft(
                array('ct' => 'category'),
                'b.id_parent = ct.id',
                array(
                    'category_title' => 'title',
                    'category_page_url' => 'page_url',
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

    public function fetchAllColored($where = null, $order = null, $limit = null, $offset = null) {
        $select = $this->getBaseSelect($where);

        $select->joinLeft(
            array('clr' => 'product_color'),
            'clr.id_product = p.id',
            array('color_image' => 'image', 'color_id' => 'id', 'color_image_preview' => 'image_preview', 'color_cost' => 'cost', 'color_id_surface' => 'id_surface', 'color_title' => 'title')
        )->joinLeft(
                array('clrs' => 'surface'),
                'clr.id_surface = clrs.id',
                array(
                    'color_surface_title' => 'title',
                )
            );

        if (null !== $limit) {
            $select->limit($limit);
        }

        if (null !== $order) {
            $select->order($order);
        }
        return $select->query()->fetchAll();
    }
}