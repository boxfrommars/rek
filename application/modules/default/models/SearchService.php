<?php
/**
 * @author Dmitry Groza <boxfrommars@gmail.com>
 */

class Default_Model_SearchService {

    /**
     * @var Zend_Search_Lucene_Interface
     */
    protected $_index;

    /**
     * @var string
     */
    protected $_indexPath;

    public function __construct($indexPath)
    {
        $this->_indexPath = $indexPath;
        Zend_Search_Lucene_Analysis_Analyzer::setDefault(new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8_CaseInsensitive());
    }

    public function open() {
        $this->_index = Zend_Search_Lucene::open($this->_indexPath);
    }

    public function create()
    {
        Zend_Search_Lucene::create($this->_indexPath);
        $this->open();
    }

    public function addToIndex(Default_Model_SearchDocInterface $searchDoc)
    {
        $doc = $this->_createLuceneDoc($searchDoc);
        $this->_index->addDocument($doc);
    }

    public function removeFromIndex(Default_Model_SearchDocInterface $searchDoc)
    {
        $hits = $this->_index->find('docid:' . $searchDoc->getId());
        foreach ($hits as $hit) {
            $this->_index->delete($hit->id);
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
        $query = Zend_Search_Lucene_Search_QueryParser::parse($string . '~0.7', 'utf-8');
        return $this->_index->find($query);
    }

    public function updateInIndex(Default_Model_SearchDocInterface $searchDoc)
    {
        $this->removeFromIndex($searchDoc);
        $this->addToIndex($searchDoc);
    }

    /**
     * @param Default_Model_SearchDocInterface $searchDoc
     * @return Zend_Search_Lucene_Document
     */
    protected function _createLuceneDoc(Default_Model_SearchDocInterface $searchDoc)
    {
        $doc = new Zend_Search_Lucene_Document();
        $doc->addField(Zend_Search_Lucene_Field::Text('title', $searchDoc->getTitle(), 'utf-8'));
        $doc->addField(Zend_Search_Lucene_Field::Text('description', $searchDoc->getDescription(), 'utf-8'));
        $doc->addField(Zend_Search_Lucene_Field::Text('keyword', $searchDoc->getKeywords(), 'utf-8'));
        $doc->addField(Zend_Search_Lucene_Field::Text('content', $searchDoc->getContent(), 'utf-8'));
        $doc->addField(Zend_Search_Lucene_Field::UnIndexed('type', $searchDoc->getType(), 'utf-8'));
        $doc->addField(Zend_Search_Lucene_Field::keyword('url', $searchDoc->getURL(), 'utf-8'));
        $doc->addField(Zend_Search_Lucene_Field::keyword('docid', $searchDoc->getId(), 'utf-8'));

        return $doc;
    }
}