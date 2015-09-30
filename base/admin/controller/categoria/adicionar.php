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
      <li class="active">Nova Categoria</li>
    </ol>
    <h2>Nova Categoria</h2>
    <p>Coloque aqui uma categoria para inclusão de trabalhos</p>
  </header>
  <?php 
    $categoria = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if(!empty($categoria['EnviarCategoria'])):
      unset($categoria['EnviarCategoria']);
      require ('_models/AdminCatTrab.class.php');
      $cadastra = new AdminCatTrab;
      $cadastra->exeCreate($categoria);
      if(!$cadastra->getResult()):
        MSG($cadastra->getError()[0], $cadastra->getError()[1]);
      else:
        header('Location: painel.php?mt=categoria/index&create=true');
      endif;
      
    
    endif;
  ?>
  <form action="#" method="post" name="EnviarCategoriaForm">
    <div class="form-group">
      <label for="titulo">Titulo</label>
      <input type="text" class="form-control" id="titulo" name="ctrab_titulo" value="<?php if(isset($categoria['ctrab_titulo'])) echo $categoria['ctrab_titulo']?>" placeholder="Insira um Titulo" required autofocus>
    </div>
    <div class="form-group">
      <label for="descricao">Descrição</label>
      <textarea class="form-control" id="descricao" name="ctrab_descricao" rows="5"><?php if(isset($categoria['ctrab_descricao'])) echo $categoria['ctrab_descricao']?></textarea>
    </div>

    <div class="form-inline">
      <label for="data">Data de Inclusão</label>
      <input type="text" class="form-control" id="data" name="ctrab_data" value="<?= date('d/m/Y H:i:s'); ?>" required autofocus>

      <label for="catsec">Categoria ou Seção</label>
      <select class="form-control" id="catsec" name="ctrab_parente">
        <option value="null">Categoria Inicial</option>
        <?php 
          $readSecao = new Read;
          $readSecao->exeRead("cweb_cattrab" , "WHERE ctrab_parente IS NULL ORDER BY ctrab_titulo ASC");
          if(!$readSecao->getResult()):
            echo '<option disabled="disabled" value="null">Cadastre Antes uma Seção!</option>';
           else:
             foreach ($readSecao->getResult() as $secao):
                echo "<option value=\"{$secao['ctrab_codigo']}\" ";
                if($secao['ctrab_codigo'] == $categoria['ctrab_parente']): echo ' selected="selected" ';                endif;
                echo ">{$secao['ctrab_titulo']}</option>";
             endforeach;
          endif;
        
        ?>
      </select>
    </div>
    <br>

    <input type="submit" class="btn btn-primary" name="EnviarCategoria" value="Cadastrar" />
  </form>

</section>