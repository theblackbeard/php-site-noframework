
<?php

class Login {

//1 Admin
//2 Usuario
  private $level;
  private $email;
  private $password;
  private $result;
  private $error;

  const Entity = 'usuario';

  function __construct($level) {
    $this->level = (int)$level;
  }

  public function exeLogin(array $userData) {
    $this->email = (string) strip_tags(trim($userData['email']));
    $this->password = (string) strip_tags(trim($userData['senha']));
    $this->setLogin();
  }

  function getResult() {
    return $this->result;
  }

  function getError() {
    return $this->error;
  }

  public function checkLogin() {
    if (empty($_SESSION['userlogin']) || $_SESSION['userlogin']['nivel'] < $this->level):
      return false;
    else:
      return true;
    endif;
  }

  public function checkLevel() {
    if (empty($_SESSION['userlogin']) || $_SESSION['userlogin']['nivel'] < 1):
      unset($_SESSION['userlogin']);
      return false;
    else:
      return true;
    endif;
  }

	public function checkAdmin(){
		if($_SESSION['userlogin']['nivel'] == 1):
			return true;
		else:
			return false;
		endif;
	
	}

  private function setLogin() {
    if (!$this->email || !$this->password || !Check::mail($this->email)):
      $this->error = ['Informe seu E-mail e Senha para efetuar seu Login', MSG_INFO];
      $this->result = false;
    elseif (!$this->getUser()):
      $this->error = ['Os dados informados  não são compatveis', MSG_ALERT];
      $this->result = false;
    elseif ($this->result['nivel'] < $this->level):
      $this->error = ["{$this->result['nome']}, você não tem permissão para cessar essa página", MSG_ERROR];
      $this->result = false;
    else:
      $this->execute();
    endif;
  }

  private function getUser() {
    $this->password = md5($this->password);
    $read = new Read;
    $read->exeRead(self::Entity,"WHERE email= :email AND senha= :senha","email={$this->email}&senha={$this->password}");
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
    $this->error = ["Olá {$this->result['nome']}, seja bem vindo(a). Aguarde...", MSG_ACCEPT];
    $this->result = true;
  }

}
