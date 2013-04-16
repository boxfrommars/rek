<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Whale_PageText_Item
{
    /**
     * @var string
     */
    protected $_content = '';

    /**
     * @var string
     */
    protected $_title = '';

    /**
     * @param $title string
     * @param $content string
     */
    public function __construct($title = '', $content = '')
    {
        $this->setTitle($title);
        $this->setContent($content);
    }

    /**
     * @param $content string
     */
    public function setContent($content)
    {
        $this->_content = $content;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->_content;
    }

    /**
     * @param $title string
     */
    public function setTitle($title)
    {
        $this->_title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }
}