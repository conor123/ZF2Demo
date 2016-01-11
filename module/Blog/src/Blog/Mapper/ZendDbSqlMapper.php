<?php
// Filename: /module/Blog/src/Blog/Mapper/ZendDbSqlMapper.php
namespace Blog\Mapper;

use Blog\Model\PostInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
//use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements PostMapperInterface
{
   /**
    * @var \Zend\Db\Adapter\AdapterInterface
    */
   protected $dbAdapter;

    /**
      * @var \Zend\Stdlib\Hydrator\HydratorInterface
      */
    protected $hydrator;

     /**
      * @var \Blog\Model\PostInterface
      */
    protected $postPrototype;


   /**
      * @param AdapterInterface  $dbAdapter
      * @param HydratorInterface $hydrator
      * @param PostInterface    $postPrototype
      */
     public function __construct(
         AdapterInterface $dbAdapter,
         HydratorInterface $hydrator,
         PostInterface $postPrototype
     ) {
         $this->dbAdapter      = $dbAdapter;
         $this->hydrator       = $hydrator;
         $this->postPrototype  = $postPrototype;
     }

   /**
    * @param int|string $id
    *
    * @return PostInterface
    * @throws \InvalidArgumentException
    */
   public function find($id)
   {
      $sql = new Sql($this->adapter);
      $select = $sql->select('posts');
      $select->where(array('id = ?' => $id));

      $stmt = $sql->prepareStatementForSqlObject($select);
      $result = $stmt->execute();

      if ($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()) {

        return $this->hydrator->hydrate($result->current(), $this->postPrototype);
      }

      throw new \InvalidArgumentException("Blog with given ID: {$id} not found.");
   }

   /**
    * @return array|PostInterface[]
    */
   public function findAll()
   {
    $sql    = new Sql($this->dbAdapter);
    $select = $sql->select('posts');

    $stmt   = $sql->prepareStatementForSqlObject($select);
    $result = $stmt->execute();

    if ($result instanceof ResultInterface && $result->isQueryResult()) {
      //$resultSet = new ResultSet();
      

      //$resultSet = new HydratingResultSet(new \Zend\Stdlib\Hydrator\ClassMethods(), new \Blog\Model\Post());
       $resultSet = new HydratingResultSet($this->hydrator, $this->postPrototype);

      //    \Zend\Debug\Debug::dump($resultSet->initialize($result));die();

      return $resultSet->initialize($result);
    }
    return array();
   }
}