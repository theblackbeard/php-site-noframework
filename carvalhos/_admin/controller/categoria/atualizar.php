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
      <li class="active">Atualizar Categoria</li>
    </ol>
    <h2>Atualizar Categoria</h2>
    <p>Atualize aqui a categoria Cadastrada</p>
  </header>
  <?php
    $catid = filter_input(INPUT_GET, 'catid', FILTER_VALIDATE_INT);
    $categoriaUp = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if(isset($categoriaUp) && $categoriaUp['AtualizarUpdate']):
        $categoriaUp['foto'] = ($_FILES['foto']['tmp_name'] ? $_FILES['foto'] : 'null');
      unset($categoriaUp['AtualizarUpdate']);
      require ('models/AdminCatTrab.class.php');
      $update = new AdminCatTrab;
      $update->exeUpdate($catid, $categoriaUp);
        if(!$update->getResult()):
          MSG($update->getError()[0], $update->getError()[1]);
        else:
           header('location: painel.php?mt=usuario/index$updated=true');
          endif;
    else:
      $read = new Read;
      $read->exeRead("categoria" , "WHERE codigo = :id" , "id={$catid}");
      if(!$read->getResult()):
        header('Location: painel.php?mt=categoria/index&empty=true');
      else:
        $categoriaUp = $read->getResult()[0];
      endif;
    endif;
    
    $create = filter_input(INPUT_GET, 'create' , FILTER_VALIDATE_BOOLEAN);
    if($create && empty($create)):
      $tipo = (empty($categoriaUp['subcodigo']) ? 'Seção' : 'Categoria');
      MSG("A {$categoriaUp['ctrab_titulo']} {} foi cadastrada com sucesso!", MSG_ACCEPT); 
    endif;
    
   
  
  ?>
  <form action="#" name="AtualizarUpdateForm" method="post" enctype="multipart/form-data">
      <div class="form-group">
          <label for="foto">Foto de Capa</label>
          <input type="file" id="foto" name="foto">
      </div>
    <div class="form-group">
      <label for="titulo">Titulo</label>
      <input type="text" class="form-control" id="titulo" name="titulo" value="<?php if(isset($categoriaUp)) echo $categoriaUp['titulo']?>" placeholder="Insira um Titulo" required autofocus>
    </div>
    <div class="form-group">
      <label for="descricao">Descrição</label>
      <textarea class="form-control" id="descricao" name="descricao" rows="5"><?php if(isset($categoriaUp)) echo $categoriaUp['descricao']; ?>
</textarea>
    </div>

    <div class="form-inline">
     
      <label for="catsec">Categoria ou Seção</label>
      <select class="form-control" id="catsec" name="subcodigo">
        <option value="null">Seção</option>
        <?php
          $readSesao = new Read;
          $readSesao->exeRead("categoria", "WHERE subcodigo IS NULL ORDER BY titulo ASC");
          if(!$readSesao->getResult()):
            echo '<option disabled="disabled" value="null">Cadastre Antes uma Seção!</option>';
          else:
            foreach ($readSesao->getResult() as $sessao):
              echo "<option value=\"{$sessao['codigo']}\" ";
              if($sessao['codigo'] == $categoriaUp['subcodigo']) : echo ' selected="selected" ';
              endif;
              echo ">{$sessao['titulo']}</option>";
            endforeach;
          endif;
        ?>
      </select>
    </div>
    <br>

    <input type="submit" class="btn btn-info" name="AtualizarUpdate" value="Atualizar" />
  </form>

</section>