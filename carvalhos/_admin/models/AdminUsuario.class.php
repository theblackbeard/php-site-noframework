<?php 
	class AdminUsuario{
		private $data;
		private $usuarioId;
		private $result;
		private $error;
		const Entity = "usuario";
		
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
			elseif(!Check::mail($this->data['email'])):
				$this->result = false;
				$this->error = ['E-mail Invalido' , MSG_INFO];
		    elseif(!$this->getEmail()):
				$this->result = false;
				$this->error = ['E-mail já existente' , MSG_ERROR];		
			else:
				$this->setData();
				$this->create();
			endif;
		}
		
		public function exeUpdate($usuarioId, array $data)
		{
			$this->usuarioId = (int)$usuarioId;
			$this->data  = $data;
			if(in_array('' , $this->data)):
				$this->result = false;
				$this->error = ['Sem Espaços em Brancos' , MSG_INFO];	
			elseif(!Check::mail($this->data['email'])):
				$this->result = false;
				$this->error = ['E-mail Invalido' , MSG_INFO];
		    elseif(!$this->checkPass()):
				$this->result = false;
				$this->error = ['A Senha Atual não Confere, atualização abortada.' , MSG_ERROR];	
			elseif($this->data['senha'] == 'null'):
				$this->setData();
				unset($this->data['senha_atual'], $this->data['senha']);	
				$this->update();
				//var_dump($this->data);
			else:
				$this->setData();
				unset($this->data['senha_atual']);
				$this->update();
			endif;
		}
		
		public function exeDelete($usuarioId)
		{
			$this->usuarioId = (int)$usuarioId;
			$read = new Read;
			$read->exeRead(self::Entity, "WHERE codigo = :id", "id={$this->usuarioId}");
			if(!$read->getResult()):
				$this->result = false;
				$this->error = ['Exclusão de página inexistente', MSG_ERROR];
			else:
				if($read->getResult()[0]['nivel'] == "1"):
					$this->result = false;
					$this->error = ['Adiministrador não pode ser excluido', MSG_ERROR];
				else:
					$delete = new Delete;
					$delete->exeDelete(self::Entity, "WHERE codigo = :did", "did={$this->usuarioId}");
					$this->result = true;
					$this->error = ["A pagina Sobre foi excluida com sucesso!" , MSG_ACCEPT];	
		
				endif;
			endif;
		}	
		
		private function setData()
		{
			$this->data['senha'] = md5($this->data['senha']);
		}
		
		
		
		private function getEmail()
		{
			$readEmail = new Read;
			$readEmail->exeRead(self::Entity, "WHERE email = :email", "email={$this->data['email']}");
			if($readEmail->getResult()):
				return false;
			else:
				return true;
			endif;
		}
		
		private function checkPass()
		{
			$readEmail = new Read;
			$readEmail->exeRead(self::Entity, "WHERE senha = :senha", "senha={$this->data['senha_atual']}");
			if($readEmail->getResult()):
				return true;
			else:
				return false;
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
     		$update->exeUpdate(self::Entity, $this->data, "WHERE codigo = :id", "id={$this->usuarioId}");
     		if($update->getResult()):
       			$this->result = true;
       		endif;
		}
		
		
	}