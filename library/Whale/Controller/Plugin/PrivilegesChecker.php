<?php
/**
 * @TODO если ресурса не существует, то логично бы было сделать редирект на 404
 *
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Whale_Controller_Plugin_PrivilegesChecker extends Zend_Controller_Plugin_Abstract
{
    /**
     * роут на который перебрасываем при
     * @var string
     */
    protected $_deniedRoute = 'login';
    protected $_noResourceRoute = ''; // @TODO смотри туду к этому плагину

    /**
     * проверяем существует ли ресурс и роль, если существует, то проверям есть ли у пользователя права
     * для доступа к данному ресурсу. если есть, то ничего не делаем (позволяем дальше диспатчиться),
     * если же прав нет, то что-то делаем. на данный момент роутим на страницу логина,
     * но вполне можно сделать что-нибудь по-интереснее
     *
     * @var Zend_Controller_Request_Abstract $request
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $acl = Zend_Registry::get('ACL');
        $module = $request->getModuleName();
        $module = $module ? $module : 'default';
        $controller = $request->getControllerName();
        $action = $request->getActionName();

        $resource = $module . ':' . $controller;
        $privilege = $action;

        $user = Whale_User_Current::get();
        $role = $user->getRole();
        Whale_Log::log('role: ' . $user->getRole() . ' / resource: ' . $resource . ' / privelege: ' . $privilege);

        $resourceExist = $acl->has($resource);
        Whale_Log::log('resource ' . $resource . ($resourceExist ? ' exist' : ' not found'), Zend_Log::NOTICE);

        if (!$resourceExist) {
            $this->_pageNotFound($request);

            return;
        }

        $roleExist = $acl->hasRole($role);
        Whale_Log::log('role ' . $role . ($roleExist ? ' exists' : ' not found') . ' in ACL');
        $allowed = $resourceExist && $roleExist && $acl->isAllowed($role, $resource, $privilege);
        Whale_Log::log($allowed ? 'allowed' : 'denied');

        if (!$allowed) {
            $this->_deniedHandler($request);
        }
    }

    // если доступ запрещён, то редиректим куда надо
    protected function _deniedHandler(Zend_Controller_Request_Abstract $request)
    {
        $request->setControllerName('error');
        $request->setActionName('error');
        throw new Zend_Controller_Action_Exception('запрещено', 403);
    }

    protected function _pageNotFound(Zend_Controller_Request_Abstract $request)
    {
        $layout = Zend_Layout::getMvcInstance();
        $layout->disableLayout();
        throw new Zend_Controller_Action_Exception('page not found', 404);
    }

    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
    }

    // выделил в отдельную функцию, так как я уверен, что придётся в зависимости от какой-нибудь черни
    // кидать на разные ресурсы
    protected function _getDefaultRoute()
    {
        return $this->_deniedRoute;
    }
}