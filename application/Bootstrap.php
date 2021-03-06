<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * инициализируем плагины
     */
    protected function _initPlugins()
    {
        $this->bootstrap('Log');
        $front = Zend_Controller_Front::getInstance();

        $front->registerPlugin(new Whale_Controller_Plugin_UserInit());
        $front->registerPlugin(new Whale_Controller_Plugin_ACLInit());
        $front->registerPlugin(new Whale_Controller_Plugin_PrivilegesChecker());
        $front->registerPlugin(new Whale_Controller_Plugin_NavigationInit());
    }

    protected function _initValidatorMessages()
    {
        $translator = new Zend_Translate(
            array(
                'adapter' => 'array',
                'content' => APPLICATION_PATH . '/../resources/languages',
                'locale'  => 'ru',
                'scan' => Zend_Translate::LOCALE_DIRECTORY
            )
        );
        Zend_Validate_Abstract::setDefaultTranslator($translator);
    }

    protected function _initLayoutHelper()
    {
        $this->bootstrap('frontController');
        Zend_Controller_Action_HelperBroker::addPrefix('Whale_Controller_Action_Helper');
        Zend_Controller_Action_HelperBroker::addHelper(new Whale_Controller_Action_Helper_Page());
    }

    protected function _initModuleManager()
    {
        $moduleManager = Whale_Module_Manager::getInstance();
        $app = $this->getOption('app');
        $moduleManager->setNames($app['modules']);
    }

    protected function _initAppRoutes()
    {
        $this->bootstrap('frontController');
        $this->bootstrap('router');

        /** @var $front Zend_Controller_Front */
        $front =  $this->getResource('FrontController');
//        $front->getRouter()->removeDefaultRoutes();
    }
}

