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
      $trabalho['status'] = ($trabalho['EnviarTrabalho'] == 'Cadastrar' ? '0' : '1');
      $trabalho['foto'] = ($_FILES['foto']['tmp_name'] ? $_FILES['foto'] : null);
      unset($trabalho['EnviarTrabalho']);
      require ('models/AdminTrabalho.class.php');
      $cadastra = new AdminTrabalho;
      $cadastra->exeCreate($trabalho);
      //var_dump($cadastra);

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
      <input type="file" id="exampleInputFile" name="foto">
    </div>
    <div class="form-group">
      <label for="titulo">Titulo</label>
      <input type="text" class="form-control" id="titulo" name="titulo" value="<?php if(isset($trabalho['titulo'])) echo $trabalho['titulo']?>" placeholder="Insira um Titulo" required autofocus>
    </div>
    <div class="form-group">
      <label for="descricao">Descrição:</label>
      <textarea class="form-control" id="descricao" name="descricao" rows="15"><?php if(isset($trabalho['descricao'])) echo $trabalho['descricao']?></textarea>
    </div>

    <div class="form-inline">
      <label for="data">Data de Inclusão:</label>
      <input type="text" class="form-control" id="data" name="data" value="<?php if (isset($trabalho['data'])): echo $trabalho['data'];
else: echo date('d/m/Y H:i:s');
endif; ?>" required autofocus>

     
      <label for="catsec">Categoria:</label>
      <select class="form-control" id="catsec" name="categoriafilho">
       <option value="null">Selecione uma Categoria</option>
       <?php
        $readSes = new Read;
        $readSes->exeRead("categoria", "WHERE subcodigo IS NULL ORDER BY titulo ASC");
        if($readSes->getRowCount() >= 1):
          foreach ($readSes->getResult() as $ses):
            echo "<option disabled=\"disable\" value=\"\"><b> Seção: {$ses['titulo']}</b></option>";
            $readCat = new Read;
            $readCat->exeRead("categoria", "WHERE subcodigo = :parente ORDER BY titulo ASC", "parente={$ses['codigo']}");
            if($readCat->getRowCount() >= 1):
              foreach ($readCat->getResult() as $cat):
                echo " <option ";
                if($trabalho['categoriafilho'] == $cat['titulo']):
                  echo "selected=\"selected\" ";
                endif;
                echo "value=\"{$cat['codigo']}\">{$cat['titulo']}</option>";
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
      <input type="submit" class="btn btn-primary" name="EnviarTrabalho" value="Cadastrar" />
      <input type="submit" class="btn btn-info" name="EnviarTrabalho" value="Cadastrar e Publicar" />
      
  </form>
<div class="branco"></div>
</section>