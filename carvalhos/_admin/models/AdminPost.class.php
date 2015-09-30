<?php
class AdminPost {
    private $data;
    private $postId;
    private $result;
    private $error;

    const Entity = 'post';

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
        if(in_array('', $this->data)):
            $this->result = false;
            $this->error = ['Revise seus campos em brancos', MSG_ALERT];
        else:
            $this->setData();
            $this->setName();
            if($this->data['foto']):
                $upload = new Upload;
                $upload->imagem($this->data['foto'], $this->data['nome']);
            endif;
            if(isset($upload) && $upload->getResultado()):
                $this->data['foto'] = $upload->getResultado();
                $this->create();
            else:
                $this->data['foto'] = null;
                $this->create();
            endif;

        endif;
    }


    public function exeUpdate($postId, array $data) {
        $this->postId = (int)$postId;
        $this->data = $data;

        if(in_array('', $this->data)):
            $this->error = ["Revise seus Espaços em Brancos", MSG_ALERT];
            $this->result = false;
        else:
            $this->setData();
            $this->setName();
            if(is_array($this->data['foto'])):
                $readCapa = new Read;
                $readCapa->exeRead(self::Entity, "WHERE codigo = :trabid", "trabid={$this->postId}");
                $capa = '../uploads/' . $readCapa->getResult()[0]['foto'];
                if(file_exists($capa) && !is_dir($capa)):
                    unlink($capa);
                endif;

                $uploadCapa = new Upload;
                $uploadCapa->imagem($this->data['foto'], $this->data['nome']);
            endif;

            if(isset($uploadCapa) && $uploadCapa->getResultado()):
                $this->data['foto'] = $uploadCapa->getResultado();

                $this->update();
            else:
                unset($this->data['foto']);
                $this->update();
            endif;
        endif;
    }

    public function exeDelete($postId) {
        $this->$postId = (int)$postId;
        $trabalho = new Read;
        $trabalho->exeRead(self::Entity, "WHERE codigo = :id", "id={$this->$postId}");
        if(!$trabalho->getResult()):
            $this->error = ["O Trabalho não existe no Sistema", MSG_ERROR];
            $this->result = false;
        else:
            $trabalhoDel = $trabalho->getResult()[0];
            if(file_exists('../uploads/' . $trabalhoDel['foto']) && !is_dir('../uploads/' . $trabalhoDel['foto'])):
                unlink('../uploads/' . $trabalhoDel['foto']);
            endif;
            $deleta = new Delete;
            $deleta->exeDelete(self::Entity, "WHERE codigo = :idtrab", "idtrab={$this->$postId}");

            $this->error = ["O Trabalho {$trabalhoDel['titulo']} foi removido com sucesso!" , MSG_ACCEPT];
            $this->result = true;
        endif;
    }



    private function setData(){
        $photo = $this->data['foto'];
        $description = $this->data['descricao'];
        unset($this->data['foto'], $this->data['descricao']);
        $this->data = array_map('strip_tags', $this->data);
        $this->data = array_map('trim', $this->data);

        $this->data['nome'] = Check::name($this->data['titulo']);


        $this->data['datacad'] = Check::data($this->data['datacad']);
        $this->data['dataup'] = Check::data($this->data['dataup']);

        $this->data['foto'] = $photo;
        $this->data['descricao'] = $description;
        $this->data['categoriapai'] = $this->getCatParente();


        $this->data['tipo'] = $this->getTipo( $this->data['categoriafilho']);
        $this->data['tiponome'] = Check::name($this->data['tipo']);
    }


    private function getTipo($codigo){
        $read = new Read;
        $read->exeRead("categoria_post", "WHERE codigo = :codigo", "codigo={$codigo}");
        if($read->getResult()):
            return $read->getResult()[0]['titulo'];
        endif;

    }
    private function setName(){
        $where = (isset($this->postId) ? "codigo != {$this->postId} AND " : '');
        $readNome = new Read;
        $readNome->exeRead(self::Entity, "WHERE {$where} titulo = :t" , "t={$this->data['titulo']}");
        if($readNome->getResult()):
            $this->data['nome'] = $this->data['nome'] .'-' .$readNome->getRowCount();
        endif;
    }

    private function getCatParente(){
        $readCat = new Read;
        $readCat->exeRead("categoria_post", "WHERE codigo = :id", "id={$this->data['categoriafilho']}");
        if($readCat->getResult()):
            return $readCat->getResult()[0]['subcodigo'];
        else:
            return null;
        endif;
    }

    public function exeStatus($postId, $trabalhoStatus) {
        $this->$postId = (int)$postId;
        $this->data['status'] = (string)$trabalhoStatus;
        $update = new Update;
        $update->exeUpdate(self::Entity, $this->data, "WHERE codigo = :id", "id={$this->$postId}");
    }


    private function create(){
        $cad = new Create;
        $cad->exeCreate(self::Entity, $this->data);
        if($cad->getResult()):
            $this->result = $cad->getResult();
        endif;
    }

    private function update() {
        $update = new Update;
        $update->exeUpdate(self::Entity, $this->data, "WHERE codigo = :id", "id={$this->postId}");
        if($update->getResult()):
             $this->result = true;
             $this->error = ["Página salvada com sucesso!", MSG_ACCEPT];
        endif;

    }



} 