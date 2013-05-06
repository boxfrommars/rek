<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_GalleryController extends Whale_Controller_Action_Admin_Article
{

    protected $_order = null;

    /**
     * @var Catalog_Model_SurfaceService
     */
    protected $_model;

    /**
     * @var Admin_Form_Gallery
     */
    protected $_form;

    public function init()
    {
        parent::init();
        $this->_model = new Gallery_Model_Service();
        $this->_form = new Admin_Form_Gallery();
    }
}

