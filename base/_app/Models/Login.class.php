<?php

class Login {

  private $level;
  private $email;
  private $password;
  private $result;
  private $error;

  const Entity = 'cweb_usuario';

  function __construct($level) {
    $this->level = (int)$level;
  }

  public function exeLogin(array $userData) {
    $this->email = (string) strip_tags(trim($userData['user']));
    $this->password = (string) strip_tags(trim($userData['pass']));
    $this->setLogin();
  }

  function getResult() {
    return $this->result;
  }

  function getError() {
    return $this->error;
  }

  public function checkLogin() {
    if (empty($_SESSION['userlogin']) || $_SESSION['userlogin']['usu_nivel'] > $this->level):
      return false;
    else:
      return true;
    endif;
  }

  public function checkLevel() {
    if (empty($_SESSION['userlogin']) || $_SESSION['userlogin']['usu_nivel'] < 3):
      unset($_SESSION['userlogin']);
      return false;
    else:
      return true;
    endif;
  }

  private function setLogin() {
    if (!$this->email || !$this->password || !Check::mail($this->email)):
      $this->error = ['Informe seu E-mail e Senha para efetuar seu Login', MSG_INFO];
      $this->result = false;
    elseif (!$this->getUser()):
      $this->error = ['Os dados informados  não são compatveis', MSG_ALERT];
      $this->result = false;
    elseif ($this->result['usu_nivel'] < $this->level):
      $this->error = ["{$this->result['usu_nome']}, você não tem permissão para cessar essa página", MSG_ERROR];
      $this->result = false;
    else:
      $this->execute();
    endif;
  }

  private function getUser() {
    $this->password = md5($this->password);
    $read = new Read;
    $read->exeRead("cweb_usuario","WHERE usu_email= :email AND usu_senha= :senha","email={$this->email}&senha={$this->password}");
    if ($read->getResult()):
      $this->result = $read->getResult()[0];
      return true;
    else:
      return false;
    endif;
  }

  private function execute() {
    if (!session_id()):
      session_start();
    endif;

    $_SESSION['userlogin'] = $this->result;
    $this->error = ["Olá {$this->result['usu_nome']}, seja bem vindo(a). Aguarde...", MSG_ACCEPT];
    $this->result = true;
  }

}
