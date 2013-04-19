<?php

class Default_IndexController extends Whale_Controller_Action
{

    public function init()
    {
        parent::init();
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $productService = new Catalog_Model_ProductService();
        $this->view->items = $productService->fetchAll();
    }

    public function loginAction()
    {
        $form = new Application_Form_Login();
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            $request = $this->getRequest();

            if ($form->isValid($request->getPost())) {
                $data = $form->getValues();

                $identity = $this->_auth($data['username'], $data['password']);

                if ($identity) {
                    $storage = new Zend_Auth_Storage_Session();
                    $storage->write(array(
                        'username' => $identity->username,
                        'id' => $identity->id,
                    ));
                    $this->redirect('/admin');
                } else {
                    $this->view->flashMessages[] = 'Неправильный логин или пароль';
                }
            }
        }
    }

    protected function _auth($username, $password)
    {
        $auth = Zend_Auth::getInstance();
        $authAdapter = $this->_getAuthAdapter();

        $authAdapter
            ->setIdentity($username)
            ->setCredential($password);

        $result = $auth->authenticate($authAdapter);

        if ($result->isValid()) {
            $identity = $authAdapter->getResultRowObject();
        } else {
            $identity = false;
        }

        return $identity;
    }

    /**
     * @return Zend_Auth_Adapter_DbTable
     */
    public function _getAuthAdapter()
    {
        $authAdapter = new Zend_Auth_Adapter_DbTable(null, 'users', 'username', 'password', 'MD5(?)');

        return $authAdapter;
    }

    public function logoutAction()
    {
        $storage = new Zend_Auth_Storage_Session();
        $storage->clear();
        $this->redirect('/');
    }
}

