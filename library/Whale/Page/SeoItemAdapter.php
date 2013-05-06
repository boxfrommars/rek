<?php

class Whale_Page_SeoItemAdapter implements Whale_Page_Interface  {

    protected $_item;

    public function __construct($item) {
        $this->_item = $item;
    }

    public function getTitle() {
        return empty($this->_item['page_title']) ?: $this->_item['title'];
    }

    public function getId() {
        return $this->_item['id'];
    }

    public function getHumanId() {
        return $this->_item['page_url'] ?: $this->_item['id'];
    }

    public function getDescription() {
        return $this->_item['page_description'] ?: '';
    }

    public function getKeywords() {
        return $this->_item['page_keywords'];
    }
}