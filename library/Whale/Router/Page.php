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

        $pageService = new Page_Model_Service();
        $select = $pageService->getBaseSelect();

        $nextSelect = $pageService->getAdapter()->select()->from(array('s' => $select), '*')->where('url = ?', '/' . $path)->where('NOT is_locked')->where('is_published');
        $result = $nextSelect->query()->fetch();
        return empty($result) ? false : array('page' => $result) + $this->_defaults;
    }

    public function assemble($data = array(), $reset = false, $encode = false)
    {
        return $data['page_url'];
    }
}