<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_Form_Brand extends Admin_Form_CatalogItem
{

    protected function _initMiddleElements()
    {
        $categoryService = new Catalog_Model_CategoryService();
        $categories = $categoryService->fetchAll();

        $categorySelectOptions = array();
        foreach ($categories as $category) {
            $categorySelectOptions[$category['id']] = $category['title'];
        }

        $this->addElement('select', 'id_category', array(
                'label' => "Категория",
                'required' => true,
                'validators' => array(),
                'dimension' => $this->_dimension,
                'multiOptions' => $categorySelectOptions,
            ));
    }
}