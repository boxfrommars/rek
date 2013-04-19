<?php

class Whale_Page_StandardAdapter implements Whale_Page_Interface  {

    protected $_item;

    public function __construct($item) {
        $this->_item = $item;
    }

    public function getTitle() {
        return $this->_item['title'];
    }

    public function getId() {
        return $this->_item['id'];
    }

    public function getHumanId() {
        return $this->_item['name'] ?: $this->_item['id'];
    }

    public function getDescription() {
        return $this->_item['description'];
    }

    public function getKeywords() {
        return $this->_item['keywords'];
    }
}