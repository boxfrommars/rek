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
    protected static $_startTime = null;

    /**
     * @param $message
     * @param int $priority
     */
    public static function log($message, $priority = Zend_Log::INFO)
    {

        if (null === self::$_log) {
            self::$_log = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('Log');
        }
        $message = is_scalar($message) ? $message : print_r($message, true);
        self::$_log->log((int)((microtime(true) - self::$_startTime) * 1000) . 'ms ' . $message, $priority);
    }

    public static function setStartTime($microtime)
    {
        self::$_startTime = $microtime;
    }
}