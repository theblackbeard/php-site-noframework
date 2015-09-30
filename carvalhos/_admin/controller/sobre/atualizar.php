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
      <li class="active">Atualizar Sobre</li>
    </ol>
    <h2>Atualização de Sobre</h2>
  <p>Edite aqui uma descrição da sua pagina sobre.</p>
  </header>
  <?php
    $sobreId = filter_input(INPUT_GET, 'sobreid', FILTER_VALIDATE_INT);
    
     $atualizaSobre = filter_input_array(INPUT_POST, FILTER_DEFAULT);
     if(!empty($atualizaSobre['AtualizaSobre'])):
       unset($atualizaSobre['AtualizaSobre']);
       require ('models/AdminSobre.class.php');
       $atualiza = new AdminSobre;
       $atualiza->exeUpdate($sobreId, $atualizaSobre);
       if(!$atualiza->getResult()):
        MSG($atualiza->getError()[0], $atualiza->getError()[1]);
       else:
         header('Location: painel.php?mt=sobre/index&updated=true');
       endif;
     else:
       $read = new Read;
       $read->exeRead("sobre" , "WHERE codigo = :id" , "id={$sobreId}");
       if(!$read->getResult()):
         header('Location: painel.php?mt=sobre/index&empty=true');
       else:
         $atualizaSobre = $read->getResult()[0];
       endif;
     endif;
    
  ?>
  <form action="#" method="post" name="AtualizaSobreForm">
  <div class="form-group">
    <label for="titulo">Titulo</label>
    <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Insira um Titulo" value="<?php if(isset($atualizaSobre)) echo $atualizaSobre['titulo']; ?>" required autofocus>
  </div>
  <div class="form-group">
    <label for="descricao">Descrição</label>
    <textarea class="form-control" id="descricao" name="descricao" rows="15" >
    <?php
      if(isset($atualizaSobre)) echo $atualizaSobre['descricao'];
    ?>
    </textarea>
  </div>
  
  <input type="submit" class="btn btn-info" name="AtualizaSobre" value="Atualizar" />
</form>
  
</section>