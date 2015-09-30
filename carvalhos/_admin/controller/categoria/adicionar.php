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
      <li class="active">Nova Categoria</li>
    </ol>
    <h2>Nova Categoria</h2>
    <p>Coloque aqui uma categoria para inclusão de trabalhos</p>
  </header>
  <?php 
    $categoria = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if(!empty($categoria['EnviarCategoria'])):
      $categoria['foto'] = ($_FILES['foto']['tmp_name'] ? $_FILES['foto'] : null);
      unset($categoria['EnviarCategoria']);
      require ('models/AdminCatTrab.class.php');
      $cadastra = new AdminCatTrab;
      $cadastra->exeCreate($categoria);
      if(!$cadastra->getResult()):
        MSG($cadastra->getError()[0], $cadastra->getError()[1]);
      else:
        header('location: painel.php?mt=usuario/index$created=true');
      endif;
      
    
    endif;
  ?>
  <form action="#" method="post" name="EnviarCategoriaForm" enctype="multipart/form-data">
     <div class="form-group">
          <label for="foto">Foto de Capa</label>
          <input type="file" id="foto" name="foto">
      </div>
    <div class="form-group">
      <label for="titulo">Titulo</label>
      <input type="text" class="form-control" id="titulo" name="titulo" value="<?php if(isset($categoria['titulo'])) echo $categoria['titulo']?>" placeholder="Insira um Titulo" required autofocus>
    </div>
    <div class="form-group">
      <label for="descricao">Descrição</label>
      <textarea class="form-control" id="descricao" name="descricao" ><?php if(isset($categoria['descricao'])) echo $categoria['descricao']?></textarea>
    </div>

    <div class="form-group">
      
      <label for="catsec">Categoria ou Seção</label>
      <select class="form-control" id="catsec" name="subcodigo">
        <option value="null">Categoria Inicial</option>
        <?php 
          $readSecao = new Read;
          $readSecao->exeRead("categoria" , "WHERE subcodigo IS NULL ORDER BY nome ASC");
          if(!$readSecao->getResult()):
            echo '<option disabled="disabled" value="null">Cadastre Antes uma Seção!</option>';
           else:
             foreach ($readSecao->getResult() as $secao):
                echo "<option value=\"{$secao['codigo']}\" ";
                if($secao['codigo'] == $categoria['subcodigo']): echo ' selected="selected" ';                endif;
                echo "><b> Categoria Pai ( Seção ): {$secao['titulo']}</b></option>";
             endforeach;
          endif;
        
        ?>
      </select>
    </div>
    <br>

    <input type="submit" class="btn btn-primary" name="EnviarCategoria" value="Cadastrar" />
  </form>

</section>