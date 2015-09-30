<?php
if (!class_exists('Login')):
    header('Location: ../../painel.php');
    die;
endif;

$getPage = filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT);
$paginacao = new Pager('painel.php?mt=trabalho/index&pagina=', '&laquo;', '&raquo;');
$paginacao->exePager($getPage, 10);

?> 
<section>
  <header>
    <ol class="breadcrumb">
      <li><a href="painel.php?mt=home">Dashboard</a></li>
      <li class="active">Listagem de Trabalhos</li>
    </ol>
    <h2>Listagem de Trabalhos</h2>
    <p></p>

  </header>

  <?php
  
  $created = filter_input(INPUT_GET, 'created' , FILTER_VALIDATE_BOOLEAN);
  if($created):
    MSG("Trabalho Incluido Com Sucesso!", MSG_ACCEPT);
  endif;
  
  $acao = filter_input(INPUT_GET, 'acao', FILTER_DEFAULT);
  if($acao):
    require ('_models/AdminTrabalho.class.php');
    $trabAcao = filter_input(INPUT_GET, 'trabalho' , FILTER_VALIDATE_INT);
    $trabUpdate = new AdminTrabalho;
    
    switch ($acao):
      case 'ativa':
        $trabUpdate->exeStatus($trabAcao, '1');
        MSG("O Status do Trabalho está <b>Ativo e Publicado</b>" , MSG_ACCEPT);
        break;
      case 'inativa':
        $trabUpdate->exeStatus($trabAcao, '0');
          MSG("O Status do Trabalho está <b>Inativo e em Rascunho</b>" , MSG_INFO);
        break;
      case 'deleta':
        $trabUpdate->exeDelete($trabAcao);
        if($trabUpdate->getError()):
          MSG($trabUpdate->getError()[0], $trabUpdate->getError()[1]);
        else:
          MSG($trabUpdate->getError()[0], $trabUpdate->getResult()[1]);
        endif;
        break;
      default:
        MSG("Ação não identificada pelo sistema" , MSG_ALERT);
    endswitch;
  endif;
  
  $empty = filter_input(INPUT_GET, 'empty', FILTER_VALIDATE_BOOLEAN);
  if($empty):
    MSG("Trabalho Inexistente", MSG_ALERT);
  endif;
  
  $updated = filter_input(INPUT_GET, 'updated' , FILTER_VALIDATE_BOOLEAN);
  if($updated):
    MSG("Trabalho Atualizado com Sucesso!" , MSG_ACCEPT);
  endif;
  
  
  $readTrabalhos = new Read;
  $readTrabalhos->exeRead("cweb_trabalho", "ORDER BY trab_data DESC LIMIT :limit OFFSET :offset", "limit={$paginacao->getLimit()}&offset={$paginacao->getOffset()}");
  if ($readTrabalhos->getResult()):
    foreach ($readTrabalhos->getResult() as $trabalhos):
      extract($trabalhos);
      $status =  (!$trab_status ? 'style="background:#f2f2f2"' : '');
      //Leitura de Trabalhos
      $getNomeCategoria = new Read;
      $getNomeCategoria->exeRead("cweb_cattrab", "WHERE ctrab_parente =:codParente", "codParente={$trab_cat_parente}");
      $nomeCat = $getNomeCategoria->getResult()[0];
      //Leitura de Autor
      $getNomeAutor = new Read;
      $getNomeAutor->exeRead("cweb_usuario", "WHERE usu_codigo =:codigo", "codigo={$trab_autor}");
      if($getNomeAutor->getResult()):
         $nomeAutor = $getNomeAutor->getResult()[0];
      else:
          $nomeAutor['usu_nome'] = "Autor Inexistente";
          $nomeAutor['usu_sobrenome'] = "";
      endif;
     
      
      ?>
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><a href="../trabalho/<?= $trab_nome ?>" title="Ver Postagem No Site" target="_blank" ><?= $trab_titulo ?></a></h3>
        </div>
        <div class="panel-body">
          <div class="media">
            <a class="media-left" href="#" title="Ver <?= $trab_titulo; ?> no Site">
              <?= Check::image('../uploads/' . $trab_foto, $trab_titulo, 64, 64);?>
            </a>
            <div class="media-body">
              <p class="media-heading">
                <?= Check::word($trab_descricao, 50); ?>
                
              </p>

            </div>
          </div> 
        </div>
        <div class="panel-footer">
          <a class="btn btn-primary glyphicon glyphicon-pencil" href="painel.php?mt=trabalho/atualizar&trabalhoid=<?= $trab_codigo;?>" title="Editar Trabalho" ></a>
          
          <?php if(!$trab_status) : ?>
          <a class="btn btn-primary glyphicon glyphicon-eye-close" href="painel.php?mt=trabalho/index&trabalho=<?= $trab_codigo;?>&acao=ativa" title="Ativar Trabalho " ></a>
          <?php else: ?>
          <a class="btn btn-info glyphicon glyphicon-eye-open" href="painel.php?mt=trabalho/index&trabalho=<?= $trab_codigo;?>&acao=inativa" title="Inativar Trabalho" ></a>
          <?php endif; ?>
          
          
          <a class="btn btn-danger glyphicon glyphicon-remove" href="painel.php?mt=trabalho/index&trabalho=<?= $trab_codigo?>&acao=deleta" title="Excluir Trabalho" ></a>
          <ul class="nav navbar-nav navbar-right ">
            <li class="datadir">Visualizações: <span class="badge"><?= $trab_visualizacao; ?></span> </li>
            <li class="datadir">Categoria: <b><?= $nomeCat['ctrab_titulo']; ?></b> </li>
            <li class="datadir">Autor: <b><?= $nomeAutor['usu_nome']; echo " "; echo $nomeAutor['usu_sobrenome']; ?></b></li>
            <li class="datadir">Data: <b><?= date('d/m/Y H:i', strtotime($trab_data)); ?></b></li>
          </ul>
        </div>
      </div>

    </section>

    <?php
  endforeach;
  else:
    $paginacao->returnPage();
    MSG("Ainda não existem trabalhos cadastrados" , MSG_INFO);
endif;

$paginacao->exePaginator("cweb_trabalho");
echo $paginacao->getPaginator();



?>