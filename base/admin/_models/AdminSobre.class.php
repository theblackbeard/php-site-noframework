<?php

class AdminSobre {
   private $data;
   private $sobreId;
   private $result;
   private $error;
   
   const Entity = 'cweb_sobre';
   
   public function exeCreate(array $data) {
     $this->data = $data;
     if(in_array('', $this->data)):
       $this->result = false;
       $this->error = ['Verifique todos os campos em brancos' , MSG_INFO];
      else:
        $lerLimite = new Read;
        $lerLimite->exeRead(self::Entity);
        if($lerLimite->getRowCount() <= 0):
          $this->setData();
          $this->setName();
          $this->create();
        else:
          $this->result = false;
          $this->error = ["Você apenas poderá adicionar uma página sobre" , MSG_INFO];
        endif;
     endif;
   }
   
   public function exeUpdate($sobreId, array $data) {
     $this->sobreId = (int)$sobreId;
     $this->data = $data;
     if(in_array('', $this->data)):
       $this->result = false;
       $this->error = ["Revise todos campos vazios" , MSG_INFO];
     else:
       $this->setData();
       $this->setName();
       $this->update();
     endif;
   }
   
   public function exeDelete($sobreId) {
     $this->sobreId = (int)$sobreId;
     $read = new Read;
     $read->exeRead(self::Entity, "WHERE sobre_codigo = :id" , "id={$this->sobreId}");
     if(!$read->getResult()):
       $this->result = false;
       $this->error = ["Exclusao de Página inexistente" , MSG_ERROR];
     else:
       $delete = new Delete;
       $delete->exeDelete(self::Entity, "WHERE sobre_codigo = :delId", "delId={$this->sobreId}");
       $this->result = true;
       $this->error = ["A Página foi exlcuida com sucesso!", MSG_ACCEPT];
     endif;
   }
   
   public function getResult() {
     return $this->result;
   }

   public function getError() {
     return $this->error;
   }

      
   private function setData() {
     //$this->data = array_map('strip_tags', $this->data);
     $this->data = array_map('trim', $this->data);
     $this->data['sobre_nome'] = Check::name($this->data['sobre_titulo']);
   }
   
   private function setName() {
     $where = (!empty($this->sobreId) ? "sobre_codigo != {$this->sobreId} AND " : '');
     $readName = new Read;
     $readName->exeRead(self::Entity, "WHERE {$where} sobre_titulo = :t", "t={$this->data['sobre_titulo']}");
     if($readName->getResult()):
       $this->data['sobre_nome'] = $this->data['sobre_nome'] . '-' . $readName->getRowCount();
     endif;
   }

   private function create() {
     $create = new Create;
     $create->exeCreate(self::Entity, $this->data);
     if($create->getResult()):
       $this->result = $create->getResult();
       $this->error = ["<b>Sucesso: </b> a página sobre foi cafastrada com sucesso", MSG_ACCEPT];
     endif;
   }
   
   private function update() {
     $update = new Update;
     $update->exeUpdate(self::Entity, $this->data, "WHERE sobre_codigo = :id", "id={$this->sobreId}");
     if($update->getResult()):
       $this->result = true;
       $this->error = ["Informação atualizada com sucesso", MSG_ACCEPT];
     endif;
   }

}
