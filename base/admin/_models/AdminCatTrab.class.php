<?php

class AdminCatTrab {

  private $data;
  private $catId;
  private $result;
  private $error;

  const Entity = 'cweb_cattrab';

  public function exeCreate(array $data) {
    $this->data = $data;
    if (in_array('', $this->data)):
      $this->result = false;
      $this->error = ['Revisa todos espaços em branco', MSG_ALERT];
    else:
      $this->setData();
      $this->setName();
      $this->create();
    endif;
  }

  public function exeUpdate($catid, array $data) {
    $this->catId = (int)$catid;
    $this->data = $data;
    if(in_array('', $this->data)):
      $this->result = false;
      $this->error = ['Revise os campos em branco' , MSG_ALERT];
   else:
     $this->setData();
     $this->setName();
     $this->update();
    endif;
  }
  
  public function exeDelete($catid) {
      $this->catId = (int)$catid;
      $read = new Read;
      $read->exeRead(self::Entity, "WHERE ctrab_codigo = :id", "id={$this->catId}");
      if(!$read->getResult()):
        $this->result = false;
        $this->error = ['Você tentou excluir uma categoria inexistente', MSG_ALERT];  
      else:
        extract($read->getResult()[0]);
        if(!$ctrab_parente && !$this->checkCategoria()):
          $this->result = false;
          $this->error = ["A seção <b>{$ctrab_titulo}</b> possui categorias cadastradas, Remova ou altere sua sub-categorias", MSG_INFO];  
        elseif($ctrab_parente && !$this->checkTrabalhos()):
          $this->result = false;
          $this->error = ["A Categoria <b>{$ctrab_titulo}</b> possui postagens existentes, Remova ou Altere todos as postagens da categoria", MSG_ALERT];  
        else:
          $delete = new Delete;
          $delete->exeDelete(self::Entity, "WHERE ctrab_codigo= :delcat", "delcat={$this->catId}");
          $tipo = (empty($ctrab_parente) ? "Seção" : "Categoria");
          $this->result = true;
          $this->error = ["A <b>{$tipo}</b> foi exclui com sucesso" , MSG_ACCEPT];
        endif;
      endif;
  }
  
  public function getResult() {
    return $this->result;
  }

  public function getError() {
    return $this->error;
  }

  private function setData() {
    $descricao = $this->data['ctrab_descricao'];
  	unset($this->data['ctrab_descricao']);
  	$this->data = array_map('strip_tags', $this->data);
    $this->data = array_map('trim', $this->data);
    $this->data['ctrab_nome'] = Check::name($this->data['ctrab_titulo']);
    $this->data['ctrab_data'] = Check::data($this->data['ctrab_data']); 
    $this->data['ctrab_parente'] = ($this->data['ctrab_parente'] == 'null' ? null : $this->data['ctrab_parente']);
    $this->data['ctrab_descricao'] = $descricao;
  }

  private function setName() {
    $where = (!empty($this->catId) ? "ctrab_codigo != {$this->catId} AND " : '');
    $readNome =  new Read;
    $readNome->exeRead(self::Entity, "WHERE {$where} ctrab_titulo = :t", "t={$this->data['ctrab_titulo']}");
    if($readNome->getResult()):
      $this->data['ctrab_nome'] = $this->data['ctrab_nome'] . '-' . $readNome->getRowCount();
    endif;
  }
  
  private function checkCategoria() {
    $readSes = new Read;
    $readSes->exeRead(self::Entity, "WHERE ctrab_parente = :parente", "parente={$this->catId}");
    if($readSes->getResult()):
      return false;
    else:
      return true;
    endif;
  }
  
  
  private function checkTrabalhos() {
    $readTrab = new Read;
    $readTrab->exeRead("cweb_trabalho", "WHERE trab_categoria = :categoria", "categoria={$this->catId}");
    if($readTrab->getResult()):
      return false;
    else:
      return true;
    endif;
  }

  private function create() {
    $create = new Create;
    $create->exeCreate(self::Entity, $this->data);
    if($create->getResult()):
      $tipo  =(empty($this->data['ctrab_parente']) ? 'Seção' : 'Categoria');
      $this->result = $create->getResult();
      //$this->error = ["A <b>{$tipo}</b> foi cadastrada com sucesso!, clique <a href=\"painel.php?mt=categoria/index\">Aqui</a> para listar sua categoria" , MSG_ACCEPT];
    endif;
  }
  
  private function update() {
    $update = new Update;
    $update->exeUpdate(self::Entity, $this->data, "WHERE ctrab_codigo = :id", "id={$this->catId}");
    if($update->getResult()):
     $tipo = (empty($this->data['ctrab_parente']) ? 'Seção' : 'Categoria');
    $this->result = true;
    //$this->error = ["<b>Sucesso</b> A {$tipo} {$this->data['ctrab_titulo']} foi atualizada com sucesso!", MSG_ACCEPT];
    endif;
  }

}
