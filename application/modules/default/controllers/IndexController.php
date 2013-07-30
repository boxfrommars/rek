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
        $this->_setPage('main');

        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayout('main');

        $sliderService = new Gallery_Model_ServiceMain();

        $this->view->slider = $sliderService->fetchAll(array('is_published = ?' => true));

        $newsService = new News_Model_Service();
        $this->view->news = $newsService->fetchAll(array('is_published = ?' => true), array('published_at DESC'), 3);

        $productService = new Catalog_Model_ProductService();
        $this->view->itemsAction = $productService->fetchAll(array('is_action' => true, 'is_published = ?' => true), array('created_at DESC'), 4);
        $this->view->itemsNew = $productService->fetchAll(array('is_new' => true, 'is_published = ?' => true), array('created_at DESC'), 4);
        $this->view->itemsHit = $productService->fetchAll(array('is_hit' => true, 'is_published = ?' => true), array('created_at DESC'), 4);

        $pageTextsService = new Whale_PageText_Service();

        $texts = $pageTextsService->fetchAll(array('"group" = ?' => 'main'));
        $this->view->texts = new Whale_PageText_Group($texts);

    }

    public function mapAction()
    {
        $this->_setPage('map');
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayout('page');

        $db = Zend_Db_Table::getDefaultAdapter();

        $select = $db->select()->from(
            array('p' => 'page'),
            array('url' => "array_to_string(array_agg(a.page_url ORDER BY a.path), '/')", '*')
        )->joinInner(
                array('a' => 'page'),
                'a.path @> p.path',
                array()
            )->group(
                'p.id',
                'p.path',
                'p.page_url'
            );
        $nextSelect = $db->select()->from(array('s' => $select), '*')->order(array('path'));
//        $nextSelect = $db->select()->from(array('s' => $select), '*')->where('is_published')->order(array('path'));
        $items = $nextSelect->query()->fetchAll();
        Whale_Log::log($items);
        $this->view->assign('items', $items);

    }

    public function contactsAction()
    {
        $this->_setPage('contacts');
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayout('contacts');

        $newsService = new News_Model_Service();
        $this->view->news = $newsService->fetchAll(array('is_published = ?' => true), array('published_at DESC'), 3);
//
//
//        $model = new Page_Model_Service();
//        $items = $model->fetchAll(null, array('path ASC'));
//        Whale_Log::log($items->toArray());
//        $this->view->assign('items', $items);

    }

    public function articlesAction()
    {
        $this->_setPage('articles');
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayout('page');

        $db = Zend_Db_Table::getDefaultAdapter();

        $page = $db->select()->from(
            array('p' => 'page'),
            array('*')
        )->where('name = ?', 'articles')->query()->fetch();

        $select = $db->select()->from(
            array('p' => 'page'),
            array('url' => "array_to_string(array_agg(a.page_url ORDER BY a.path), '/')", '*')
        )->joinInner(
                array('a' => 'page'),
                'a.path @> p.path',
                array()
            )->group(
                'p.id',
                'p.path',
                'p.page_url'
            );
        $nextSelect = $db->select()->from(array('s' => $select), '*')->order(array('path'));


        $items = $nextSelect->where('path <@ ?', $page['path'])->where('name <> ?', 'articles')->query()->fetchAll();
        Whale_Log::log($items);

        $this->view->assign('items', $items);

    }

    /**
     * ищет документы с соответствием со строкой переданной в $_POST['searchstring']
     */
    public function searchAction()
    {
        // форма поиска
        $form = new Application_Form_Search();
        $this->view->form = $form;
        $this->_setPage('search');

        // если есть post-данные и они валидны
        if ($this->getRequest()->isPost()) {
            $request = $this->getRequest();
            if ($form->isValid($request->getPost())) {
                // получаем строку для поиска
                $values = $form->getValues();
                $searchString = $values['searchstring'];

                // ищем

                $searchOptions = Whale_Get::option('search');
                $searchService = new Default_Model_SearchService($searchOptions['index_path']);
                $searchService->open();
                $hits = $searchService->search($searchString);

                // отправляем в вид строку поиска и результат
                $this->view->searchString = $searchString;
                $this->view->searchResult = $hits;
                Whale_Log::log('SEEEEEEEAAAAAAAAAAAARCH');
                Whale_Log::log($hits);

            }
        }
    }

    public function csearchAction()
    {
        $searchOptions = Whale_Get::option('search');
        $searchService = new Default_Model_SearchService($searchOptions['index_path']);
        $searchService->create();

        $pageService = new Page_Model_Service();
        $pages = $pageService->getBaseSelect()->where('p.is_published')->query()->fetchAll();
        foreach ($pages as $page) {

            Whale_Log::log(new Default_Model_SearchDocPageAdapter($page));
            $searchService->addToIndex(new Default_Model_SearchDocPageAdapter($page));
        }
    }

    public function loginAction()
    {
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayout('layout');

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

