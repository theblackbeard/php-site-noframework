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
    if(!empty($categoriaUp['AtualizarUpdate'])):
      unset($categoriaUp['AtualizarUpdate']);
      require ('_models/AdminCatTrab.class.php');
      $update = new AdminCatTrab;
      $update->exeUpdate($catid, $categoriaUp);
        if(!$update->getResult()):
          MSG($update->getError()[0], $update->getError()[1]);
        else:
        	header('Location: painel.php?mt=categoria/index&updated=true');
        endif;
    else:
      $read = new Read;
      $read->exeRead("cweb_cattrab" , "WHERE ctrab_codigo = :id" , "id={$catid}");
      if(!$read->getResult()):
        header('Location: painel.php?mt=categoria/index&empty=true');
      else:
        $categoriaUp = $read->getResult()[0];
      endif;
    endif;
    
    /*
    $create = filter_input(INPUT_GET, 'create' , FILTER_VALIDATE_BOOLEAN);
    if($create && empty($create)):
      $tipo = (empty($categoriaUp['ctrab_parente']) ? 'Seção' : 'Categoria');
      MSG("A {$categoriaUp['ctrab_titulo']} {} foi cadastrada com sucesso!", MSG_ACCEPT); 
    endif;
    */
   
  
  ?>
  <form action="#" name="AtualizarUpdateForm" method="post">
    <div class="form-group">
      <label for="titulo">Titulo</label>
      <input type="text" class="form-control" id="titulo" name="ctrab_titulo" value="<?php if(isset($categoriaUp)) echo $categoriaUp['ctrab_titulo']?>" placeholder="Insira um Titulo" required autofocus>
    </div>
    <div class="form-group">
      <label for="descricao">Descrição</label>
      <textarea class="form-control" id="descricao" name="ctrab_descricao" rows="5" required> 
      <?php if(isset($categoriaUp)) echo $categoriaUp['ctrab_descricao']; ?>
      </textarea>
    </div>

    <div class="form-inline">
      <label for="data">Data de Inclusão</label>
      <input type="text" class="form-control" id="data" name="ctrab_data" value="<?= date('d/m/Y H:i:s'); ?>" placeholder="Insira um Titulo" required autofocus>

      <label for="catsec">Categoria ou Seção</label>
      <select class="form-control" id="catsec" name="ctrab_parente">
        <option value="null">Seção</option>
        <?php
          $readSesao = new Read;
          $readSesao->exeRead("cweb_cattrab", "WHERE ctrab_parente IS NULL ORDER BY ctrab_titulo ASC");
          if(!$readSesao->getResult()):
            echo '<option disabled="disabled" value="null">Cadastre Antes uma Seção!</option>';
          else:
            foreach ($readSesao->getResult() as $sessao):
              echo "<option value=\"{$sessao['ctrab_codigo']}\" ";
              if($sessao['ctrab_codigo'] == $categoriaUp['ctrab_parente']) : echo ' selected="selected" ';
              endif;
              echo ">{$sessao['ctrab_titulo']}</option>";
            endforeach;
          endif;
        ?>
      </select>
    </div>
    <br>

    <input type="submit" class="btn btn-default" name="AtualizarUpdate" value="Atualizar" />
  </form>

</section>