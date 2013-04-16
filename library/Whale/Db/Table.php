<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Whale_Db_Table extends Zend_Db_Table
{
    /**
     * return tablename with schema
     *
     * @return string schema.name
     */
    public function getSName()
    {
        $name = '';
        if (!empty($this->_schema)) {
            $name .= $this->_schema . ".";
        }
        if (!empty($this->_name)) {
            $name .= $this->_name;
        }

        return $name;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getSchema()
    {
        return $this->_schema;
    }

}