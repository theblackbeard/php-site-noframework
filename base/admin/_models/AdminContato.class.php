<?php

class AdminContato {

    private $data;
    private $contactId;
    private $result;
    private $error;

    const Entity = 'cweb_contato';


    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }



    public function exeCreate(array $data){
        $this->data = $data;
        if(in_array('' , $this->data)):
            $this->error = ['Revise os Espacos em Brancos' , MSG_ALERT];
            $this->result = false;
        elseif(!$this->checkContato()):
            $this->error = ["Já existe uma pagina de contato! Exclua ou Altera a existente, clique <a href=\"painel.php?mt=contato/index\"><b>Aqui</b></a> para visualizar" , MSG_ERROR];
            $this->result = false;
        else:
            $this->setData();
            $this->setName();
            if($this->data['cont_foto']):
                $upload = new Upload;
                $upload->imagem($this->data['cont_foto'], $this->data['cont_nome']);
            endif;
            if(isset($upload) && $upload->getResultado()):
                $this->data['cont_foto'] = $upload->getResultado();
                $this->create();

             else:
                $this->data['cont_foto'] = null;
                $this->create();
             endif;
            endif;
    }

    public function exeUpdate($contactId, array $data){
        $this->contactId = (int)$contactId;
        $this->data = $data;
        if(in_array('' , $this->data)):
            $this->result = false;
            $this->error = ["Revise campos em brancos" , MSG_ALERT];
        else:
            $this->setData();
            $this->setName();
            if(is_array($this->data['cont_foto'])):
                $readCapa =new Read;
            $readCapa->exeRead(self::Entity, "WHERE cont_codigo = :contid", "contid={$this->contactId}" );
            $capa = '../uploads/' . $readCapa->getResult()[0]['cont_foto'];
            if(file_exists($capa) && !is_dir($capa)):
                unlink($capa);
            endif;

            $uploadCapa = new Upload;
            $uploadCapa->imagem($this->data['cont_foto'], $this->data['cont_nome']);
            endif;

            if(isset($uploadCapa) && $uploadCapa->getResultado()):
                $this->data['cont_foto'] = $uploadCapa->getResultado();
                $this->update();
            else:
                unset($this->data['cont_foto']);
                $this->update();
            endif;
            endif;
    }

    public function exeDelete($contactId){
        $this->contactId = (int)$contactId;
        $read = new Read;
        $read->exeRead(self::Entity, "WHERE cont_codigo = :id" , "id={$this->contactId}");
        if(!$read->getResult()):
            $this->result = false;
            $this->error = ["Exclusão de pagina inexistente"];
        else:
            $delete = new Delete;
            $delete->exeDelete(self::Entity, "WHERE cont_codigo = :contid" , "contid={$this->contactId}");
            $this->result = true;
            $this->error = ["A pagina foi excluida com sucesso!" , MSG_ACCEPT];
        endif;

    }


    private function  checkContato(){
        $readContato = new Read;
        $readContato->exeRead(self::Entity);
        if($readContato->getRowCount() > 0):
            return false;
        else:
            return true;
        endif;
    }

    private function setData(){
        $foto = $this->data['cont_foto'];
        $descricao = $this->data['cont_descricao'];
        unset($this->data['cont_foto'],$this->data['cont_descricao'] );

        $this->data = array_map('strip_tags' , $this->data);
        $this->data = array_map('trim' , $this->data);

        $this->data['cont_nome'] = Check::name($this->data['cont_titulo']);
        $this->data['cont_foto'] = $foto;
        $this->data['cont_descricao'] = $descricao;

    }

    private function setName(){
        $where = (isset($this->contactId) ? "cont_codigo != {$this->contactId} AND " : '' );
        $readNome = new Read;
        $readNome->exeRead(self::Entity, "WHERE {$where} cont_titulo = :t" , "t={$this->data['cont_titulo']}");
        if($readNome->getResult()):
            $this->data['cont_nome'] = $this->data['cont_nome'] . '-' . $readNome->getRowCount();
            endif;
    }


    private function create(){

        $cad = new Create;
        $cad->exeCreate(self::Entity, $this->data);
        if($cad->getResult()):
            $this->result = $cad->getResult();
       endif;
    }

    private function update(){
        $update = new Update;
        $update->exeUpdate(self::Entity, $this->data, "WHERE cont_codigo = :id" , "id={$this->contactId}");
        if($update->getResult()):
            $this->result = true;
        endif;
    }

} 