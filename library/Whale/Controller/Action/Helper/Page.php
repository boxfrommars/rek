<?php
/**
 * Занимается всякой лайаутной чушью
 * добавление навигаций, меню и титлов на основе навигации
 * добавление виджета профайла и (в будущем) сообщений
 * добавление в каждый вью $this->user (объект текущего пользователя)
 *
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Whale_Controller_Action_Helper_Page extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * @var Zend_Layout
     */
    protected $_layout;

    public function preDispatch()
    {
        /** @var $layout Zend_Layout */
        $layout = $this->getActionController()->getHelper('layout');
        $view = $this->getActionController()->view;
        $module = $this->getRequest()->getModuleName();
        $controller = $this->getRequest()->getControllerName();
        $action = $this->getRequest()->getActionName();

        if ($module != 'admin') {
            $categoryService = new Catalog_Model_CategoryService();
            $view->categoryNavigation = $categoryService->fetchAll(array('is_published = ?' => true));
            $view->page = new Whale_Page();

            $settings = new Admin_Model_Settings();
            $price = $settings->fetchRow(array('name = ?' => 'price'));
            if (null !== $price && !empty($price->value)) {
                Whale_Log::log($price->value);
                $view->price = $price->value;
            }

        }

        $this->_switchLayout($layout, $module, $controller, $action);
        $this->_setTitle($view, $layout);
        $this->_appendScripts($view);
    }

    /**
     * @param Zend_Layout $layout
     * @param $module
     * @param $controller
     * @param $action
     */
    protected function _switchLayout($layout, $module, $controller, $action)
    {
        if ($module == 'admin') {
            $layout->setLayout('admin');
        } else if ($module == 'default' && $controller = 'error') {
            $layout->disableLayout();
        }
    }

    protected function _setTitle($view, $layout)
    {
        /** @var $activePage Zend_Navigation_Page_Mvc */
        $activePage = $layout->getView()->navigation()->findBy('active', true);
        if (!empty($activePage))  {
            $view->headTitle($activePage->getLabel() . ' - Универсальная электронная карта. Краснодарский край');
        } else {
            $view->headTitle('Универсальная электронная карта. Краснодарский край');
        }
    }

    /**
     * @param Zend_View_Interface $view
     */
    protected function _appendScripts($view)
    {
        $view->doctype()->setDoctype('HTML5');
        $view->headMeta()->setCharset('UTF-8');
    }
}
