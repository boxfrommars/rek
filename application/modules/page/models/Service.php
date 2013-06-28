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

    public function getPage($where) {

        $select = $this->getDefaultAdapter()->select()
            ->from(array('s' => $this->getBaseSelect()), '*')
            ->where('is_published');

        $select = $this->setSelectWhere($select, $where);

        $page = $select->query()->fetch();
        if (empty($page)) return null;

        $parentsSelect = $this->getDefaultAdapter()->select()->from(array('s' => $this->getBaseSelect()), '*')->where('path @> ?', $page['path'])->order(array('path'));
        Whale_Log::log($parentsSelect->assemble());

        $parents = array_slice($parentsSelect->query()->fetchAll(), 0, -1);

        foreach ($parents as $parent) {
            if (!$parent['is_published']) {
                return null;
            }
        }

        $page['parents'] = $parents;

        return $page;
    }
}