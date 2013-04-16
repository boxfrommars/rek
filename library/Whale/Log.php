<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Whale_Log
{
    /**
     * @var Whale_Log
     */
    protected static $_log = null;

    /**
     * @param $message
     * @param int $priority
     */
    public static function log($message, $priority = Zend_Log::INFO)
    {

        if (null === self::$_log) {
            self::$_log = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('Log');
        }

        self::$_log->log(is_scalar($message) ? $message : print_r($message, true), $priority);
    }
}