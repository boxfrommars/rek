<?php
/**
 * @author Dmitry Groza <boxfrommars@gmail.com>
 */

class Whale_Form_Page extends Whale_Form_Titled
{

    protected $_parentPage = null;

    protected function _initTopElements()
    {
        $this->addElement('select', 'id_parent', array(
            'label' => "Родитель",
            'readonly' => true,
            'required' => false,
            'validators' => array(),
            'dimension' => $this->_dimension,
            'multiOptions' => $this->getParentPages(),
        ));

        $this->addElement('checkbox', 'is_published', array(
            'label' => "Опубликована",
        ));

        $this->addElement('text', 'order', array(
            'label' => "Порядковый номер",
            'required' => false,
            'filters' => array('Null'),
            'validators' => array('Int'),
            'dimension' => $this->_dimension,
        ));
    }

    protected function _initMiddleElements()
    {
        parent::_initMiddleElements();

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

    protected function getParentPages()
    {
        if (null == $this->_parentPage) return array();
        $pageService = new Page_Model_Service();
        $pages = $pageService->fetchAll(array('name = ?' => $this->_parentPage))->toArray();

        $options = array();
        foreach ($pages as $page) {
            $options[$page['id']] = $page['title'];
        }

        return $options;
    }
}