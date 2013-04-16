<?php
/**
 *
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Whale_Controller_Plugin_UserInit extends Whale_Controller_Plugin
{

    /**
     * @var Zend_Acl
     */
    protected $_acl;

    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        if ($this->_isUserFromRequest($request)) {
            // http://zend-framework-community.634137.n4.nabble.com/Getting-JSON-POST-data-from-Zend-td1462014.html#a1468843
            $request     = $this->getRequest();
            $contentType = $request->getHeader('Content-Type');
            $rawBody     = $request->getRawBody();

            if ($rawBody && strstr($contentType, 'application/json')) {
                $params = Zend_Json::decode($rawBody);
                Whale_User_Current::setIsApiUser(true);
                if (!empty($params['session'])) {
                    Whale_User_Current::setApiSessionId($params['session']);
                }
            }
        }
    }

    /**
     * @param Zend_Controller_Request_Abstract $request
     * @return bool
     */
    protected function _isUserFromRequest($request)
    {
        if ($request->getModuleName() == 'default' && $request->getControllerName() == 'index' && $request->getActionName() == 'api') {
            return true;
        } else {
            return false;
        }
    }
}