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
      $atualiza['status'] = ($atualiza['EnviarTrabalho'] ==  'Atualizar' ? '0' : '1');
      $atualiza['foto'] = ($_FILES['foto']['tmp_name'] ? $_FILES['foto'] : 'null');
      unset($atualiza['EnviarTrabalho']);
      require('models/AdminTrabalho.class.php');
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
      $read->exeRead("trabalho", "WHERE codigo = :id", "id={$trabalhoid}");
      if(!$read->getResult()):
        header('Location: painel.php?mt=trabalho/index&empty=true');
      else:
        $atualiza =$read->getResult()[0]; 
        $atualiza['data'] = date('d/m/Y H:i:s' , strtotime($atualiza['data']));
      endif;
    endif;
    
    
     
     
    
  ?>
  <form action="#" method="post" enctype="multipart/form-data">
    <div class="form-group">
      <label for="exampleInputFile">Foto de Capa</label>
      <input type="file" id="exampleInputFile" name="foto">
    </div>
    <div class="form-group">
      <label for="titulo">Titulo</label>
      <input type="text" class="form-control" id="titulo" name="titulo" value="<?php if(isset($atualiza['titulo'])) echo $atualiza['titulo'];?>" placeholder="Insira um Titulo" required autofocus>
    </div>
    <div class="form-group">
      <label for="descricao">Descrição:</label>
      <textarea class="form-control" id="descricao" name="descricao" rows="15"><?php if(isset($atualiza['descricao'])) echo $atualiza['descricao'];?></textarea>
    </div>

    <div class="form-group">
      <label for="data">Data de Inclusão:</label>
      <input type="text" class="form-control" id="data" name="data" value="<?php if(isset($atualiza['data'])):  echo $atualiza['data']; else: date('d/m/Y H:i:s'); endif; ?>" required autofocus>


      <label for="catsec">Categoria:</label>
      <select class="form-control" id="catsec" name="categoriafilho">
          <option value="null">Selecione uma Categoria</option>
       <?php
        $readSes = new Read;
        $readSes->exeRead("categoria", "WHERE subcodigo IS NULL ORDER BY titulo ASC");
        if($readSes->getRowCount() >= 1):
          foreach ($readSes->getResult() as $ses):
            echo "<option disabled=\"disable\" value=\"\"><b> {$ses['titulo']}</b></option>";
            $readCat = new Read;
            $readCat->exeRead("categoria", "WHERE subcodigo = :parente ORDER BY titulo ASC", "parente={$ses['codigo']}");
            if($readCat->getRowCount() >= 1):
              foreach ($readCat->getResult() as $cat):
                echo "<option value=\"{$cat['codigo']}\" ";
              if($cat['codigo'] == $atualiza['categoriafilho']) : echo ' selected="selected" ';
              endif;
              echo ">{$cat['titulo']}</option>";
              endforeach;
            endif;
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
      <input type="submit" class="btn btn-primary" name="EnviarTrabalho" value="Atualizar" />
      <input type="submit" class="btn btn-info" name="EnviarTrabalho" value="Atualizar e Publicar" />

  
   

  </form>

  <br>
  <hr>
    <div class="row">
      <?php
        $delmt = filter_input(INPUT_GET, 'mtdel' , FILTER_VALIDATE_INT);
        if($delmt):
          require_once ('models/AdminTrabalho.class.php');
          $delGaleria= new AdminTrabalho;
          $delGaleria->removeGallery($delmt);
          MSG($delGaleria->getError()[0], $delGaleria->getError()[1]);
        endif;
      
        $i = 0;
        $galeria = new Read;
        $galeria->exeRead("galeria", "WHERE trabalho = :id", "id={$trabalhoid}");
        if($galeria->getResult()):
          foreach ($galeria->getResult() as $mt):
          $i++;
      ?>
       <div class="col-xs-6 col-sm-3 col-md-2" id="galfoco">
    <div class="thumbnail">
      <?= Check::image('../uploads/'. $mt['imagem'], $i, 171, 180);?>
      <div class="caption">
        <p><a href="painel.php?mt=trabalho/atualizar&trabalhoid=<?= $trabalhoid ?>&mtdel=<?= $mt['codigo']; ?>#galfoco" class="btn btn-danger glyphicon glyphicon-remove" role="button"></a></p>
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