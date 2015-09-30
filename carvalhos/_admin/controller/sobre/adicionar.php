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
      <li class="active">Novo Sobre</li>
    </ol>
    <h2>Novo Sobre</h2>
  <p>Coloque aqui uma descrição da sua pagina sobre, apenas um envio, caso já tenha enviando apague ou edite o envio existente nesse <a href="painel.php?mt=sobre/index">Link</a></p>
  </header>
  <?php 
    $sobre = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if(!empty($sobre['EnviarSobre'])):
      unset($sobre['EnviarSobre']);
      
      require('models/AdminSobre.class.php');
      $sobreCreate = new AdminSobre;
      $sobreCreate->exeCreate($sobre);
       if($sobreCreate->getResult()):
        header('Location: painel.php?mt=sobre/index&create=true');
      else:
        header('Location: painel.php?mt=sobre/index&errorCreated=true');
      endif;
   
    endif;
  ?>
  <form action="#" method="post" name="EnviarSobreForm">
  <div class="form-group">
    <label for="titulo">Titulo</label>
    <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Insira um Titulo" value="<?php if(isset($sobre)) echo $sobre['titulo']; ?>" required autofocus />
  </div>
  <div class="form-group">
    <label for="descricao">Descrição</label>
    <textarea class="form-control" id="descricao" name="descricao" rows="15" placeholder="Insira um Texto"  ><?php if(isset($sobre)) echo $sobre['descricao']; ?> </textarea>
  </div>
  
  <input type="submit" class="btn btn-primary" name="EnviarSobre" value="Cadastrar" />
</form>
  <div class="branco"></div>
</section>