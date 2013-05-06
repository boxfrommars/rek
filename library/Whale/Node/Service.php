<?php

class Whale_Node_Service {
    protected $_name = 'page';

    /**
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_db;

    public function __construct() {
        $this->_db = Zend_Db_Table::getDefaultAdapter();
    }

    public function get($path)
    {
        $select = $this->_db->select()->from(
            array('p' => $this->_name),
            '*'
        )->where('p.path <@ ?', $path);
        Whale_Log::log($select->assemble());
        $result = $select->query()->fetchAll();
        return $result;
    }
}