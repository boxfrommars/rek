<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Feedback_IndexController extends Whale_Controller_Action
{
    /**
     * @var Faq_Model_Service
     */
    protected $_service;

    public function init()
    {
        parent::init();
        $this->_service = new Faq_Model_Service();
    }

    public function indexAction()
    {
        $this->view->items = $this->_service->fetchAll(array('is_published = ?' => true), array('created_at ASC'));
        $form = new Admin_Form_FaqPublic();

        if ($this->getRequest()->isPost()) {
            $request = $this->getRequest();

            if ($form->isValid($request->getPost())) {
                $values = $form->getValues();
                $this->_service->insert($values);
                $this->_flashMessenger->addMessage('Ваш вопрос добавлен. Мы постараемся ответить на него как можно быстрее.');

                return $this->_helper->redirector('index');
            }
        }
        $this->view->form = $form;
    }
}

