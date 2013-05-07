<?php
/**
 * форма поиска
 */
class Application_Form_Search extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');
        $this->setAction('/search');

        // массив настроек инпута поиска
        $searchStringOptions = array(
            'label' => 'Search',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array()
        );
        // добавляем к форме инпут для текста поиска
        $this->addElement('text', 'searchstring', $searchStringOptions);
        // кнопка
        $this->addElement('submit', 'submit', array('ignore' => true,'label' => 'Search'));
    }
}