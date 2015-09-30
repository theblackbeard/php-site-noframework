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
            <li class="active">Listagem de Paginas Pessoais</li>
        </ol>
        <h2>Listagem de Paginas Pessoais</h2>
        <p></p>

    </header>

    <?php

    $created = filter_input(INPUT_GET, 'created' , FILTER_VALIDATE_BOOLEAN);
    if($created):
        MSG("Pagina Incluida Com Sucesso!", MSG_ACCEPT);
    endif;

    $acao = filter_input(INPUT_GET, 'acao', FILTER_DEFAULT);
    if($acao):
        require('models/AdminTaverna.class.php');
        $trabAcao = filter_input(INPUT_GET, 'tav' , FILTER_VALIDATE_INT);
        $funcTav = new AdminTaverna;

        switch ($acao):
            case 'ativa':
                $funcTav->exeStatus($trabAcao, '1');
                MSG("O Status do Trabalho está <b>Ativo e Publicado</b>" , MSG_ACCEPT);
                break;
            case 'inativa':
                $funcTav->exeStatus($trabAcao, '0');
                MSG("O Status do Trabalho está <b>Inativo e em Rascunho</b>" , MSG_INFO);
                break;
            case 'deleta':
                $funcTav->exeDelete($trabAcao);
                if($funcTav->getError()):
                    MSG($funcTav->getError()[0], $funcTav->getError()[1]);
                else:
                    MSG($funcTav->getError()[0], $funcTav->getResult()[1]);
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


    $readTaverna = new Read;
    $readTaverna->exeRead("taverna", "ORDER BY data DESC LIMIT :limit OFFSET :offset", "limit={$paginacao->getLimit()}&offset={$paginacao->getOffset()}");
    if ($readTaverna->getResult()):
    foreach ($readTaverna->getResult() as $taverna):
    extract($taverna);



    ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><a href="../taverna/<?= $nome ?>" title="Ver Postagem No Site" target="_blank" ><b><?= $titulo ?></b></a>



        </div>
        <div class="panel-body">

            <div class="media">
                <a class="media-left" href="#" title="Ver <?= $titulo; ?> no Site">

                </a>
                <div class="media-body">
                    <p class="media-heading">
                        <?= Check::word($descricao, 50); ?>

                    </p>
                    <span class="label label-info"><?= date('d/m/Y H:i', strtotime($data)); ?> </span>

                </div>
            </div>
        </div>
        <div class="panel-footer">
            <a class="btn btn-primary glyphicon glyphicon-pencil" href="painel.php?mt=taverna/atualizar&id=<?= $codigo;?>" title="Editar Trabalho" ></a>

            <?php if($status) : ?>
                <a class="btn btn-danger glyphicon glyphicon-eye-close" href="painel.php?mt=taverna/index&tav=<?= $codigo;?>&acao=ativa" title="Ativar Trabalho " ></a>
            <?php else: ?>
                <a class="btn btn-success glyphicon glyphicon-eye-open" href="painel.php?mt=taverna/index&tav=<?= $codigo;?>&acao=inativa" title="Inativar Trabalho" ></a>
            <?php endif; ?>


            <a class="btn btn-danger glyphicon glyphicon-remove" href="painel.php?mt=taverna/index&tav=<?= $codigo?>&acao=deleta" title="Excluir Trabalho" ></a>





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


