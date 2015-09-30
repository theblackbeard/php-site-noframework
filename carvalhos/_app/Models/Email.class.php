<?php
require ('_app/Lib/PHPMailer/class.phpmailer.php');

class Email {
  
  /** @var PHPMailer*/
  private $email;
  private $data;
  
  private $assunto;
  private $mensagem;
  
  private $remetenteNome;
  private $remetenteEmail;
   
  private $destinoNome;
  private $destinoEmail;
  
  
  private $resultado;
  private $erro;
  
  
  function __construct() {
    $this->email =  new PHPMailer;
    $this->email->Host = MAILHOST;
    $this->email->Port = MAILPORT;
    $this->email->Username = MAILUSER;
    $this->email->Password = MAILPASS;
    $this->email->CharSet = 'UTF-8';
  }
  
  public  function enviar(array $data) {
    $this->data = $data;
    $this->Clear();
   
    if(in_array('', $this->data)):
      $this->erro = ['Erro ao enviar Email. preencha todos os campos requisitados', MT_INFOR];
      $this->resultado = false;
    elseif(!Check::mail($this->data['remetenteEmail'])):
      $this->erro = ['Erro ao enviar Email. Email incorreto', MT_INFOR];
      $this->resultado = false;
    else: 
      $this->setEmail();
      $this->setConfig();
      $this->sendMail();
    endif;
  }
  
  
  function getResultado() {
    return $this->resultado;
  }

  function getErro() {
    return $this->erro;
  }

    
  private function Clear() {
    array_map('strip_tags', $this->data);
    array_map('trim', $this->data);
  }
  
  private function setEmail() {
    $this->assunto = $this->data['assunto'];
    $this->mensagem = $this->data['mensagem'];
    $this->remetenteNome = $this->data['remetenteNome'];
    $this->remetenteEmail = $this->data['remetenteEmail'];
    $this->destinoNome = $this->data['destinoNome'];
    $this->destinoEmail = $this->data['destinoEmail'];
  
    $this->data = null;
    $this->setMsg();
  }
  
  private function setMsg() {
    $this->mensagem = "{$this->mensagem}<hr><small>Recebida em:" . date('d/m/Y H:i') . "</small>";
  }
  
  private function setConfig() {
    $this->email->IsSMTP();
    $this->email->SMTPAuth = true;
    $this->email->SMTPSecure = "tls";
    $this->email->IsHTML();
    
    $this->email->From = MAILUSER;
    $this->email->FromName = $this->remetenteNome;
    $this->email->AddReplyTo($this->remetenteEmail, $this->remetenteNome);
    
    $this->email->Subject = $this->assunto;
    $this->email->Body = $this->mensagem;
    $this->email->AddAddress($this->destinoEmail, $this->destinoNome);
    
    
  }
  
  private function sendMail() {
    if($this->email->Send()):
      $this->erro = ['Mensagem Enviada com sucesso', MSG_ACCEPT];
      $this->resultado = true;
    else:
      $this->erro = ["Erro ao Enviar: Entre em contato com admin, erro: ({$this->email->ErrorInfo})" , MSG_ERROR];
      $this->resultado = false;
    endif;
  }
  
}
