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
      <li class="active">Novo Trabalho</li>
    </ol>
    <h2>Novo Trabalho</h2>
    <p>Coloque aqui infomações do seu novo trabalho</p>
  </header>
  <?php
    $trabalho = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if(isset($trabalho) && $trabalho['EnviarTrabalho']):
      $trabalho['trab_status'] = ($trabalho['EnviarTrabalho'] == 'Cadastrar' ? '0' : '1');
      $trabalho['trab_foto'] = ($_FILES['trab_foto']['tmp_name'] ? $_FILES['trab_foto'] : null);
      unset($trabalho['EnviarTrabalho']);
      require ('_models/AdminTrabalho.class.php');
      $cadastra = new AdminTrabalho;
      $cadastra->exeCreate($trabalho);
     // var_dump($cadastra);

      if($cadastra->getResult()):
        if(!empty($_FILES['trab_galeria']['tmp_name'])):
            $galeria = new AdminTrabalho;
            $galeria->sendGallery($_FILES['trab_galeria'], $cadastra->getResult());
         endif;
         header('Location: painel.php?mt=trabalho/index&created=true');
        else:
          MSG($cadastra->getError()[0], $cadastra->getError()[1]);
      endif;
 
    endif;
   
     
  ?>
  <form action="#" method="post" name="EnviarTrabalhoForm" enctype="multipart/form-data">
    <div class="form-group">
      <label for="exampleInputFile">Foto de Capa</label>
      <input type="file" id="exampleInputFile" name="trab_foto">
    </div>
    <div class="form-group">
      <label for="titulo">Titulo</label>
      <input type="text" class="form-control" id="titulo" name="trab_titulo" value="<?php if (isset($post['trab_titulo'])) echo $post['trab_titulo'];?>" placeholder="Insira um Titulo" required autofocus>
    </div>
    <div class="form-group">
      <label for="trab_descricao">Descrição:</label>
      <textarea class="form-control" id="trab_descricao" name="trab_descricao"><?php if (isset($post['trab_descricao'])) echo $post['trab_descricao']; ?></textarea>
        <span id="resta"> </span> Caracteres Restantes<br>
        
    </div>

    <div class="form-inline">
      <label for="data">Data de Inclusão:</label>
      <input type="text" class="form-control" id="data" name="trab_data" value="<?php if (isset($post['trab_data'])): echo $post['trab_data'];
else: echo date('d/m/Y H:i:s');
endif; ?>" required autofocus>

     
      <label for="catsec">Categoria:</label>
      <select class="form-control" id="catsec" name="trab_categoria" required>
       <option value="null">Selecione uma Categoria</option>
       <?php
        $readSes = new Read;
        $readSes->exeRead("cweb_cattrab", "WHERE ctrab_parente IS NULL ORDER BY ctrab_titulo ASC");
        if($readSes->getRowCount() >= 1):
          foreach ($readSes->getResult() as $ses):
            echo "<option disabled=\"disable\" value=\"\"><b> {$ses['ctrab_titulo']}</b></option>";
            $readCat = new Read;
            $readCat->exeRead("cweb_cattrab", "WHERE ctrab_parente = :parente ORDER BY ctrab_titulo ASC", "parente={$ses['ctrab_codigo']}");
            if($readCat->getRowCount() >= 1):
              foreach ($readCat->getResult() as $cat):
                echo " <option ";
                if($trabalho['trab_categoria'] == $cat['ctrab_titulo']):
                  echo "selected=\"selected\" ";
                endif;
                echo "value=\"{$cat['ctrab_codigo']}\"> {$cat['ctrab_titulo']}</option>";
              endforeach;
            endif;
          endforeach;
        endif;
       
       ?>
      </select>

      <label for="catsec">Autor:</label>
      <select class="form-control" id="catsec" name="trab_autor">
        <option value="<?= "{$_SESSION['userlogin']['usu_codigo']}" ?>">
          <?= "{$_SESSION['userlogin']['usu_nome']} {$_SESSION['userlogin']['usu_sobrenome']}" ?>
        </option>
        <?php
           $readAutor = new Read;
           $readAutor->exeRead("cweb_usuarios" , "WHERE usu_codigo != :id AND usu_nivel >= :nivel ORDER BY usu_nome ASC", "id={$_SESSION['userlogin']['usu_codigo']}&nivel=2");
           if($readAutor->getRowCount() >= 1):
             foreach ($readAutor->getResult() as $autor):
              echo "<option ";
              if($trabalho['trab_autor'] == $autor['usu_codigo']):
                echo " selected =\"selected\" ";
              endif;
              echo "value=\"{$autor['usu_codigo']}\">{$autor['usu_nome']} {$autor['usu_sobrenome']}</option>";
             endforeach;
           endif;
        ?>
      </select>
    </div>
    <br>

    
      <hr>
      
      
       <div class="form-group">
      <label for="exampleInputFile">Galeria de Fotos:</label>
      <input type="file" id="exampleInputFile" multiple name="trab_galeria[]" />
    </div>
      <br>
      <input type="submit" class="btn btn-primary" name="EnviarTrabalho" value="Cadastrar" />
      <input type="submit" class="btn btn-info" name="EnviarTrabalho" value="Cadastrar e Publicar" />
      
  </form>

</section>