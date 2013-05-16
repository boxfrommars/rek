<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Page_Model_Service extends Whale_Db_TableCached
{
    protected $_name = 'page';


    public function getBaseSelect() {
        return $this->getDefaultAdapter()->select()->from(
            array('p' => 'page'),
            array('url' => "array_to_string(array_agg(a.page_url ORDER BY a.path), '/')", '*')
        )->joinInner(
            array('a' => 'page'),
            'a.path @> p.path',
            array()
        )->group(
            'p.id',
            'p.path',
            'p.page_url'
        );
    }
}