<?php
/**
 * Class Whale_Node_Tree
 */
class Whale_Node_Tree {

    protected $_data;
    protected $_tree;

    public function __construct($arr, $rootPath) {
        $this->_data = $arr;

        foreach ($arr as $node) {
            $this->_addNode($node);
        }
    }

    protected function _addNode($node)
    {
        $parentNodePath = implode('.', array_pop(explode('.', $node['path'])));
        $this->_getNode();
    }
}