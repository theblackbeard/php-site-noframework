<?php

class AdminCatPost {

        private $data;
        private $categoryId;
        private $error;
        private $result;

        const Entity = 'categoria_post';
    public function getError()
    {
        return $this->error;
    }

    public function getResult()
    {
        return $this->result;
    }


    public function exeCreate(array $data){
        $this->data = $data;
        if(in_array('' , $this->data)):
            $this->result = false;
            $this->error = ['Revise campos em branco', MSG_ALERT];
        else:

            $this->setData();
            $this->setName();
            $this->create();
        endif;
    }

    public function exeUpdate($categoryId, array $data){

        $this->categoryId = (int)$categoryId;
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

    public function exeDelete($categoryId){
        $this->categoryId = (int)$categoryId;
        $read = new Read;
        $read->exeRead(self::Entity, "WHERE codigo = :id", "id={$this->categoryId}");
        if(!$read->getResult()):
            $this->result = false;
            $this->error = ['Você tentou excluir uma categoria inexistente', MSG_ALERT];
        else:
            extract($read->getResult()[0]);
            if(!$subcodigo && !$this->checkCategoria()):
                $this->result = false;
                $this->error = ["A seção <b>{$titulo}</b> possui categorias cadastradas, Remova ou altere sua sub-categorias", MSG_INFO];
            elseif($subcodigo && !$this->checkPosts()):
                $this->result = false;
                $this->error = ["A Categoria <b>{$titulo}</b> possui postagens existentes, Remova ou Altere todos as postagens da categoria", MSG_ALERT];
            else:
                $delete = new Delete;
                $delete->exeDelete(self::Entity, "WHERE codigo= :delcat", "delcat={$this->categoryId}");
                $this->result = true;
                $this->error = ["A Categoria foi excluida com sucesso" , MSG_ACCEPT];
            endif;
        endif;
    }

    private function setData(){
        $description = $this->data['descricao'];
        unset($this->data['descricao']);
        $this->data = array_map("strip_tags",$this->data );
        $this->data = array_map("trim",$this->data );
        $this->data['nome'] = Check::name($this->data['titulo']);
        $this->data['subcodigo'] = ($this->data['subcodigo'] == 'null' ? null : $this->data['subcodigo']);
        $this->data['descricao'] = $description;
   }

    private function setName(){
        $where = (!empty($this->catId) ? "codigo != {$this->catId} AND " : '');
        $readName =  new Read;
        $readName->exeRead(self::Entity, "WHERE {$where} titulo = :t", "t={$this->data['titulo']}");
        if($readName->getResult()):
            $this->data['nome'] = $this->data['nome'] . '-' . $readName->getRowCount();
        endif;
    }


    private function checkCategoria(){
        $readSes = new Read;
        $readSes->exeRead(self::Entity, "WHERE subcodigo = :parente", "parente={$this->categoryId}");
        if($readSes->getResult()):
            return false;
        else:
            return true;
        endif;
    }

    private function checkPosts(){
        $readTrab = new Read;
        $readTrab->exeRead("post", "WHERE categoriafilho = :categoria", "categoria={$this->categoryId}");
        if($readTrab->getResult()):
            return false;
        else:
            return true;
        endif;
    }


    private function create(){
        $create = new Create;
        $create->exeCreate(self::Entity, $this->data);
        if($create->getResult()):
            $this->result = $create->getResult();
        endif;
    }


    private function update(){
        $update = new Update;
        $update->exeUpdate(self::Entity, $this->data, "WHERE codigo = :id", "id={$this->categoryId}");
        if($update->getResult()):
            $this->result = true;
        endif;
    }



} 