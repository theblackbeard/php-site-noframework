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
      <li class="active">Atualizar Pagina Contato</li>
    </ol>
    <h2>Atualizar Pagina Contato</h2>
    <p>Atualize aqui a pagina de contato</p>
  </header>
  <?php
    $contid = filter_input(INPUT_GET, 'contid', FILTER_VALIDATE_INT);
    $contato = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if(!empty($contato['EnviarContato'])):
        $contato['cont_foto'] = ($_FILES['cont_foto']['tmp_name'] ? $_FILES['cont_foto'] : 'null');
      unset($contato['EnviarContato']);
      require ('_models/AdminContato.class.php');
      $update = new AdminContato;
      $update->exeUpdate($contid, $contato);
        if(!$update->getResult()):
          MSG($update->getError()[0], $update->getError()[1]);
        else:
            header('Location: painel.php?mt=contato/index&updated=true');
        endif;
    else:
      $read = new Read;
      $read->exeRead("cweb_contato" , "WHERE cont_codigo = :id" , "id={$contid}");
      if(!$read->getResult()):
        header('Location: painel.php?mt=contato/index&empty=true');
      else:
          $contato = $read->getResult()[0];
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
            <input type="file" class="form-control" id="foto" name="cont_foto"  >
        </div>
        <br>

        <input type="submit" class="btn btn-default" name="EnviarContato" value="Atualizar" />
    </form>

</section>