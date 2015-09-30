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
      <li class="active">Listagem de Sobre</li>
    </ol>
    <h2>Listagem de Sobre</h2>
    <?php
    
    $empty = filter_input(INPUT_GET, 'empty', FILTER_VALIDATE_BOOLEAN);
    if ($empty):
      MSG("Você tentou atualizar uma página inexistente", MSG_ALERT);
    endif;
    
    $created = filter_input(INPUT_GET, 'created', FILTER_VALIDATE_BOOLEAN);
    if ($created):
      MSG("Página Sobre foi criado com sucesso!" , MSG_ACCEPT);
    endif;
    
    $errorCreated = filter_input(INPUT_GET, 'errorCreated' , FILTER_VALIDATE_BOOLEAN);
    if($errorCreated):
    	MSG("Você apenas poderá inclui uma página sobre, já existente!", MSG_INFO);
    endif;
    
    $updated = filter_input(INPUT_GET, 'updated', FILTER_VALIDATE_BOOLEAN);
    if ($updated):
      MSG("Página atualizada com Sucesso!", MSG_ACCEPT);
    endif;
    
    $delId = filter_input(INPUT_GET, 'delid', FILTER_VALIDATE_INT);
    if($delId):
      require ('models/AdminSobre.class.php');
      $delete = new AdminSobre;
      $delete->exeDelete($delId);
      MSG($delete->getError()[0], $delete->getError()[1]);
    endif;
    
    
    ?>

  </header>

  <?php
  $lerSobre = new Read;
  $lerSobre->exeRead("sobre");
  if ($lerSobre->getResult()):
    foreach ($lerSobre->getResult() as $sobre):
      extract($sobre);
      ?>
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><?= $titulo; ?></h3>
        </div>
        <div class="panel-body">
          <?= $descricao; ?>
        </div>
        <div class="panel-footer">
          <a class="btn btn-primary glyphicon glyphicon-pencil" href="painel.php?mt=sobre/atualizar&sobreid=<?= $codigo ?>" title="Editar Sobre" ></a>
          <a class="btn btn-danger glyphicon glyphicon-remove" href="painel.php?mt=sobre/index&delid=<?= $codigo ?>" title="Excluir Sobre" ></a>
      
        </div>
      </div>

      <?php
      
    endforeach;
    else:
      MSG("Nenhuma pagina sobre foi incluida!" , MSG_ERROR);
  endif;
  ?>

</section>