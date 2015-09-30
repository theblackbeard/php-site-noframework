<?php 
	class AdminSobre{
		private $data;
		private $sobreId;
		private $result;
		private $error;
		
		const Entity = "sobre";
		
		public function getResult() {
			return $this->result;
		}
		
		public function getError() {
			return $this->error;
		}
		
		
		
		public function exeCreate(array $data)
		{
			$this->data  = $data;
			if(in_array('' , $this->data)):
				$this->result = false;
				$this->error = ['Sem Espaços em Brancos' , MSG_INFO];	
			else:
				$limitPost = new Read;
				$limitPost->exeRead(self::Entity);
					if($limitPost->getRowCount() <= 0):
						$this->setData();
						$this->setName();
						$this->create();
					else:
						$this->result = false;
					endif;
					
			endif;	
		}
		
		public function exeUpdate($sobreId, array $data)
		{
			$this->sobreId = (int)$sobreId;
			$this->data = $data;
			if(in_array('', $this->data)):
				$this->result = false;
				$this->error = ['Sem Espaços em Brancos' , MSG_INFO];
			else:
				$this->setData();
				$this->setName();
				$this->update();	
			endif;
		}
		
		
		public function exeDelete($sobreId)
		{
			$this->sobreId = (int)$sobreId;
			$read = new Read;
			$read->exeRead(self::Entity, "WHERE codigo = :id", "id={$this->sobreId}");
			if(!$read->getResult()):
				$this->result = false;
				$this->error = ['Exclusão de página inexistente', MSG_ERROR];
			else:
				$delete = new Delete;
				$delete->exeDelete(self::Entity, "WHERE codigo = :did", "did={$this->sobreId}");
				$this->result = true;
				$this->error = ["A pagina Sobre foi excluida com sucesso!" , MSG_ACCEPT];	
			endif;
		}
		
		private function setData()
		{
			//Retirando a descricao para não passar no strip_tags e trim
			$descricao = $this->data['descricao'];
			unset($this->data['descricao']);
			$this->data = array_map('strip_tags', $this->data);
			$this->data = array_map('trim', $this->data);
			$this->data['nome'] = Check::name($this->data['titulo']);
			//Instancias novamente o indece guardado na variavel
			$this->data['descricao'] = $descricao;
		}
	
		
		private function setName()
		{
			$where = (!empty($this->sobreId) ? "codigo != {$this->sobreId} AND " : '');
			$readName = new Read;
			$readName->exeRead(self::Entity, "WHERE {$where} titulo = :t", "t={$this->data['titulo']}");
			if($readName->getResult()):
				$this->data['nome'] = $this->data['nome'] . '-' . $readNome->getRowCount();
			endif;
		}
		
		
		private function create()
		{
			$create = new Create;
			$create->exeCreate(self::Entity, $this->data);
			if($create->getResult()):
				$this->result = true;
			endif;
			
		}
		
		private function update()
		{
	 		$update = new Update;
     		$update->exeUpdate(self::Entity, $this->data, "WHERE codigo = :id", "id={$this->sobreId}");
     		if($update->getResult()):
       			$this->result = true;
       		endif;
		}
		
		
	}
	
?>
