<?php
/**
 * Класс поиска.
 */
class Application_Model_Search
{
    // путь к индексу
    private $indexPath;
    // индекс
    private $sIndex;

    /**
     * конструктор. определяет поисковый индекс ($this->sIndex).
     * если он не создан, то сначала создаёт его
     */
    function __construct()
    {
        $this->indexPath = APPLICATION_PATH . '/tmp/searchindex';

        Zend_Search_Lucene_Analysis_Analyzer::setDefault(
            new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8_CaseInsensitive());

        try{
            // пытаемся открыть индекс
            $this->sIndex = Zend_Search_Lucene::open($this->indexPath);

        } catch(Zend_Search_Lucene_Exception $e) {
            // если не получалось, то создаём и обновляем
            Zend_Search_Lucene::create($this->indexPath);
            $this->sIndex = Zend_Search_Lucene::open($this->indexPath);

            // плохо, это нужно было вынести, но так мы избавляемся
            // от необходимости инсталяции приложения (индекс,
            // если он отсутствует создастся на лету)
            $pagesService = new Page_Model_Service();
            $pages = $pagesService->fetchAll(array('is_published'));

            // добавляем все документы в индекс
            foreach ($pages as $page){
                $doc = new Default_Model_SearchDocPageAdapter($page);
                $this->sIndex->addDocument($doc);
            }
            $this->sIndex->optimize();
        }
    }

    /**
     * добавляет документ к индексу
     *
     * @param Default_Model_SearchDocInterface $searchDoc
     *
     */
    public function addToIndex(Default_Model_SearchDocInterface $searchDoc)
    {
        $doc = $this->createLuceneDoc($searchDoc);
        $this->sIndex->addDocument($doc);
    }

    /**
     * обновляет документ в индексе
     *
     * @param Default_Model_SearchDocInterface $searchDoc
     *
     */
    public function updateInIndex(Default_Model_SearchDocInterface $searchDoc)
    {
        $this->deleteFromIndex($searchDoc);
        $doc = $this->createLuceneDoc($searchDoc);
        $this->sIndex->addDocument($doc);
    }

    /**
     * удаляет документ из индекса
     *
     * @param Default_Model_SearchDocInterface $searchDoc
     *
     */
    public function deleteFromIndex(Default_Model_SearchDocInterface $searchDoc)
    {
        $hits = $this->sIndex->find('docid:' . $searchDoc->id);
        foreach ($hits as $hit) {
            $this->sIndex->delete($hit->id);
        }
    }

    /**
     * ищет строку в индексе
     *
     * @param Default_Model_SearchDocInterface $string
     * @return Zend_Search_Lucene_Document
     */
    public function search($string)
    {
        $query = Zend_Search_Lucene_Search_QueryParser::parse($string, 'utf-8');
        return $this->sIndex->find($query);
    }

    /**
     * создаёет LuceneDoc из SearchDoc
     *
     * @param Default_Model_SearchDocInterface $searchDoc
     * @return Zend_Search_Lucene_Document
     */
    public function createLuceneDoc(Default_Model_SearchDocInterface $searchDoc)
    {
        $doc = new Zend_Search_Lucene_Document();
        $doc->addField(Zend_Search_Lucene_Field::Text('title', $searchDoc->title, 'utf-8'));
        $doc->addField(Zend_Search_Lucene_Field::Text('content', $searchDoc->content, 'utf-8'));
        $doc->addField(Zend_Search_Lucene_Field::UnIndexed('type', $searchDoc->type, 'utf-8'));
        $doc->addField(Zend_Search_Lucene_Field::keyword('docid', $searchDoc->id));

        return $doc;

    }
}