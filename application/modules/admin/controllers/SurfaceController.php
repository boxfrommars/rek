<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_SurfaceController extends Whale_Controller_Action_Admin_Article
{

    protected $_order = null;

    /**
     * @var Catalog_Model_SurfaceService
     */
    protected $_model;

    /**
     * @var Whale_Form_Titled
     */
    protected $_form;

    public function init()
    {
        parent::init();
        $this->_model = new Catalog_Model_SurfaceService();
        $this->_form = new Whale_Form_Titled();
    }
}

