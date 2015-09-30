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
            <li class="active">Listagem de Postagens</li>
        </ol>
        <h2>Listagem de Postagens</h2>
        <p></p>

    </header>

    <?php

    $created = filter_input(INPUT_GET, 'created' , FILTER_VALIDATE_BOOLEAN);
    if($created):
        MSG("Postagem Incluida Com Sucesso!", MSG_ACCEPT);
    endif;

    $empty = filter_input(INPUT_GET, 'empty', FILTER_VALIDATE_BOOLEAN);
    if($empty):
        MSG("Trabalho Inexistente", MSG_ALERT);
    endif;

    $updated = filter_input(INPUT_GET, 'updated' , FILTER_VALIDATE_BOOLEAN);
    if($updated):
        MSG("Postagem Atualizada com Sucesso!" , MSG_ACCEPT);
    endif;


    $acao = filter_input(INPUT_GET, 'acao', FILTER_DEFAULT);
    if($acao):
        require ('models/AdminPost.class.php');
        $postAcao = filter_input(INPUT_GET, 'post' , FILTER_VALIDATE_INT);
        $postFunc = new AdminPost();

        switch ($acao):
            case 'ativa':
                $postFunc->exeStatus($postAcao, '1');
                MSG("O Status do Trabalho está <b>Ativo e Publicado</b>" , MSG_ACCEPT);
                break;
            case 'inativa':
                $postFunc->exeStatus($postAcao, '0');
                MSG("O Status do Trabalho está <b>Inativo e em Rascunho</b>" , MSG_INFO);
                break;
            case 'deleta':
                $postFunc->exeDelete($postAcao);
                if($postFunc->getError()):
                    MSG($postFunc->getError()[0], $postFunc->getError()[1]);
                else:
                    MSG($postFunc->getError()[0], $postFunc->getResult()[1]);
                endif;
                break;
            default:
                MSG("Ação não identificada pelo sistema" , MSG_ALERT);
        endswitch;
    endif;



    $readTrabalhos = new Read;
    $readTrabalhos->exeRead("post", "ORDER BY datacad DESC LIMIT :limit OFFSET :offset", "limit={$paginacao->getLimit()}&offset={$paginacao->getOffset()}");
    if ($readTrabalhos->getResult()):
    foreach ($readTrabalhos->getResult() as $trabalhos):
    extract($trabalhos);
    $status =  (!$status ? 'style="background:#f2f2f2"' : '');
    //Leitura de Trabalhos
    $getNomeCategoria = new Read;
    $getNomeCategoria->exeRead("categoria_post", "WHERE subcodigo =:codParente", "codParente={$categoriapai}");
    $nomeCat = $getNomeCategoria->getResult()[0];


    ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><a href="../blog/<?= $nome ?>" title="Ver Postagem No Site" target="_blank" ><b><?= $titulo ?></b></a>


                <span class="badge pull-right">Visualizações: <?= $aberto; ?></span> </h3>

        </div>
        <div class="panel-body">

            <div class="media">
                <a class="media-left" href="#" title="Ver <?= $titulo; ?> no Site">
                    <?= Check::image('../uploads/' . $foto, $titulo, 64, 64);?>
                </a>
                <div class="media-body">
                    <p class="media-heading">
                        <?= Check::word($descricao, 50); ?>

                    </p>
                    <span class="label label-info"><?= date('d/m/Y H:i', strtotime($datacad)); ?> </span>

                </div>
            </div>
        </div>
        <div class="panel-footer">
            <a class="btn btn-primary glyphicon glyphicon-pencil" href="painel.php?mt=post/atualizar&postId=<?= $codigo;?>" title="Editar Trabalho" ></a>

            <?php if($status) : ?>
                <a class="btn btn-danger glyphicon glyphicon-eye-close" href="painel.php?mt=post/index&post=<?= $codigo;?>&acao=ativa" title="Ativar Trabalho " ></a>
            <?php else: ?>
                <a class="btn btn-success glyphicon glyphicon-eye-open" href="painel.php?mt=post/index&post=<?= $codigo;?>&acao=inativa" title="Inativar Trabalho" ></a>
            <?php endif; ?>


            <a class="btn btn-danger glyphicon glyphicon-remove" href="painel.php?mt=post/index&post=<?= $codigo?>&acao=deleta" title="Excluir Trabalho" ></a>


            <span class="label label-danger pull-right"><?= $nomeCat['titulo']; ?></span>

        </div>
    </div>

</section>

<?php
endforeach;
else:
    $paginacao->returnPage();
    MSG("Ainda não existem trabalhos cadastrados" , MSG_INFO);
endif;

$paginacao->exePaginator("trabalho");
echo $paginacao->getPaginator();



?>


