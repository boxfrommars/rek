<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_CategoryController extends Whale_Controller_Action_Admin_Article
{

    protected $_perPage = null;

    /**
     * @var Catalog_Model_CategoryService
     */
    protected $_model;

    /**
     * @var Admin_Form_CatalogItem
     */
    protected $_form;

    public function init()
    {
        parent::init();
        $this->_model = new Catalog_Model_CategoryService();
        $this->_form = new Admin_Form_CatalogItem();
    }

    protected function _getList($page = null)
    {
        $categories = $this->_model->fetchAll();

        $pathes = array();

        foreach ($categories as $category) {
            $pathes[] = $category['path'];
        }

        $db = Zend_Db_Table::getDefaultAdapter();

        $select = $db->select()->from(
            array('p' => 'page'),
            array('*')
        );

        foreach ($pathes as $path) {
            $select->orWhere('path <@ ?', $path);
        }

        $pages =  $select->order(array('path'))->query()->fetchAll();
        Whale_Log::log($pages);
        return $pages;
    }
}

