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
      <li class="active">Nova Pagina Contato</li>
    </ol>
    <h2>Nova Pagina Contato</h2>
    <p>Coloque aqui o nome e uma descrição da pagina de contato</p>
  </header>
  <?php 
    $contato = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if(!empty($contato['EnviarContato'])):
        $contato['cont_foto'] = ($_FILES['cont_foto']['tmp_name'] ? $_FILES['cont_foto'] : null);
      unset($contato['EnviarContato']);
      require ('_models/AdminContato.class.php');
     $cadastra = new AdminContato;
      $cadastra->exeCreate($contato);

      if(!$cadastra->getResult()):
        MSG($cadastra->getError()[0], $cadastra->getError()[1]);
      else:
        header('Location: painel.php?mt=contato/index&created=true');
      endif;
      
    
    endif;


  ?>
  <form action="#" method="post" name="EnviarContatoForm" enctype="multipart/form-data">
    <div class="form-group">
      <label for="titulo">Titulo</label>
      <input type="text" class="form-control" id="titulo" name="cont_titulo" value="<?php if(isset($contato['cont_titulo'])) echo $contato['cont_titulo']?>" placeholder="Insira um Titulo" required autofocus>
    </div>
    <div class="form-group">
      <label for="descricao">Descrição</label>
      <textarea class="form-control" id="descricao" name="cont_descricao" rows="5" required>
      <?php if(isset($contato['cont_descricao'])) echo $contato['cont_descricao']?>
      </textarea>
    </div>

    <div class="form-inline">
      <label for="foto">Foto de Capa</label>
      <input type="file" class="form-control" id="foto" name="cont_foto" required >
    </div>
    <br>

    <input type="submit" class="btn btn-default" name="EnviarContato" value="Cadastrar" />
  </form>

</section>