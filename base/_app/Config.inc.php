<?php


//Configuração da Conexão do Site com o banco de dados
define('HOST', '');
define('USER', '');
define('PASS', '');
define('DBSA', '');

//Servidor de Email do contato
define('MAILUSER', 'contato@carvcom.com');
define('MAILPASS', '');
define('MAILPORT', '2525');
define('MAILHOST', '');

//Identidade do SIte
define('SITENAME', 'Tatiana S. Santana - Design de Interiores');
define('SITEDESC', 'Portfólio acadêmico de Design de Interiores');

//Base do Site
define('HOME', 'http://tatianasouza.carvcom.com');
define('THEME', 'taty');

define('INCLUDE_PATH', HOME . '/' .'themes' . '/' . THEME);
define('REQUIRE_PATH', 'themes/'  . THEME);


//Autoload de Classes
function __autoload($class) {
    $cDir = ['Conn', 'Helpers', 'Models'];
    $iDir = null;

    foreach ($cDir as $dirName):
        if(!$iDir && file_exists(__DIR__ .'/' .$dirName. '/' . $class. '.class.php') && !is_dir(__DIR__.'/' . $dirName . '/'. $class . '.class.php')):
            include_once (__DIR__ .'/' .$dirName. '/' . $class. '.class.php');
            $iDir = true;
        endif;
    endforeach;

    if(!$iDir):
        trigger_error("Não foi possivel incluir {$class}.class.php" , E_USER_ERROR);
    endif;
}

//Tratamentos de Erros so Site
define('MSG_ACCEPT', 'alert-success');
define('MSG_INFO', 'alert-info');
define('MSG_ALERT', 'alert-warning');
define('MSG_ERROR', 'alert-danger');

function MSG($errMsg, $errNo, $errDie = null) {
  $cssClass = ($errNo == E_USER_NOTICE ? MSG_INFO : ($errNo == E_USER_WARNING ? MSG_ALERT : ($errNo == E_USER_ERROR ? MSG_ERROR : $errNo)));
  echo "<div class=\"alert {$cssClass}\" role=\"alert\">{$errMsg}<span class=\"ajax_close\"></span></div>";
  if ($errDie):
    die;
  endif;
}

function PHPErro($errNo, $errMsg, $errFile, $errLine) {
  $cssClass = ($errNo = E_USER_NOTICE ? MSG_INFO : ($errNo == E_USER_WARNING ? MSG_ALERT : ($errNo == E_USER_ERROR ? MSG_ERROR : $errNo)));
  echo "<div class=\"alert {$cssClass}\" role=\"alert\">";
  echo "<b>Erro na Linha: {$errLine} :: </b> {$errMsg} <br>";
  echo "<small>{$errFile}</small>";
  echo "<span class=\"ajax_close\"></span></div>";
  if ($errNo == E_USER_ERROR):
    die;
  endif;
}
