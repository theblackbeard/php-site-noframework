<?php
if (!class_exists('Login')):
    header('Location: ../../painel.php');
    die;
endif;

$empty = filter_input(INPUT_GET, 'empty', FILTER_VALIDATE_BOOLEAN);
if ($empty):
    MTErro("Você tentou editar uma categoria inexistente", MT_INFOR);
endif;
?>

<section>
  <header>
    <ol class="breadcrumb">
      <li><a href="painel.php?mt=home">Dashboard</a></li>
      <li class="active">Listagem de Categorias</li>
    </ol>
    <h2>Listagem de Categorias</h2>
  </header>
  <?php
   $delcont  = filter_input(INPUT_GET, 'deletar' , FILTER_VALIDATE_INT);
  if($delcont):
        require ('_models/AdminContato.class.php');
        $deletar = new AdminContato;
        $deletar->exeDelete($delcont);
        MSG($deletar->getError()[0], $deletar->getError()[1]);
        
    endif;

    $created = filter_input(INPUT_GET, 'created' , FILTER_VALIDATE_BOOLEAN);
    if($created):
        MSG("Uma pagina de contato foi adicionada com sucesso!" , MSG_ACCEPT);
    endif;

    $updated = filter_input(INPUT_GET, 'updated' , FILTER_VALIDATE_BOOLEAN);
  if($updated):
    MSG("A Pagina de Contato foi atualizado com sucesso!" , MSG_ACCEPT);
  endif;

    $lerCont = new Read;
  $lerCont->exeRead("cweb_contato");
    if ($lerCont->getResult()):
        foreach ($lerCont->getResult() as $cont):
            extract($cont);
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= $cont_titulo; ?></h3>
                </div>
                <div class="panel-body">
                    <?= $cont_descricao; ?>
                </div>
                <div class="panel-footer">
                    <a class="btn btn-primary glyphicon glyphicon-pencil" href="painel.php?mt=contato/atualizar&contid=<?= $cont_codigo ?>" title="Editar Contato" ></a>
                    <a class="btn btn-danger glyphicon glyphicon-remove" href="painel.php?mt=contato/index&deletar=<?= $cont_codigo ?>" title="Excluir Contato" ></a>

                </div>
            </div>

        <?php

        endforeach;
    else:
        MSG("Nenhuma pagina sobre foi incluida!" , MSG_ERROR);
    endif;
    ?>
</section>