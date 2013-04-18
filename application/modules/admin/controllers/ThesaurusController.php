<?php

class Admin_ThesaurusController extends Whale_Controller_Action
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        $colorService = new Catalog_Model_ColorService();
        $surfaceService = new Catalog_Model_SurfaceService();
        $countryService = new Catalog_Model_CountryService();

        $this->view->thesaurus = array(
            'colors' => array(
                'items' => $colorService->fetchAll(),
                'title' => 'Цвет',
                'controller' => 'color',
            ),
            'surfaces' => array(
                'items' => $surfaceService->fetchAll(),
                'title' => 'Поверхность',
                'controller' => 'surface',
            ),
            'countries' => array(
                'items' => $countryService->fetchAll(),
                'title' => 'Страна',
                'controller' => 'country',
            ),
        );
    }


}

