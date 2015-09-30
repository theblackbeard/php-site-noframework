<?php

/**
 * Classe de Conexão[ Conn ]
 * Classe responsavel por criar conexão com o banco de dados
 * @copyright (c) 2015, Carlos Mateus Carvalho
 */
class Conn {

  private static $host = HOST;
  private static $user = USER;
  private static $pass = PASS;
  private static $dbsa = DBSA;

  /** @var PDO */
  private static $connect = null;

  private static function toConnect() {
    try {
      if (self::$connect == null):
        $dsn = 'mysql:host=' . self::$host . ';dbname=' . self::$dbsa;
        $options = [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'];
        self::$connect = new PDO($dsn, self::$user, self::$pass, $options);
      endif;
    } catch (PDOException $e) {
      PHPErro($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine());
      die;
    }

    self::$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return self::$connect;
  }

  public static function getConn() {
    return self::toConnect();
  }

}
