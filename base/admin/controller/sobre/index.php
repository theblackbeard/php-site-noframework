<?php
if (!class_exists('Login')):
  header('Location:  ../../painel.php?mt=home');
  die;
endif;
$login = new Login(3);
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
      MSG("Você pode criar apenas uma página sobre, Apague ou Atualiza a existente", MSG_ALERT);
    endif;
    $updated = filter_input(INPUT_GET, 'updated', FILTER_VALIDATE_BOOLEAN);
    if ($updated):
      MSG("Página Atualizada com Sucesso!", MSG_ACCEPT);
    endif;
    $delId = filter_input(INPUT_GET, 'delid', FILTER_VALIDATE_INT);
    if($delId):
      require ('_models/AdminSobre.class.php');
      $delete = new AdminSobre;
      $delete->exeDelete($delId);
      MSG($delete->getError()[0], $delete->getError()[1]);
    endif;
    
    
    ?>

  </header>

  <?php
  $lerSobre = new Read;
  $lerSobre->exeRead("cweb_sobre");
  if ($lerSobre->getResult()):
    foreach ($lerSobre->getResult() as $sobre):
      extract($sobre);
      ?>
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><?= $sobre_titulo; ?></h3>
        </div>
        <div class="panel-body">
          <?= $sobre_descricao; ?>
        </div>
        <div class="panel-footer">
          <a class="btn btn-primary glyphicon glyphicon-pencil" href="painel.php?mt=sobre/atualizar&sobreid=<?= $sobre_codigo ?>" title="Editar Sobre" ></a>
          <a class="btn btn-danger glyphicon glyphicon-remove" href="painel.php?mt=sobre/index&delid=<?= $sobre_codigo ?>" title="Excluir Sobre" ></a>
          <ul class="nav navbar-nav navbar-right datadir">
            <li>Data: <strong><?= date('d/m/Y H:i', strtotime($sobre_data))?></strong></li>
          </ul>
        </div>
      </div>

      <?php
      
    endforeach;
    else:
      MSG("Nenhuma pagina sobre foi incluida!" , MSG_ERROR);
  endif;
  ?>

</section>