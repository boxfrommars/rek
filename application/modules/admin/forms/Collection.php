<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_Form_Collection extends Admin_Form_CatalogItem
{
    protected function _initMiddleElements()
    {
        $brandService = new Catalog_Model_BrandService();
        $brands = $brandService->fetchAll();

        $brandSelectOptions = array();
        foreach ($brands as $brand) {
            $brandSelectOptions[$brand['id']] = $brand['title'];
        }

        $this->addElement('select', 'id_brand', array(
                'label' => "Бренд",
                'required' => true,
                'validators' => array(),
                'dimension' => 6,
                'multiOptions' => $brandSelectOptions,
            ));

    }
}