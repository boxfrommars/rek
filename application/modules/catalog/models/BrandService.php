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
        $select =  $this->getAdapter()->select()
            ->from(
                array('b' => $this->getName()),
                array('*')
            )->joinLeft(
                array('ct' => 'category'),
                'b.id_parent = ct.id',
                array(
                    'category_title' => 'title'
                )
            );
        if (!empty($where)) {
            $select = $this->setSelectWhere($select, $where);
        }


        if (null !== $order) $select->order($order);
        return $select->query()->fetchAll();
    }
}