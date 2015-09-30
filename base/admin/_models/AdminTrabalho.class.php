<?php
class AdminTrabalho {

    private $data;
    private $trabalhoId;
    private $result;
    private $error;
    
    const Entity = 'cweb_trabalho';
    
    public function getResult() {
      return $this->result;
    }

    public function getError() {
      return $this->error;
    }
    
    public function exeCreate(array $data) {
      $this->data = $data;
      if(in_array('', $this->data)):
        $this->result = false;
        $this->error = ['Revise os espaços em brancos' , MSG_ERROR];
      else:
        $this->setData();
        $this->setName();
        if($this->data['trab_foto']):
          $upload = new Upload;
          $upload->imagem($this->data['trab_foto'], $this->data['trab_nome']);
        endif;
        if(isset($upload) && $upload->getResultado()):
          $this->data['trab_foto'] = $upload->getResultado();
          $this->create();
        else:
          $this->data['trab_foto'] = null;
          $this->create();
        endif;
      endif;
      
    }
    
    
    public function exeUpdate($trabalhoId , array $data) {
        $this->trabalhoId = (int)$trabalhoId;
        $this->data = $data;
        
        if(in_array('', $this->data)):
          $this->error = ["Revise seus Espaços em Brancos", MSG_ALERT];
          $this->result = false;
        else:
          $this->setData();
          $this->setName();
          if(is_array($this->data['trab_foto'])):
            $readCapa = new Read;
            $readCapa->exeRead(self::Entity, "WHERE trab_codigo = :trabid", "trabid={$this->trabalhoId}");
            $capa = '../uploads/' . $readCapa->getResult()[0]['trab_foto'];
            if(file_exists($capa) && !is_dir($capa)):
              unlink($capa);
            endif;
            
            $uploadCapa = new Upload;
            $uploadCapa->imagem($this->data['trab_foto'], $this->data['trab_nome']);
          endif;
          
          if(isset($uploadCapa) && $uploadCapa->getResultado()):
            $this->data['trab_foto'] = $uploadCapa->getResultado();
            $this->update();
          else:
            unset($this->data['trab_foto']);
              $this->update();
          endif;
        endif;
    }
    
    public function exeDelete($trabalhoId) {
      $this->trabalhoId = (int)$trabalhoId;
      $trabalho = new Read;
      $trabalho->exeRead(self::Entity, "WHERE trab_codigo = :id", "id={$this->trabalhoId}");
      if(!$trabalho->getResult()):
        $this->error = ["O Trabalho não existe no Sistema", MSG_ERROR];
        $this->result = false;
      else:
        $trabalhoDel = $trabalho->getResult()[0];
        if(file_exists('../uploads/' . $trabalhoDel['trab_foto']) && !is_dir('../uploads/' . $trabalhoDel['trab_foto'])):
          unlink('../uploads/' . $trabalhoDel['trab_foto']);
        endif;
        
        $lerGaleria = new Read;
        $lerGaleria->exeRead("cweb_galeria", "WHERE post_codigo = :id" , "id={$this->trabalhoId}"); 
        if($lerGaleria->getResult()):
          foreach ($lerGaleria->getResult() as $mtdel):
              if(file_exists('../uploads/' .  $mtdel['galeria_imagem']) && !is_dir('../uploads/' . $mtdel['galeria_imagem'])):
                unlink('../uploads/' . $mtdel['galeria_imagem']);
              endif;
          endforeach;
        endif;
        
        $deleta = new Delete;
        $deleta->exeDelete("cweb_galeria", "WHERE post_codigo = :mttrab", "mttrab={$this->trabalhoId}");
        $deleta->exeDelete(self::Entity, "WHERE trab_codigo = :idtrab", "idtrab={$this->trabalhoId}");
        
        $this->error = ["O Trabalho {$trabalhoDel['trab_titulo']} foi removido com sucesso!" , MSG_ACCEPT];
        $this->result = true;
      endif;
    }
    
    public function exeStatus($trabalhoid, $trabalhoStatus) {
      $this->trabalhoId = (int)$trabalhoid;
      $this->data['trab_status'] = (string)$trabalhoStatus;
      $update = new Update;
      $update->exeUpdate(self::Entity, $this->data, "WHERE trab_codigo = :id", "id={$this->trabalhoId}");
    }
    
    public function removeGallery($imgId) {
      $this->trabalhoId = (int)$imgId;
      $readGal = new Read;
      $readGal->exeRead("cweb_galeria", "WHERE galeria_codigo =:mt", "mt={$this->trabalhoId}");
      if($readGal->getResult()):
        $imagem = '../uploads/'. $readGal->getResult()[0]['galeria_imagem'];
        if(file_exists($imagem) && !is_dir($imagem)):
          unlink($imagem);
        endif;
        
        $deleta = new Delete;
        $deleta->exeDelete("cweb_galeria", "WHERE galeria_codigo =:id", "id={$this->trabalhoId}");
        if($deleta->getResult()):
          $this->error = ["A Imagem foi removida com sucesso", MSG_ACCEPT];
          $this->result = true;
        endif;
      endif;
    }
    
    public function sendGallery(array $imagens, $trabid) {
      $this->trabalhoId = (int)$trabid;
      $this->data = $imagens;
      $imagemName =new Read;
      $imagemName->exeRead(self::Entity, "WHERE trab_codigo =:id" , "id={$this->trabalhoId}");
      if(!$imagemName->getResult()):
        $this->error = ["Erro ao Enviar Galeria, o indice {$this->trabalhoId} não foi encontrado.", MSG_ALERT];
        $this->result = false;
      else:
        $imagemName = $imagemName->getResult()[0]['trab_nome'];
        $gbFiles = array();
        $gbCount = count($this->data['tmp_name']);
        $gbKeys = array_keys($this->data);
        
        for($gb = 0; $gb < $gbCount; $gb++):
          foreach ($gbKeys as $keys):
            $gbFiles[$gb][$keys] = $this->data[$keys][$gb];
          endforeach;
        endfor;
        
        $gbSend = new Upload;
        $i = 0;
        $u = 0;
        
        foreach ($gbFiles as $gbUploads):
          $i++;
          $imagemName = "{$imagemName}-mt-{$this->trabalhoId}-" . (substr(md5(time() + $i), 0,5));
          $gbSend->imagem($gbUploads, $imagemName);
          
          if($gbSend->getResultado()):
            $gbImagem = $gbSend->getResultado();
            $gbCreate =['post_codigo' => $this->trabalhoId, "galeria_imagem" => $gbImagem, "galeria_data" => date('Y-m-d H:i:s')];
            $insertGb = new Create;
            $insertGb->exeCreate("cweb_galeria", $gbCreate);
            $u++;
          endif;
        endforeach;
        if($u > 1):
          $this->result = true;
          $this->error = ["Galeria Atualizada: Foram enviadas {$u} Imagens para galeria desse trabalho"];
        endif;
        
      endif;
    }
    
    private function setData() {
        $foto = $this->data['trab_foto'];
        $descricao = $this->data['trab_descricao'];
        unset($this->data['trab_foto'], $this->data['trab_descricao']);
        $this->data = array_map('strip_tags', $this->data);
        $this->data = array_map('trim', $this->data);
        
        $this->data['trab_nome'] = Check::name($this->data['trab_titulo']);
        $this->data['trab_data'] = Check::data($this->data['trab_data']);
        $this->data['trab_tipo'] = 'Trabalho';
        $this->data['trab_foto'] = $foto;
        $this->data['trab_descricao'] = $descricao;
        $this->data['trab_cat_parente'] = $this->getCatParente();
        
    }
    
    
    private function setName() {
      $where = (isset($this->trabalhoId) ? "trab_codigo != {$this->trabalhoId} AND " : '');
      $readNome = new Read;
      $readNome->exeRead(self::Entity, "WHERE {$where} trab_titulo = :t" , "t={$this->data['trab_titulo']}");
      if($readNome->getResult()):
        $this->data['trab_nome'] = $this->data['trab_nome'] .'-' .$readNome->getRowCount();
      endif;
    }

    private function  getCatParente() {
      $readCat = new Read;
      $readCat->exeRead("cweb_cattrab", "WHERE ctrab_codigo = :id", "id={$this->data['trab_categoria']}");
      if($readCat->getResult()):
        return $readCat->getResult()[0]['ctrab_parente']; 
      else:
        return null;
      endif;
    }
    
    private function create() {
      $cad = new Create;
      $cad->exeCreate(self::Entity, $this->data);
      if($cad->getResult()):
        $this->result = $cad->getResult();
        $this->error = ["O Trabalho {$this->data['trab_titulo']} foi cadastrado com sucesso" , MSG_ACCEPT];
      endif;
    }
    
    private function update() {
      $update = new Update;
      $update->exeUpdate(self::Entity, $this->data, "WHERE trab_codigo = :id", "id={$this->trabalhoId}");
      if($update->getResult()):
        $this->error = ["O Trabalho <b>{$this->data['trab_titulo']}</b> foi cadstrado com sucesso!", MSG_ACCEPT];
        $this->result = true;
      endif;
          
    }

}
