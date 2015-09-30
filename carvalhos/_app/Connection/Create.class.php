<?php

class Create extends Conn {

  private $table;
  private $data;
  private $result;

  /** @var PDOStatement */
  private $Create;

  /** @var PDO */
  private $conn;

  public function exeCreate($table, array $data) {
    $this->table = (string) $table;
    $this->data = $data;
    $this->getSintax();
    $this->execute();
  }

  public function getResult() {
    return $this->result;
  }

  private function connect() {
    $this->conn = parent::getConn();
    $this->Create = $this->conn->prepare($this->Create);
  }

  private function getSintax() {
    $fields = implode(',', array_keys($this->data));
    $places = ':' . implode(', :', array_keys($this->data));
    $this->Create = "INSERT INTO {$this->table}({$fields}) VALUES ({$places})";
  }

  private function execute() {
    $this->connect();
    try {
      $this->Create->execute($this->data);
      $this->result = $this->conn->lastInsertId();
    } catch (PDOException $e) {
      $this->result = null;
      MSG("Erro ao Cadastrar: {$e->getMessage()}", $e->getCode());
    }
  }

}
