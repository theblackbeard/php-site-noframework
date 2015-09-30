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
      <li class="active">Novo Usuario</li>
    </ol>
    <h2>Novo Usuario</h2>
	  </header>
	<?php
      $create = filter_input_array(INPUT_POST, FILTER_DEFAULT);
      if(!empty($create['EnviarUsuario'])):
        unset($create['EnviarUsuario']);
        require ('models/AdminUsuario.class.php');
       $save = new AdminUsuario;
       $save->exeCreate($create);
       if(!$save->getResult()):
         MSG($save->getError()[0], $save->getError()[1]);
       else:
         header('location: painel.php?mt=usuario/index&created=true');
      endif; 
  endif;       
   
  ?>
	<form action="#" method="post" name="EnviarUsuarioForm" enctype="multipart/form-data">
    
    <div class="form-group">
      <label for="nome">Nome</label>
      <input type="text" class="form-control" id="nome" name="nome" placeholder="Insira o Nome" required autofocus>
    </div>
    <div class="form-group">
      <label for="sobrenome">Sobrenome</label>
      <input type="text" class="form-control" id="sobrenome" name="sobrenome" placeholder="Insira o Sobrenome" required autofocus>
    </div>
      <div class="form-group">
      <label for="email">E-mail Usuario</label>
      <input type="email" class="form-control" id="email" name="email" placeholder="Insira o E-Mail" required autofocus>
    </div>
	<div class="form-group">
      <label for="senha">Senha</label>
      <input type="password" class="form-control" id="senha" name="senha"  placeholder="Senha" required autofocus>
    </div>
	
	<div class="form-group">
		<select class="form-control" name="nivel">
  			<option value="1">Administrador</option>
  			<option value="2" selected>Leitor</option>
		</select>	
	</div>
  
   
    <br>

    <input type="submit" class="btn btn-primary" name="EnviarUsuario" value="Cadastrar" />
  </form>
</section>