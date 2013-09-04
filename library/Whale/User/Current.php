<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Whale_User_Current
{
    /**
     * @var Whale_User
     */
    protected static $_user = null;

    protected static $_isApiUser = false;

    protected static $_apiSessionId = null;

    /**
     * Получаем текущего юзера. (сторим на основе zend_auth и сохраняем здесь же, чтобы при последующих вызовах не строить заново)
     *
     * @return Whale_User
     */
    public static function get()
    {
        if (null === self::$_user) {
            $identity = self::_getIdentity();
            $userMapper = new Whale_User_Service();
            self::$_user = $userMapper->fetch($identity);
        }

        return self::$_user;
    }

    /**
     *
     * @return array $identity
     */
    protected static function _getGuestIdentity()
    {
        return array(
            'role' => 'guest',
        );
    }

    /**
     * @param int $user
     * @return array|mixed|null
     */
    protected static function _getIdentity($user = 0)
    {
        $auth = Zend_Auth::getInstance();
        $identity = $auth->hasIdentity() ? $auth->getIdentity() : self::_getGuestIdentity(); // если не авторизован, то берём гостевой идентити

        return $identity;
    }
}