<?php
class Whale_Get {

    /**
     * @param string $resourceName
     * @return Zend_Application_Resource_ResourceAbstract
     */
    public static function resource($resourceName) {
        return Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource($resourceName);
    }

    /**
     * @param string $option
     * @return array options from bootstrap (eg. application.ini) config
     */
    public static function option($option) {
        return Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOption($option);
    }

    /**
     * @param string $cacheName
     * @return Zend_Cache_Core
     */
    public static function cache($cacheName) {
        /** @var $cacheManager Zend_Cache_Manager */
        $cacheManager = self::resource('cachemanager');
        return $cacheManager->getCache($cacheName);
    }
}