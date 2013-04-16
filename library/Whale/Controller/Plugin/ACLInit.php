<?php
/**
 * Собираем acl из модулей
 *
 * вполне можно было бы получать только acl  для запрашиваемого модуля,
 * но так как мы кэшируем и acl не такая уж и большая, то пока так
 * если в итоге будет огромной, то поменять будет не сложно
 *
 * @TODO cache acl
 *
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Whale_Controller_Plugin_ACLInit extends Whale_Controller_Plugin
{

    /**
     * @var Zend_Acl
     */
    protected $_acl;

    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        $this->_acl = new Zend_Acl();

        try {
            //устанавливаем роли
            $this->_acl->addRole('guest');
            $this->_acl->addRole('user', 'guest');
            $this->_acl->addRole('admin', 'guest');

            $moduleManager = Whale_Module_Manager::getInstance();

            Whale_Log::log($moduleManager->getAll());

            foreach ($moduleManager->getAll() as $module) {
                if ($module->isEnabled()) {
                    foreach ($module->getResources() as $resource) $this->_acl->addResource($resource);
                    $this->_addToACL($module->getPrivileges());
                }
            }

        } catch (Zend_Exception $e) {
            Whale_Log::log($e->getMessage(), Zend_Log::ERR);
            Whale_Log::log($e->getTraceAsString(), Zend_Log::ERR);
        }
        Zend_Registry::set('ACL', $this->_acl);
    }

    /**
     * добавляет к ACL привилегии
     *
     * @param $privileges
     * @throws Zend_Exception
     */
    protected function _addToACL($privileges)
    {
        if (is_array($privileges)) {
            foreach ($privileges as $privilege) {
                switch ($privilege['type']) {
                    case 'allow':
                        $this->_acl->allow(
                            $privilege['role'],
                            $privilege['resource'],
                            empty($privilege['action']) ? null : $privilege['action']
                        );
                        break;

                    case 'deny':
                        $this->_acl->deny(
                            $privilege['role'],
                            $privilege['resource'],
                            empty($privilege['action']) ? null : $privilege['action']
                        );
                        break;

                    default:
                        break;
                }
            }
        } else {
            throw new Zend_Exception('invalid ACL from module ');
        }
    }
}