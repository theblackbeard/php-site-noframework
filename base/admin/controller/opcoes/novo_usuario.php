<?php
if(!class_exists('Login')):
        header('Location: ../../painel.php?mt=home');
        die;
    endif;  
 ?>
<section>
  <header>
    <ol class="breadcrumb">
      <li><a href="painel.php?mt=home">Dashboard</a></li>
      <li class="active">Cadastrar Usuario</li>
    </ol>
    <h2>Cadastrar Usuario</h2>
    <p>Cadastrar um novo usuario no sistema</p>
  </header>
  <?php
    $usuario = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if(isset($usuario) && $usuario['EnviarUsuario']):
      unset($usuario['EnviarUsuario']);
      require ('_models/AdminUsuario.class.php');
      $cadastraUsuario = new AdminUsuario;
      $cadastraUsuario->exeCreate($usuario);
      if($cadastraUsuario->getResult()):
        header('Location: painel.php?mt=opcoes/lista_usuario&created=true');
      else:
        MSG($cadastraUsuario->getError()[0], $cadastraUsuario->getError()[1]);
      endif;
    endif;
   
  ?>
  <form name="EnviarUsuarioForm" action="#" method="post"> 
  <div class="form-group">
    <label for="nome">Nome:</label>
    <input type="text" class="form-control" id="nome" name="usu_nome" value="<?php if(isset($usuario['usu_nome'])) echo $usuario['usu_nome']; ?>" placeholder="Coloque seu nome" required>
  </div>
  <div class="form-group">
    <label for="sobrenome">Sobrenome:</label>
    <input type="text" class="form-control" id="sobrenome" name="usu_sobrenome" value="<?php if(isset($usuario['usu_sobrenome'])) echo $usuario['usu_sobrenome']; ?>" placeholder="Coloque seu sobrenome" required>
  </div>
  
  <div class="form-group">
    <label for="email">E-mail:</label>
    <input type="email" class="form-control" id="email" name="usu_email" value="<?php if(isset($usuario['usu_email'])) echo $usuario['usu_email']; ?>" placeholder="Coloque seu nome" required>
  </div>
    
  <div class="form-group">
    <label for="senha">Senha:</label>
    <input type="password" class="form-control" id="senha" name="usu_senha" placeholder="Coloque sua senha" required>
  </div>
    
   <div class="form-group">
    <label for="nivel">Nivel:</label> 
   <select class="form-control" name="usu_nivel" id="nivel">
      <option value="2">Usuario</option>
      <option value="3">Administrador</option>
     
</select>
      </div>
     
    <input type="submit" class="btn btn-primary" value="Cadastrar" name="EnviarUsuario" />
</form>
</section>