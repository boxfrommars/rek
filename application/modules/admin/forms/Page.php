<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_Form_Page extends Admin_Form_Titled
{
    public function init()
    {
        $this->setMethod('post');
        $this->_initTopElements();
        $this->_initMiddleElements();
        $this->_initBottomElements();
        $this->_initButtons();
    }


    protected function _initTopElements()
    {
        $this->addElement('checkbox', 'is_published', array(
            'label' => "Опубликована",
        ));
    }

    protected function _initMiddleElements()
    {
        parent::_initMiddleElements();

        $pageService = new Page_Model_Service();
        $pages = $pageService->fetchAll();

        $pageSelectOptions = array();
        foreach ($pages as $page) {
            $pageSelectOptions[$page['id']] = $page['title'];
        }

        $this->addElement('select', 'id_parent', array(
            'label' => "Родитель",
            'required' => true,
            'validators' => array(),
            'dimension' => 6,
            'multiOptions' => $pageSelectOptions,
        ));

        $this->addElement('textarea', 'content', array(
            'label' => 'Контент',
            'required' => false,
            'validators' => array(),
            'dimension' => $this->_dimension,
            'rows' => 2,
        ));
    }

    protected function _initBottomElements()
    {

        $this->addElement('text', 'page_url', array(
                'label' => "SEO: Url",
                'required' => false,
                'filters' => array('StringTrim'),
                'validators' => array(),
                'dimension' => $this->_dimension,
            ));
        $this->addElement('text', 'page_title', array(
                'label' => "SEO: Title",
                'required' => false,
                'filters' => array('StringTrim'),
                'validators' => array(),
                'dimension' => $this->_dimension,
            ));

        $this->addElement('textarea', 'page_keywords', array(
                'label' => 'SEO: Ключевые слова',
                'required' => false,
                'validators' => array(),
                'dimension' => $this->_dimension,
                'rows' => 2,
            ));

        $this->addElement('textarea', 'page_description', array(
                'label' => 'SEO: Описание',
                'required' => false,
                'validators' => array(),
                'dimension' => $this->_dimension,
                'rows' => 2,
            ));
    }

    protected function _initButtons()
    {

        $this->addElement('button', 'submit', array(
            'label' => 'Сохранить',
            'type' => 'submit',
            'buttonType' => Twitter_Bootstrap_Form_Element_Submit::BUTTON_SUCCESS,
        ));

        $this->addElement('button', 'reset', array(
            'label' => 'Отмена',
            'type' => 'reset',
            'buttonType' => Twitter_Bootstrap_Form_Element_Submit::BUTTON_INFO,
        ));

        $this->addDisplayGroup(
            array('submit', 'reset'),
            'actions',
            array(
                'disableLoadDefaultDecorators' => true,
                'decorators' => array('Actions')
            )
        );
    }
}