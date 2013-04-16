<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Whale_User_Service
{
    /**
     * @param $identity array(id => value)
     *
     * @return Whale_User
     */
    public function fetch($identity)
    {
        $model = new Whale_User_DbTable();
        $user = new Whale_User($identity);

        if (!$user->isGuest()) {
            $userData = $model->fetch($identity['id']);
            $user->setRole($userData->role);
            $user->setUsername($userData->username);
        }

        return $user;
    }
}