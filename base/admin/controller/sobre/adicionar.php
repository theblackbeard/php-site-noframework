<?php
  if(!class_exists('Login')):
    header('Location:  ../../painel.php?mt=home');
    die;
  endif;
  $login = new Login(3);

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
      $sobre['sobre_data'] = Check::data($sobre['sobre_data']);
      require('_models/AdminSobre.class.php');
      $sobreCreate = new AdminSobre;
      $sobreCreate->exeCreate($sobre);
      if($sobreCreate->getResult()):
        header('Location: painel.php?mt=sobre/atualizar&create=true&sobreid='. $sobreCreate->getResult());
      else:
        header('Location: painel.php?mt=sobre/index&created=true');
      endif;
   
    endif;
  ?>
  <form action="#" method="post" name="EnviarSobreForm">
  <div class="form-group">
    <label for="titulo">Titulo</label>
    <input type="text" class="form-control" id="titulo" name="sobre_titulo" placeholder="Insira um Titulo" value="<?php if(isset($sobre)) echo $sobre['sobre_titulo']; ?>" required autofocus />
  </div>
  <div class="form-group">
    <label for="descricao">Descrição</label>
    <textarea class="form-control" id="descricao" name="sobre_descricao" rows="15" required ><?php if(isset($sobre)) echo $sobre['sobre_descricao']; ?> </textarea>
  </div>
  <div class="form-group">
    <input class="form-control" id="disabledInput" name="sobre_data" type="text" value="<?php echo date('d/m/Y H:i:s'); ?>">
  </div>
  <input type="submit" class="btn btn-default" name="EnviarSobre" value="Cadastrar" />
</form>
  
</section>