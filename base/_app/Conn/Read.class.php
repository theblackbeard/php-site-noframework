<?php

class Read extends Conn{

    private $select;
    private $places;
    private $result;
    
    /** @var PDOStatement */
    private $Read;
    
    /** @var PDO */
    private $conn;
    
    public function exeRead($table, $terms=null, $parseString=null) {
        if(!empty($parseString)):
            $this->places = $terms;
            parse_str($parseString, $this->places);
        endif;
        $this->select = "SELECT * FROM {$table} {$terms}";
        $this->execute();
    }
    
    public function getResult() {
        return $this->result;
    }
    
    public function getRowCount() {
        return $this->Read->rowCount();
    }
    
    public function fullRead($query, $parseString=null) {
        $this->select = (string)$query;
        if(!empty($parseString)):
            parse_str($parseString, $this->places);
        endif;
        $this->execute();
    }
    
    public function setPlaces($parseString) {
        parse_str($parseString, $this->places);
        $this->execute();
    }
    
    private function connect() {
        $this->conn = parent::getConn();
        $this->Read = $this->conn->prepare($this->select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
    }
    
    private function getQuery() {
        if($this->places):
            foreach ($this->places as $vinculo => $valor):
                if($vinculo == 'limit' || $vinculo == 'offset'):
                    $valor = (int) $valor;
                endif;
                $this->Read->bindValue(":{$vinculo}", $valor, (is_int($valor) ? PDO::PARAM_INT : PDO::PARAM_STR));
            endforeach;
        endif;
    }
    
    private function execute() {
        $this->connect();
        try{
            $this->getQuery();
            $this->Read->execute();
            $this->result = $this->Read->fetchAll();
        } catch (PDOException $e) {
            $this->result = null;
            MSG("Erro ao Ler: {$e->getMessage()}", $e->getCode());
        }
    }
}
