<?php

class Whale_Router_Page extends Zend_Controller_Router_Route {

    public $db = null;

    public static function getInstance(Zend_Config $config)
    {
        $reqs = ($config->reqs instanceof Zend_Config) ? $config->reqs->toArray() : array();
        $defs = ($config->defaults instanceof Zend_Config) ? $config->defaults->toArray() : array();
        return new self($config->route, $defs, $reqs);
    }

    public function match($path, $partial = false)
    {
        if ($path instanceof Zend_Controller_Request_Http) {
            $path = $path->getPathInfo();
        }

        if (!$partial) {
            $path = trim($path, $this->_urlDelimiter);
        }

        $db = Zend_Db_Table::getDefaultAdapter();

        $select = $db->select()->from(
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

//        Whale_Log::log($select->query()->fetchAll());
        $nextSelect = $db->select()->from(array('s' => $select), '*')->where('url = ?', '/' . $path)->where('NOT is_locked');
        $result = $nextSelect->query()->fetch();
//        Whale_Log::log($result);
        return empty($result) ? false : array('page' => $result) + $this->_defaults;
    }

    public function assemble($data = array(), $reset = false, $encode = false)
    {
        return $data['page_url'];
    }
}