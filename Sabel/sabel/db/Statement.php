<?php

/**
 * Sabel_Db_Statement
 *
 * @abstract
 * @category   DB
 * @package    org.sabel.db
 * @author     Ebine Yutaka <ebine.yutaka@sabel.jp>
 * @copyright  2004-2008 Mori Reo <mori.reo@sabel.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 */
abstract class Sabel_Db_Statement extends Sabel_Object
{
  const SELECT = 0x01;
  const INSERT = 0x02;
  const UPDATE = 0x04;
  const DELETE = 0x08;
  const QUERY  = 0x10;
  
  const BINARY_IDENTIFIER = "__SBL_BINARY";
  
  /**
   * @var array
   */
  protected static $queries = array();
  
  /**
   * @var const Sabel_Db_Statement
   */
  protected $type = self::QUERY;
  
  /**
   * @var string
   */
  protected $query = "";
  
  /**
   * @var Sabel_Db_Driver
   */
  protected $driver = null;
  
  /**
   * @var Sabel_Db_Metadata_Table
   */
  protected $metadata = null;
  
  /**
   * @var array
   */
  protected $bindValues = array();
  
  /**
   * @var array
   */
  protected $binaries = array();
  
  /**
   * @var string
   */
  protected $table = "";
  
  /**
   * @var mixed
   */
  protected $projection = array();
  
  /**
   * @var string
   */
  protected $join = "";
  
  /**
   * @var string
   */
  protected $where = "";
  
  /**
   * @var array
   */
  protected $values = array();
  
  /**
   * @var array
   */
  protected $constraints = array();
  
  /**
   * @var string
   */
  protected $seqColumn = null;
  
  /**
   * @var boolean
   */
  protected $forUpdate = false;
  
  /**
   * @param string $binaryData
   *
   * @return Sabel_Db_Abstract_Blob
   */
  abstract public function createBlob($binaryData);
  
  /**
   * @return string
   */
  public function __toString()
  {
    return $this->build();
  }
  
  /**
   * @return Sabel_Db_Driver
   */
  public function getDriver()
  {
    return $this->driver;
  }
  
  /**
   * @return array
   */
  public static function getExecutedQueries()
  {
    return self::$queries;
  }
  
  /**
   * @param Sabel_Db_Metadata_Table $metadata
   *
   * @return self
   */
  public function setMetadata(Sabel_Db_Metadata_Table $metadata)
  {
    $this->table    = $metadata->getTableName();
    $this->metadata = $metadata;
    
    return $this;
  }
  
  /**
   * @return self
   */
  public function clear()
  {
    $this->query       = "";
    $this->bindValues  = array();
    $this->binaries    = array();
    $this->projection  = array();
    $this->join        = "";
    $this->where       = "";
    $this->values      = array();
    $this->constraints = array();
    $this->seqColumn   = null;
    $this->forUpdate   = false;
    
    return $this;
  }
  
  public function type($type)
  {
    $this->type = $type;
    
    return $this;
  }
  
  public function setQuery($query)
  {
    if (is_string($query)) {
      $this->query = $query;
    } else {
      $message = __METHOD__ . "() argument must be a string.";
      throw new Sabel_Exception_InvalidArgument($message);
    }
    
    return $this;
  }
  
  public function getQuery()
  {
    return ($this->hasQuery()) ? $this->query : $this->build();
  }
  
  public function hasQuery()
  {
    return (is_string($this->query) && $this->query !== "");
  }
  
  public function projection($projection)
  {
    if (is_array($projection) || is_string($projection)) {
      $this->projection = $projection;
    } else {
      $message = __METHOD__ . "() argument must be a string or an array.";
      throw new Sabel_Exception_InvalidArgument($message);
    }
    
    return $this;
  }
  
  public function join($join)
  {
    if (is_string($join)) {
      $this->join = $join;
    } else {
      $message = __METHOD__ . "() argument must be a string.";
      throw new Sabel_Exception_InvalidArgument($message);
    }
    
    return $this;
  }
  
  public function where($where)
  {
    if (is_string($where)) {
      $this->where = ($where === "") ? "" : " " . $where;
    } else {
      $message = __METHOD__ . "() argument must be a string.";
      throw new Sabel_Exception_InvalidArgument($message);
    }
    
    return $this;
  }
  
  public function constraints(array $constraints)
  {
    $this->constraints = $constraints;
    
    return $this;
  }
  
  public function values(array $values)
  {
    $columns = $this->metadata->getColumns();
    foreach ($values as $k => &$v) {
      if (isset($columns[$k]) && $columns[$k]->isBinary()) {
        $this->binaries[] = $this->createBlob($v);
        $v = new Sabel_Db_Statement_Expression($this, self::BINARY_IDENTIFIER . count($this->binaries));
      }
    }
    
    $this->values = $values;
    $this->appendBindValues($values);
    
    return $this;
  }
  
  public function sequenceColumn($seqColumn)
  {
    if ($seqColumn === null) {
      $this->seqColumn = null;
    } elseif (is_string($seqColumn)) {
      $this->seqColumn = $seqColumn;
    } else {
      $message = __METHOD__ . "() argument must be a string.";
      throw new Sabel_Exception_InvalidArgument($message);
    }
    
    return $this;
  }
  
  public function forUpdate($bool)
  {
    if (is_bool($bool)) {
      $this->forUpdate = $bool;
    } else {
      $message = __METHOD__ . "() argument must be a string.";
      throw new Sabel_Exception_InvalidArgument($message);
    }
  }
  
  public function execute($bindValues = array(), $additionalParameters = array())
  {
    $query = $this->getQuery();
    
    if (empty($bindValues)) {
      if (empty($this->bindValues)) {
        $bindValues = array();
      } else {
        $bindValues = $this->escape($this->bindValues);
        foreach ($bindValues as $k => $v) {
          $bindValues["@{$k}@"] = $v;
          unset($bindValues[$k]);
        }
      }
    }
    
    if (!empty($this->binaries)) {
      for ($i = 0, $c = count($this->binaries); $i < $c; $i++) {
        $query = str_replace(self::BINARY_IDENTIFIER . ($i + 1),
                             $this->binaries[$i]->getData(),
                             $query);
      }
    }
    
    $start  = microtime(true);
    $result = $this->driver->execute($query, $bindValues, $additionalParameters);
    
    self::$queries[] = array("sql"   => $query,
                             "time"  => microtime(true) - $start,
                             "binds" => $bindValues);
    
    if ($this->isInsert()) {
      return ($this->seqColumn === null) ? null : $this->driver->getLastInsertId();
    } elseif ($this->isUpdate() || $this->isDelete()) {
      return $this->driver->getAffectedRows();
    } else {
      return $result;
    }
  }
  
  public function setBindValue($key, $val)
  {
    $this->bindValues[$key] = $val;
    
    return $this;
  }
  
  public function setBindValues(array $values)
  {
    $this->bindValues = $values;
    
    return $this;
  }
  
  public function getBindValues()
  {
    return $this->bindValues;
  }
  
  public function appendBindValues(array $values)
  {
    $this->bindValues = array_merge($this->bindValues, $values);
    
    return $this;
  }
  
  public function isSelect()
  {
    return ($this->type === self::SELECT);
  }
  
  public function isInsert()
  {
    return ($this->type === self::INSERT);
  }
  
  public function isUpdate()
  {
    return ($this->type === self::UPDATE);
  }
  
  public function isDelete()
  {
    return ($this->type === self::DELETE);
  }
  
  public function build()
  {
    if ($this->type !== self::QUERY && $this->metadata === null) {
      $message = __METHOD__ . "() can't build sql query. "
               . "must set the metadata with setMetadata().";
      
      throw new Sabel_Exception_Runtime($message);
    }
    
    if ($this->isSelect()) {
      return $this->createSelectSql();
    } elseif ($this->isInsert()) {
      return $this->createInsertSql();
    } elseif ($this->isUpdate()) {
      return $this->createUpdateSql();
    } elseif ($this->isDelete()) {
      return $this->createDeleteSql();
    } else {
      return $this->query;
    }
  }
  
  protected function createSelectSql()
  {
    $tblName = $this->quoteIdentifier($this->table);
    $prefixedTblName = $this->quoteIdentifier(XOOPS_DB_PREFIX . "_" . $this->table);
    $projection = $this->getProjection();
    
    $sql = "SELECT $projection FROM "
         . $prefixedTblName . " AS " . $tblName
         . $this->join . $this->where
         . $this->createConstraintSql();
    
    if ($this->forUpdate) $sql .= " FOR UPDATE";
    
    return $sql;
  }
  
  protected function createInsertSql()
  {
    $sql  = "INSERT INTO " . $this->quoteIdentifier(XOOPS_DB_PREFIX . "_" . $this->table) . " (";
    $cols = array();
    $hlds = array();
    
    foreach ($this->values as $column => $value) {
      $cols[] = $this->quoteIdentifier($column);
      
      if ($value instanceof Sabel_Db_Statement_Expression) {
        unset($this->bindValues[$column]);
        $hlds[] = $value->getExpression();
      } else {
        $hlds[] = "@{$column}@";
      }
    }
    
    $sql .= implode(", ", $cols) . ") VALUES(" . implode(", ", $hlds) . ")";
    return $sql;
  }
  
  protected function createUpdateSql()
  {
    $updates = array();
    foreach ($this->values as $column => $value) {
      if ($value instanceof Sabel_Db_Statement_Expression) {
        unset($this->bindValues[$column]);
        $updates[] = $this->quoteIdentifier($column) . " = " . $value->getExpression();
      } else {
        $updates[] = $this->quoteIdentifier($column) . " = @{$column}@";
      }
    }
    
    $tblName = $this->quoteIdentifier(XOOPS_DB_PREFIX . "_" . $this->table);
    return "UPDATE $tblName SET " . implode(", ", $updates) . $this->where;
  }
  
  protected function createDeleteSql()
  {
    return "DELETE FROM " . $this->quoteIdentifier(XOOPS_DB_PREFIX . "_" . $this->table) . $this->where;
  }
  
  protected function createConstraintSql()
  {
    $sql = "";
    $c = $this->constraints;
    
    if (isset($c["order"])) {
      $sql .= " ORDER BY " . $this->quoteIdentifierForOrderBy($c["order"]);
    }
    
    if (isset($c["offset"]) && !isset($c["limit"])) {
      $sql .= " LIMIT 100 OFFSET " . $c["offset"];
    } else {
      if (isset($c["limit"]))  $sql .= " LIMIT "  . $c["limit"];
      if (isset($c["offset"])) $sql .= " OFFSET " . $c["offset"];
    }
    
    return $sql;
  }
  
  protected function getProjection()
  {
    if (empty($this->projection)) {
      $colNames = $this->quoteIdentifier($this->metadata->getColumnNames());
      return implode(", ", $colNames);
    } elseif (is_string($this->projection)) {
      return $this->projection;
    } else {
      $ps = array();
      foreach ($this->projection as $p) {
        $ps[] = $this->quoteIdentifier($p);
      }
      
      return implode(", ", $ps);
    }
  }
  
  /**
   * @param string $str
   *
   * @return string
   */
  public function escapeString($str)
  {
    $escaped = $this->escape(array($str));
    return $escaped[0];
  }
  
  public function quoteIdentifier($arg)
  {
    if (is_array($arg)) {
      foreach ($arg as &$v) {
        $v = '"' . $v . '"';
      }
      return $arg;
    } elseif (is_string($arg)) {
      return '"' . $arg . '"';
    } else {
      $message = __METHOD__ . "() argument must be a string or an array.";
      throw new Sabel_Exception_InvalidArgument($message);
    }
  }
  
  protected function quoteIdentifierForOrderBy($orders)
  {
    $results = array();
    foreach ($orders as $column => $mode) {
      if (($pos = strpos($column, ".")) !== false) {
        $tblName = convert_to_tablename(substr($column, 0, $pos));
        $results[] = $this->quoteIdentifier($tblName) . "."
                   . $this->quoteIdentifier(substr($column, $pos + 1))
                   . " " . $mode;
      } else {
        $results[] = $this->quoteIdentifier($column) . " " . $mode;
      }
    }
    
    return implode(", ", $results);
  }
}
