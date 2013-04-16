<?php
/**
 * на основе модулей инициализирует навигацию. кэшабельно
 *
 * @TODO cache navigation
 *
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Whale_Controller_Plugin_NavigationInit extends Whale_Controller_Plugin
{

    protected $_bootstrap;

    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        $navigation = $this->_getNavigation();
        Zend_Registry::set('Zend_Navigation', $navigation);
        $layout = Zend_Layout::getMvcInstance();
        $layout->getView()->navigation($navigation);
    }

    protected function _getNavigation()
    {
        $moduleManager = Whale_Module_Manager::getInstance();
        $navigationPages = array();

        foreach ($moduleManager->getAll() as $module) {
            $navigationPages = $this->_mergeNavigation($navigationPages, $module->getNavigation());
        }

        $navigation = new Zend_Navigation($navigationPages);

        return $navigation;
    }

    protected function _mergeNavigation($pNav, $cNavs)
    {
        if (empty($pNav)) return $cNavs;

        foreach ($pNav as &$page) {
            if (isset($page['extendedAs']) && array_key_exists($page['extendedAs'], $cNavs)) {
                $page['pages'] = (isset($page['pages']) && is_array($page['pages'])) ?
                    array_merge($page['pages'], $cNavs[$page['extendedAs']]) : $cNavs[$page['extendedAs']];
            }

            if (isset($page['pages']) && is_array($page['pages'])) {
                $page['pages'] = $this->_mergeNavigation($page['pages'], $cNavs);
            }
        }

        return $pNav;
    }

}