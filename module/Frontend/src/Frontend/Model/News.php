<?php 
namespace Frontend\Model;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Sql;
class News extends AbstractTableGateway{
	// Table name
	protected $table = 'news';
	/* Call apdater */
	public function __construct() {
			$this->featureSet = new Feature\FeatureSet();
			$this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
			$this->initialize();
	}

	// get all category
	public function getAllNews($arrayParam = null){
		$select = new Select();
		$where = new Where();
		$select->from($this->table);
		$select->join('category', 'news.category_id = category.id', array('category-name' => 'name'), 'left');
		$select->join('taxonomy', 'category.taxonomy_id = taxonomy.id', array('taxonomy-name' => 'name'), 'left');
		$select->where("taxonomy.slug = '". $arrayParam['slug']."'");
		// paging limit
		if(isset($arrayParam['limit']) && $arrayParam['limit'] !== ''){
				$select->limit($arrayParam['limit'])->offset($arrayParam['offset']);
		}
		$select->where('news.status = 1');
		// filter
		$select->order('id DESC');
		$resultSet = $this->selectWith($select);
		$resultSet = $resultSet->toArray();
		return $resultSet;
	}
	
	// count all news
	public function countAllNews($arrayParam = null){
		$select = new Select();
		$where = new Where();
		$select->from($this->table);
		$select->join('category', 'news.category_id = category.id', array('category-name' => 'name'), 'left');
		$select->join('taxonomy', 'category.taxonomy_id = taxonomy.id', array('taxonomy-name' => 'name'), 'left');
		$select->where("taxonomy.slug = '". $arrayParam['slug']."'");
		$select->where('news.status = 1');
		// filter
		$select->order('id DESC');
		$resultSet = $this->selectWith($select);
		$resultSet = $resultSet->count();
		return $resultSet;
	}
}