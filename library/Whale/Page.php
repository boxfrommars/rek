<?php

class Whale_Page {

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
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->_description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * @param string $humanId
     */
    public function setHumanId($humanId)
    {
        $this->_humanId = $humanId;
    }

    /**
     * @return string
     */
    public function getHumanId()
    {
        return $this->_humanId ?: $this->getId();
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param string $tags
     */
    public function setTags($tags)
    {
        $this->_tags = $tags;
    }

    /**
     * @return string
     */
    public function getTags()
    {
        return $this->_tags;
    }

    /**
     * @param string $title
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