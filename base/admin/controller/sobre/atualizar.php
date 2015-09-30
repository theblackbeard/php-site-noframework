<?php
   if(!class_exists('Login')):
    header('Location:  ../../painel.php?mt=home');
    die;
  endif;
  $login = new Login(3);
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
    $create = filter_input(INPUT_GET,'create', FILTER_VALIDATE_BOOLEAN);
    if($create):
      MSG("Página sobre criada com sucesso!, caso queria mudar alguma informação continue editando" , MSG_ACCEPT);
    endif;
     $atualizaSobre = filter_input_array(INPUT_POST, FILTER_DEFAULT);
     if(!empty($atualizaSobre['AtualizaSobre'])):
       unset($atualizaSobre['AtualizaSobre']);
       require ('_models/AdminSobre.class.php');
       $atualiza = new AdminSobre;
       $atualiza->exeUpdate($sobreId, $atualizaSobre);
       if(!$atualiza->getResult()):
        MSG($atualiza->getError()[0], $atualiza->getError()[1]);
       else:
         header('Location: painel.php?mt=sobre/index&updated=true');
       endif;
     else:
       $read = new Read;
       $read->exeRead("cweb_sobre" , "WHERE sobre_codigo = :id" , "id={$sobreId}");
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
    <input type="text" class="form-control" id="titulo" name="sobre_titulo" placeholder="Insira um Titulo" value="<?php if(isset($atualizaSobre)) echo $atualizaSobre['sobre_titulo']; ?>" required autofocus>
  </div>
  <div class="form-group">
    <label for="descricao">Descrição</label>
    <textarea class="form-control" id="descricao" name="sobre_descricao" rows="15" required> 
    <?php
      if(isset($atualizaSobre)) echo $atualizaSobre['sobre_descricao'];
    ?>
    </textarea>
  </div>
  
  <input type="submit" class="btn btn-default" name="AtualizaSobre" value="Atualizar" />
</form>
  
</section>