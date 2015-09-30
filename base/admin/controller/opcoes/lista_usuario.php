<?php
if(!class_exists('Login')):
        header('Location: ../../painel.php?mt=home');
        die;
    endif;
    
    $acao = filter_input(INPUT_GET, 'acao', FILTER_DEFAULT);
    if($acao):
      require ('_models/AdminUsuario.class.php');
      $usuarioAcao = filter_input(INPUT_GET, 'usuario', FILTER_VALIDATE_INT);
      $usuarioUpdate = new AdminUsuario;
      
      switch ($acao):
        case 'deletar':
          $usuarioUpdate->exeDelete($usuarioAcao);
          if($usuarioUpdate->getError()):
            MSG($usuarioUpdate->getError()[0], $usuarioUpdate->getError()[1]);
          else:
               MSG($usuarioUpdate->getError()[0], $usuarioUpdate->getError()[1]);
          endif;
          break;
        default :
          MSG("A ação não foi identificada pelo sistema" , MSG_INFO);
      endswitch;
    endif;
    
 ?>


<section>
  <header>
    <ol class="breadcrumb">
      <li><a href="painel.php?mt=home">Dashboard</a></li>
      <li class="active">Usuarios Cadastrados</li>
    </ol>
    <h2>Usuarios Cadastrados</h2>
    <p>Usuario cadastrados no sistema</p>
  </header>
  
  <?php
  
    $created = filter_input(INPUT_GET,'created', FILTER_VALIDATE_BOOLEAN);
    if($created):
      MSG("Usuario Cadastrado Com Sucesso!" , MSG_ACCEPT);
    endif;
    
    $updated = filter_input(INPUT_GET,'updated', FILTER_VALIDATE_BOOLEAN);
    if($updated):
      MSG("Usuario Atualizado Com Sucesso!" , MSG_ACCEPT);
    endif;
    
    $empty = filter_input(INPUT_GET,'empty', FILTER_VALIDATE_BOOLEAN);
    if($empty):
      MSG("Usuario Inexistente" , MSG_ERROR);
    endif;
  
  
    $readUsuario = new Read;
    $readUsuario->exeRead("cweb_usuario", "ORDER BY usu_nome ASC");
    if($readUsuario->getResult()):
      foreach ($readUsuario->getResult() as $usuarios):
        extract($usuarios);
      
    
  ?>
  <article class="col-md-3">
  <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo $usuarios['usu_nome']; echo " "; echo $usuarios['usu_sobrenome']; ?></h3>
  </div>
  <ul class="list-group">
    <li class="list-group-item">E-mail: <b><?= $usuarios['usu_email']; ?></b></li>
    <li class="list-group-item">Registro: <b><?= date('d/m/Y H:i', strtotime($usuarios['usu_registro']))?></b></li>
  <li class="list-group-item">U. Acesso: <b><?= date('d/m/Y H:i', strtotime($usuarios['usu_ultimoacesso']))?></b></li>
  <li class="list-group-item">Nivel: <b>
    <?php
      if($usuarios['usu_nivel'] == 3):
        echo "<b>Administrador</b>";
      else:
        echo "<b>Usuario Comum</b>";
      endif;
    ?>
      
    </b></li>
  </ul>
    
    <div class="panel-footer">
      <a class="btn btn-primary glyphicon glyphicon-pencil" title="Editar Usuario" href="painel.php?mt=opcoes/atualizar_usuario&usuarioid=<?= $usu_codigo; ?>"></a>
      <a class="btn btn-danger glyphicon glyphicon-remove" title="Excluir Usuario" href="painel.php?mt=opcoes/lista_usuario&usuario=<?= $usu_codigo; ?>&acao=deletar"></a>
    </div>
</div>
  </article>
  <?php
      endforeach;
    endif;
  ?>
</section>