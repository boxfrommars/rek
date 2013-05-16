<?php
/**
 * Created by JetBrains PhpStorm.
 * User: xu
 * Date: 07.05.13
 * Time: 13:06
 * To change this template use File | Settings | File Templates.
 */

class Default_Model_SearchDocPageAdapter implements Default_Model_SearchDocInterface {
    public function __construct($page)
    {
        $this->_item = $page;
    }

    public function getTitle()
    {
        return $this->_item['title'];
    }

    public function getContent()
    {
        $content = '';
        $content .= $this->_item['content'];
        return $content;
    }

    public function getType()
    {
        return $this->_item['entity'];
    }

    public function getUrl()
    {
        $url = '';
        if (in_array($this->_item['entity'], array('category', 'brand', 'product'))) $url .= '/catalog';
        $url .= $this->_item['url'];
        return $url;
    }

    public function getId()
    {
        return $this->_item['id'];
    }

    public function getDescription()
    {
        return $this->_item['page_description'];
    }

    public function getKeywords()
    {
        return $this->_item['page_keywords'];
    }
}