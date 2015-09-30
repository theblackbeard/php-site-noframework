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
      <li class="active">Atualizar Trabalho</li>
    </ol>
    <h2>Atualizar Trabalho</h2>
    <p>Edite aqui infomações do seu trabalho</p>
  </header>
  <?php 
    $trabalhoid = filter_input(INPUT_GET, 'trabalhoid' , FILTER_VALIDATE_INT);
    $atualiza = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if(isset($atualiza) && $atualiza['EnviarTrabalho']):
      $atualiza['trab_status'] = ($atualiza['EnviarTrabalho'] ==  'Atualizar' ? '0' : '1');
      $atualiza['trab_foto'] = ($_FILES['trab_foto']['tmp_name'] ? $_FILES['trab_foto'] : 'null');
      unset($atualiza['EnviarTrabalho']);
      require('_models/AdminTrabalho.class.php');
      $cadastra = new AdminTrabalho;
      $cadastra->exeUpdate($trabalhoid, $atualiza);
      if($cadastra->getResult()):
        header('Location: painel.php?mt=trabalho/index&updated=true');
      else:
         MSG($cadastra->getError()[0], $cadastra->getError()[1]);
      endif;
     
      if(!empty($_FILES['trab_galeria']['tmp_name'])):
        $galeria =new AdminTrabalho;
        $galeria->sendGallery($_FILES['trab_galeria'], $trabalhoid);
      endif;
    
    else:
      $read = new Read;
      $read->exeRead("cweb_trabalho", "WHERE trab_codigo = :id", "id={$trabalhoid}");
      if(!$read->getResult()):
        header('Location: painel.php?mt=trabalho/index&empty=true');
      else:
        $atualiza =$read->getResult()[0]; 
        $atualiza['trab_data'] = date('d/m/Y H:i:s' , strtotime($atualiza['trab_data']));
      endif;
    endif;
    
    
     
     
    
  ?>
  <form action="#" method="post" enctype="multipart/form-data">
    <div class="form-group">
      <label for="exampleInputFile">Foto de Capa</label>
      <input type="file" id="exampleInputFile" name="trab_foto">
    </div>
    <div class="form-group">
      <label for="titulo">Titulo</label>
      <input type="text" class="form-control" id="titulo" name="trab_titulo" value="<?php if(isset($atualiza['trab_titulo'])) echo $atualiza['trab_titulo'];?>" placeholder="Insira um Titulo" required autofocus>
    </div>
    <div class="form-group">
      <label for="descricao">Descrição:</label>
      <textarea class="form-control" id="descricao" name="trab_descricao" rows="15" required>
      <?php
        if(isset($atualiza['trab_descricao'])) echo $atualiza['trab_descricao'];
      ?>
      </textarea>
    </div>

    <div class="form-inline">
      <label for="data">Data de Inclusão:</label>
      <input type="text" class="form-control" id="data" name="trab_data" value="<?php if(isset($atualiza['trab_data'])):  echo $atualiza['trab_data']; else: date('d/m/Y H:i:s'); endif; ?>" required autofocus>


      <label for="catsec">Categoria:</label>
      <select class="form-control" id="catsec" name="trab_categoria">
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
                echo "<option value=\"{$cat['ctrab_codigo']}\" ";
              if($cat['ctrab_codigo'] == $atualiza['trab_categoria']) : echo ' selected="selected" ';
              endif;
              echo ">{$cat['ctrab_titulo']}</option>";
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
              if($atualiza['trab_autor'] == $autor['usu_codigo']):
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
      <div class="form-group" id="galfoco">
      <label for="exampleInputFile">Galeria de Fotos:</label>
      <input type="file" id="exampleInputFile" multiple name="trab_galeria[]" />
    </div>
      <br>
      <input type="submit" class="btn btn-primary" name="EnviarTrabalho" value="Atualizar" />
      <input type="submit" class="btn btn-info" name="EnviarTrabalho" value="Atualizar e Publicar" />

  
   

  </form>

  <br>
  <hr>
    <div class="row">
      <?php
        $delmt = filter_input(INPUT_GET, 'mtdel' , FILTER_VALIDATE_INT);
        if($delmt):
          require_once ('_models/AdminTrabalho.class.php');
          $delGaleria= new AdminTrabalho;
          $delGaleria->removeGallery($delmt);
          MSG($delGaleria->getError()[0], $delGaleria->getError()[1]);
        endif;
      
        $i = 0;
        $galeria = new Read;
        $galeria->exeRead("cweb_galeria", "WHERE post_codigo = :id", "id={$trabalhoid}");
        if($galeria->getResult()):
          foreach ($galeria->getResult() as $mt):
          $i++;
      ?>
       <div class="col-xs-6 col-sm-3 col-md-2">
    <div class="thumbnail">
      <?= Check::image('../uploads/'. $mt['galeria_imagem'], $i, 171, 180);?>
      <div class="caption">
        <p><a href="painel.php?mt=trabalho/atualizar&trabalhoid=<?= $trabalhoid ?>&mtdel=<?= $mt['galeria_codigo']; ?>#galfoco" class="btn btn-danger glyphicon glyphicon-remove" role="button"></a></p>
      </div>
    </div>
  </div>
      <?php 
       endforeach;
       else:
       ?>
          <div class="col-xs-6 col-sm-3 col-md-2">
      <div class="thumbnail">
        <img src="img/semgaleria.jpg" />
        <div class="caption">
          <p>Nenhuma Imagem Cadastrada</p>
        </div>
      </div>
    </div>
      <?php
       endif;
      ?>
    </div>
  
</section>