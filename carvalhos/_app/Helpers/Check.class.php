<?php

class Check {
 
    private static $data;
    private static $format;
    
    //Verificando se Email esta correto
    public static function mail($email) {
      self::$data = (string)$email;
      self::$format = '/[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\.\-]+\.[a-z]{2,4}$/';
      if(preg_match(self::$format, self::$data)):
        return true;
      else:
        return false;
      endif;
    }
    
    //Verificando se há carecteres especiais
    public static function name($name) {
      self::$format = array();
      self::$format['a'] = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª' ;
      self::$format['b'] = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
      self::$data = strtr(utf8_decode($name), utf8_decode(self::$format['a']), self::$format['b']);
      self::$data = strip_tags(trim(self::$data));
      self::$data = str_replace(' ', '-', self::$data);
      self::$data = str_replace(array('-----' , '----' , '---' , '--'), '-', self::$data);
      
      return strtolower(utf8_encode(self::$data));
    }



    //Formatada data
    public static function data($data) {
      self::$format = explode(' ', $data);
      self::$data = explode('/', self::$format[0]);
      if(empty(self::$format[1])):
        self::$format[1] = date('H:i:s');
      endif;
      self::$data = self::$data[2] . '-' . self::$data[1] . '-' . self::$data[0] . ' ' . self::$format[1];
      return self::$data;
    }
    
    //Contando Palavras
    public static function word($string, $limit, $pointer=null) {
      self::$data = strip_tags(trim($string));
      self::$format = (int)$limit;
      
      $arrWords = explode(' ', self::$data);
      $numWords = count($arrWords);
      $newWords = implode(' ', array_slice($arrWords, 0, self::$format));
      $pointer =(empty($pointer) ? '...' : ' ' . $pointer);
      $result = (self::$format < $numWords ? $newWords . $pointer : self::$data);
      
      return $result;
    }
    
    
    //Procurando dados por nome
    public static  function functionName($catName) {

    $read = new Read;
      $read->exeRead('cweb_cattrab', "WHERE ctrab_nome = :name", "name={$catName}");
      if($read->getResult()):
        return $read->getResult()[0]['ctrab_codigo'];
      else:
        return "Nunguma Categoria {$catName}";
        die;
      endif;
    }

    public static  function buscaCat(){
        $cat = new Read;
        $cat->exeRead("cweb_cattrab" , "LIMIT :limit" , "limit=1");
        if($cat->getResult()):
            return $cat->getResult()[0]['ctrab_codigo'];
        else:
            return "Nenhum Categoria";
            die;
        endif;
    }

    public static function nomeSobre($nome){
        $read = new Read;
        $read->exeRead('cweb_sobre', "WHERE sobre_nome =:nome" , "nome={$nome}");
        if($read->getResult()):
            return $read->getResult()[0]['sobre_codigo'];
        else:
            return "Nenhum";
            die;
        endif;

    }

    //Estilizando Imagens
    public static function image($imageUrl, $imageDes, $imageW = null, $imageH = null) {
      self::$data = $imageUrl;
      if(file_exists(self::$data) && !is_dir(self::$data)):
        $patch  = HOME;
        $image = self::$data;
        return "<img src=\"{$patch}/tim.php?src={$patch}/{$image}&w={$imageW}&h={$imageH}\" alt=\"{$imageDes}\" title=\"{$imageDes}\"/>";
      else:
        return false;
      endif;
    }
  
}
