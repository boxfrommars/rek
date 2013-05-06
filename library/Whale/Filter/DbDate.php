<?php
/**
 * @author Dmitry Groza <boxfrommars@gmail.com>
 */

class Whale_Filter_DbDate implements Zend_Filter_Interface
{
    public function filter($value)
    {
        return implode('-', array_reverse(explode('/', $value)));
    }
}