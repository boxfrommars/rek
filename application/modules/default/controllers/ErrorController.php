<?php

class Default_ErrorController extends Zend_Controller_Action
{
    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');

        if (!$errors || !$errors instanceof ArrayObject) {
            $this->view->message = 'You have reached the error page';
            return;
        }

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:

                $this->view->page = new Whale_Page_SeoItemAdapter(array(
                    'title' => 'Ошибка 404. Страница не найдена',
                    'content' => 'Ошибка 404. Страница не найдена',
                    'description' => 'Страница ошибки'
                ));
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $priority = Zend_Log::NOTICE;
                $this->view->message = 'Страница не найдена';

                break;
            default:
                // application error
                if ($errors->exception->getCode() == 403) {
                    $this->view->page = new Whale_Page_SeoItemAdapter(array(
                        'title' => 'Ошибка 403. Запрещено',
                        'content' => 'Ошибка 403. Запрещено, <a href="/login">войдите</a>, чтобы продолжить',
                        'description' => 'Страница ошибки'
                    ));
                    $this->getResponse()->setHttpResponseCode(403);
                    $priority = Zend_Log::CRIT;
                    Whale_Log::log($errors->exception->getCode());
                    $this->view->message = 'Повторите запрос позже или свяжитесь со службой поддержки';

                } else {
                    $this->view->page = new Whale_Page_SeoItemAdapter(array(
                        'title' => 'Ошибка 500. Повторите запрос позже или свяжитесь со службой поддержки',
                        'content' => 'Ошибка 500. Повторите запрос позже или свяжитесь со службой поддержки',
                        'description' => 'Страница ошибки'
                    ));
                    $this->getResponse()->setHttpResponseCode(500);
                    $priority = Zend_Log::CRIT;
                    Whale_Log::log($errors->exception->getCode());
                    $this->view->message = 'Повторите запрос позже или свяжитесь со службой поддержки';
                }
                break;
        }

        // Log exception, if logger available
        if ($log = $this->getLog()) {
            $log->log($this->view->message, $priority);
            $log->log($errors->exception, $priority);
            $log->log($errors->request->getParams(), $priority);
        }

        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception;
        }

        $this->view->request   = $errors->request;
    }

    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }
}



