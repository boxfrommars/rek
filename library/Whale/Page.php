<?php

class Whale_Page implements Whale_Page_Interface {

    /**
     * @var string
     */
    protected $_title;

    /**
     * @var string
     */
    protected $_humanId;

    /**
     * @var int
     */
    protected $_id;

    /**
     * @var string
     */
    protected $_description;

    /**
     * @var string
     */
    protected $_tags;

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * @return string
     */
    public function getHumanId()
    {
        return $this->_humanId ?: $this->getId();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @return string
     */
    public function getKeywords()
    {
        return $this->_tags;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->_title;
    }



}