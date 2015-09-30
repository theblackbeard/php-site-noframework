<?php
class AdminCatTrab {
	private $data;
	private $catId;
	private $result;
	private $error;
	
	const Entity = 'categoria';
	
	public function getResult() {
		return $this->result;
	}
	
	public function getError() {
		return $this->error;
	}
	
	
	public function exeCreate(array $data) 
	{
		$this->data = $data;
		if (in_array('', $this->data)):
			$this->result = false;
			$this->error = ['Sem campos em branco', MSG_ALERT];
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
	
	
	
	public function exeUpdate($catid, array $data) 
	{
		$this->catId = (int)$catid;
		$this->data = $data;
		if(in_array('', $this->data)):
			$this->result = false;
			$this->error = ['Revise os campos em branco' , MSG_ALERT];
		else:
			$this->setData();
			$this->setName();
            if(is_array($this->data['foto'])):
                $readCapa = new Read;
                $readCapa->exeRead(self::Entity, "WHERE codigo = :trabid", "trabid={$this->$catid}");
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
	
	public function exeDelete($catid) {
		$this->catId = (int)$catid;
		$read = new Read;
		$read->exeRead(self::Entity, "WHERE codigo = :id", "id={$this->catId}");
		if(!$read->getResult()):
			$this->result = false;
			$this->error = ['Você tentou excluir uma categoria inexistente', MSG_ALERT];
			else:
				extract($read->getResult()[0]);
			if(!$subcodigo && !$this->checkCategoria()):
				$this->result = false;
				$this->error = ["A seção <b>{$titulo}</b> possui categorias cadastradas, Remova ou altere sua sub-categorias", MSG_INFO];
			elseif($subcodigo && !$this->checkTrabalhos()):
				$this->result = false;
				$this->error = ["A Categoria <b>{$titulo}</b> possui postagens existentes, Remova ou Altere todos as postagens da categoria", MSG_ALERT];
			else:
				$delete = new Delete;
				$delete->exeDelete(self::Entity, "WHERE codigo= :delcat", "delcat={$this->catId}");
				$tipo = (empty($subcodigo) ? "Seção" : "Categoria");
				$this->result = true;
				$this->error = ["A <b>{$tipo}</b> foi excluida com sucesso" , MSG_ACCEPT];
			endif;
		endif;
	}
	
	private function checkCategoria() {
		$readSes = new Read;
		$readSes->exeRead(self::Entity, "WHERE subcodigo = :parente", "parente={$this->catId}");
		if($readSes->getResult()):
		return false;
		else:
		return true;
		endif;
	}
	
	private function checkTrabalhos() {
		$readTrab = new Read;
		$readTrab->exeRead("trabalho", "WHERE categoriafilho = :categoria", "categoria={$this->catId}");
		if($readTrab->getResult()):
		return false;
		else:
		return true;
		endif;
	}
	
	
	
	private function setData() 
	{
        $foto = $this->data['foto'];
		$description = $this->data['descricao'];
		unset($this->data['foto'], $this->data['descricao']);
		$this->data = array_map('strip_tags', $this->data);
		$this->data = array_map('trim', $this->data);
		$this->data['nome'] = Check::name($this->data['titulo']);
		$this->data['subcodigo'] = ($this->data['subcodigo'] == 'null' ? null : $this->data['subcodigo']);
        $this->data['foto'] = $foto;
        $this->data['descricao'] = $description;
		
	}
	
	private function setName() 
	{
		$where = (!empty($this->catId) ? "codigo != {$this->catId} AND " : '');
		$readName =  new Read;
		$readName->exeRead(self::Entity, "WHERE {$where} titulo = :t", "t={$this->data['titulo']}");
		if($readName->getResult()):
		$this->data['nome'] = $this->data['nome'] . '-' . $readName->getRowCount();
		endif;
	}
	
	
	private function create()
	 {
		$create = new Create;
		$create->exeCreate(self::Entity, $this->data);
		if($create->getResult()):
		$tipo  =(empty($this->data['subcodigo']) ? 'Seção' : 'Categoria');
		$this->result = $create->getResult();
		$this->error = ["A <b>{$tipo}</b> foi cadastrada com sucesso!, clique <a href=\"painel.php?mt=categoria/index\">Aqui</a> para listar sua categoria" , MSG_ACCEPT];
		endif;
	}
	
	private function update()
	 {
		$update = new Update;
		$update->exeUpdate(self::Entity, $this->data, "WHERE codigo = :id", "id={$this->catId}");
		if($update->getResult()):
		$tipo = (empty($this->data['subcodigo']) ? 'Seção' : 'Categoria');
		$this->result = true;
		$this->error = ["<b>Sucesso</b> A {$tipo} {$this->data['titulo']} foi atualizada com sucesso!", MSG_ACCEPT];
		endif;
	}
	
}
