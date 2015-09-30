<?php

if (!class_exists('Login')):
    header('Location: ../../painel.php');
    die;
endif;

?>
<section>
	 <header>
    <ol class="breadcrumb">
      <li><a href="painel.php?mt=home">Dashboard</a></li>
      <li class="active">Atualizar Usuario</li>
    </ol>
    <h2>Atualizar Usuario</h2>
	  </header>
	<?php
      $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
      $create = filter_input_array(INPUT_POST, FILTER_DEFAULT);
      if(!empty($create['EnviarUsuario'])):
         $create['senha_atual'] = md5($create['senha_atual']);
         $create['senha'] = ($create['senha'] ? $create['senha'] : 'null');
         unset($create['EnviarUsuario']);
         require ('models/AdminUsuario.class.php');
         $save = new AdminUsuario;
        
          $save->exeUpdate($id, $create);
         if(!$save->getResult()):
           MSG($save->getError()[0], $save->getError()[1]);
         else:
           header('location: painel.php?mt=usuario/index&updated=true');
         endif; 
       
     else:
       $read = new Read;
       $read->exeRead("usuario", "WHERE codigo = :id", "id={$id}");
       if(!$read->getResult()):
        header('Location: painel.php?mt=usuario/index&empty=true');
      else:
        $create = $read->getResult()[0];
       endif;
  endif;       
   
  ?>
	<form action="#" method="post" name="EnviarUsuarioForm" enctype="multipart/form-data">
    
    <div class="form-group">
      <label for="nome">Nome</label>
      <input type="text" class="form-control" id="nome" name="nome" value="<?php if(isset($create)) echo $create['nome']; ?>" placeholder="Insira o Nome" required autofocus>
    </div>
    <div class="form-group">
      <label for="sobrenome">Sobrenome</label>
      <input type="text" class="form-control" id="sobrenome" name="sobrenome" value="<?php if(isset($create)) echo $create['sobrenome']; ?>" placeholder="Insira o Sobrenome" required autofocus>
    </div>
      <div class="form-group">
      <label for="email">E-mail Usuario</label>
      <input type="email" class="form-control" id="email" name="email" value="<?php if(isset($create)) echo $create['email']; ?>" placeholder="Insira o E-Mail" required autofocus>
    </div>
	<div class="form-group">
      <label for="senha">Senha Atual</label>
      <input type="password" class="form-control" id="senha" name="senha_atual"  required autofocus>
    </div>
		<div class="form-group">
      <label for="senha">Senha Nova</label>
      <input type="password" class="form-control" id="senha" name="senha" >
    </div>
	
	<div class="form-group">
		<select class="form-control" name="nivel">
        <?php
            $codigo = $create['nivel'];
            
        ?>
  		  <option value="1" <?php if($codigo == 1): echo 'selected'; endif;?>>Administrador</option>
  			<option value="2" <?php if($codigo == 2): echo 'selected'; endif;?>>Leitor</option>
		</select>	
	</div>
  
   
    <br>

    <input type="submit" class="btn btn-primary" name="EnviarUsuario" value="Atualizar" />
  </form>
</section>