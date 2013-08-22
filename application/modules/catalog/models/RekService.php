<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Catalog_Model_RekService extends Whale_Db_TableCached
{
    protected $_name = 'rek';
    protected $_sequence = false;

    /**
     * ад, исправить
     *
     * @param array $filterParams
     * @param array $fetchParam
     */
    public function getByParam($filterParams = array(), $fetchParam = array())
    {
        $reks = $this->fetchAll();

        $returnReks = array();

        foreach ($reks as $rek) {
            $r = $rek->toArray();
            $filters = json_decode($r['params']);

            foreach ($filters as $filter) {
                foreach ($filterParams as $filterParam) {
                    if ($filter->name === $filterParam['name'] && in_array($filter->value, $filterParam['value'])) {
                        $returnReks[] = $rek;
                    }
                }
            }
        }
        return $returnReks;
    }
}