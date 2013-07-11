<?php

class Flavia_Page_Router extends Whale_Router_Page {
    public function getPageRoute($page, $route)
    {
        switch ($page['entity']) {
            case 'category':
                $route = array('module' => 'catalog', 'controller' => 'index', 'action' => 'index',
                        'category' => $page['page_url']) + $route;
                break;
            case 'brand':
                $route = array('module' => 'catalog', 'controller' => 'index', 'action' => 'brand',
                        'brand' => $page['page_url'], 'category' => $page['parents'][1]['page_url']) + $route;
                break;
            case 'product':
                $route = array('module' => 'catalog', 'controller' => 'index', 'action' => 'view',
                        'product' => $page['page_url'], 'brand' => $page['parents'][2]['page_url'], 'category' => $page['parents'][1]['page_url']) + $route;
                break;
        }
        return $route;
    }
}