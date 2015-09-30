<?php

class Update extends Conn {

  private $table;
  private $data;
  private $term;
  private $places;
  private $result;

  /** @var PDOStatement */
  private $Update;

  /** @var PDO */
  private $conn;

  public function exeUpdate($table, array $data, $term, $parseString) {
    $this->table = (string) $table;
    $this->data = $data;
    $this->term = $term;
    parse_str($parseString, $this->places);
    $this->getSyntax();
    $this->execute();
  }

  public function getResult() {
    return $this->result;
  }

  public function getRowCount() {
    return $this->Update->rowCount();
  }

  public function setPlaces($parseString) {
    parse_str($parseString, $this->places);
    $this->getSyntax();
    $this->execute();
  }

  private function connect() {
    $this->conn = parent::getConn();
    $this->Update = $this->conn->prepare($this->Update);
  }

  private function getSyntax() {
    foreach ($this->data as $key => $value):
      $places[] = $key . '= :' . $key;
    endforeach;
    $places = implode(', ', $places);
    $this->Update = "UPDATE {$this->table} SET {$places} {$this->term}";
  }

  private function execute() {
    $this->connect();
    try {
      $this->Update->execute(array_merge($this->data, $this->places));
      $this->result = true;
    } catch (PDOException $e) {
      $this->result = null;
      MSG("Erro ao Atualizar: {$e->getMessage()}", $e->getCode());
    }
  }

}
