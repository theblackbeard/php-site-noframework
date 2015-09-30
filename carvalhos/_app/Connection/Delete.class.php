<?php
class Delete extends Conn{

  private $table;
  private $term;
  private $places;
  private $result;
  
  /** @var PDOStatement */
  private $Delete;
  
  /** @var PDO */
  private $conn;
  
  public function exeDelete($table, $term, $parseString) {
    $this->table = (string)$table;
    $this->term = (string)$term;
    parse_str($parseString, $this->places);
    $this->getSyntax();
    $this->execute();
  }
  
  public function getResult() {
    return $this->result;
  }

  public function getRowCount() {
    return $this->Delete->rowCount();
  }
  
  public function setPlaces($parseString) {
    parse_str($parseString, $this->places);
    $this->getSyntax();
    $this->execute();
  }
  
  
  private function connect() {
    $this->conn = parent::getConn();
    $this->Delete = $this->conn->prepare($this->Delete);
  }
  
  private function getSyntax() {
    $this->Delete = "DELETE FROM {$this->table} {$this->term}";
  }
  
  private function execute() {
    $this->connect();
    try{
      $this->Delete->execute($this->places);
      $this->result = true;
    } catch (PDOException $e) {
      $this->result = null;
      MSG("Erro ao Excluir:  {$e->getMessage()}" , $e->getCode());
    }
  }
  
  
  
}
