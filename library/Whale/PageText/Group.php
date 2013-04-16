<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Whale_PageText_Group
{
    /**
     * @var Whale_PageText_Item[]
     */
    protected $_texts = array();

    public function __construct($pageTextsData)
    {
        foreach ($pageTextsData as $pageTextData) {
            $this->_texts[$pageTextData['mark']] = new Whale_PageText_Item($pageTextData['title'], $pageTextData['content']);
        }
    }

    /**
     * @param $mark string
     *
     * @return Whale_PageText_Item
     */
    public function get($mark)
    {
        return array_key_exists($mark, $this->_texts) ? $this->_texts[$mark] : new Whale_PageText_Item();
    }
}