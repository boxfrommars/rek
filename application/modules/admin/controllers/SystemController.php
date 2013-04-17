<?php

class Admin_SystemController extends Whale_Controller_Action
{

    public function init()
    {
        parent::init();
    }

    public function uploadAction()
    {
        Zend_Layout::getMvcInstance()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $upload_handler = new UploadHandler(array(
            'upload_dir' => APPLICATION_PATH . '/../public/files/',
            'upload_url' => '/files/',
        ));
    }


}

