<?php

class AdminUsuario {

  private $data;
  private $usuarioId;
  private $result;
  private $error;
  
  const Entity = 'cweb_usuario';
  
  public function exeCreate(array $data) {
    $this->data = $data;
    if(in_array('', $this->data)):
      $this->result = false;
      $this->error = ['Revise tdos os campos em branco', MSG_ERROR];
    elseif(!Check::mail($this->data['usu_email'])):
      $this->result = false;
      $this->error = ['E-mail Invalido', MSG_ERROR];
    elseif(!$this->getEmail()):
       $this->result = false;
      $this->error = ['E-mail Já Existente', MSG_ERROR];
    else:
      $this->setData();
      $this->create();
    endif;
  }
  
  public function exeUpdate($usuarioId, array $data) {
    $this->usuarioId = (int)$usuarioId;
    $this->data = $data;
    if(in_array('', $this->data)):
      $this->result = false;
      $this->error = ["Revise os Campos em Brancos", MSG_ERROR];
    elseif(!Check::mail($this->data['usu_email'])):
      $this->result = false;
      $this->error = ["Email Invalido", MSG_ERROR];
    else:
      $this->setData();
      if($this->data['usu_senha'] == 'nula'):
        unset($this->data['usu_senha']);
        $this->update();
      else:
        $this->update();
      endif;
     
    endif;
  }
  
  public function exeDelete($usuarioId){
    $this->usuarioId = (int)$usuarioId;
    $read = new Read;
    $read->exeRead(self::Entity, "WHERE usu_codigo=:delId", "delId={$this->usuarioId}");
    if(!$read->getResult()):
      $this->result = false;
      $this->error = ["Usuario Inexistente" , MSG_ERROR];
    else:
      extract($read->getResult()[0]);
      if($usu_nivel < 3):
        $delete = new Delete;
        $delete->exeDelete(self::Entity, "WHERE usu_codigo=:id", "id={$this->usuarioId}");
        $this->result = true;
        $this->error = ["Usuario <b>{$usu_email}</b> Ecluido com Sucesso", MSG_ACCEPT];
      else:
          $this->result = false;
          $this->error = ['Imposivel de Excluir Administradores', MSG_ERROR];
      endif;
    endif;
  }


  public function getResult() {
    return $this->result;
  }

  public function getError() {
    return $this->error;
  }

  private function getEmail() {
    $read = new Read;
    $read->exeRead(self::Entity, "WHERE usu_email = :email", "email={$this->data['usu_email']}");
    if($read->getResult()):
      return false;
    else:
      return true;
    endif;
  }
  
  private function setData() {
    $this->data = array_map('strip_tags', $this->data);
    $this->data = array_map('trim', $this->data);
  }
  
  private function create() {
    $this->data['usu_senha'] = md5($this->data['usu_senha']);
    $this->data['usu_registro'] = Check::data(date('d/m/Y H:i:s'));
    $this->data['usu_ultimoacesso'] = Check::data(date('d/m/Y H:i:s'));
    $create = new Create;
    $create->exeCreate(self::Entity, $this->data);
    if($create->getResult()):
      $this->result = $create->getResult();
      $this->error = ["<b>Sucesso! </b> usuario registrado com sucesso!" , MSG_ACCEPT];
    endif;
     
  }
  
  private function update() {
    if(isset($this->data['usu_senha'])):
      $this->data['usu_Senha'] = md5($this->data['usu_senha']);
    
    endif;
     $this->data['usu_ultimoacesso'] = Check::data(date('d/m/Y H:i:s'));
     $update = new Update;
    $update->exeUpdate(self::Entity, $this->data, "WHERE usu_codigo = :id", "id={$this->usuarioId}");
    if($update->getResult()):
      $this->result = true;
      $this->error = ["Usuario Atualizado com Sucesso" , MSG_ACCEPT];
    endif;
      
      
  }
  
}
