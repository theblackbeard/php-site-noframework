<?php
if($link->getData()):
    extract($link->getData());


else:
    header('Location: ' . HOME . DIRECTORY_SEPARATOR . '404');
endif;
$view = new View;
$tpl_postagens = $view->Load('postsumary');

$getPage = (!empty($link->getLocal()[2]) ? $link->getLocal()[2] : 1);
$paginacao = new Pager( HOME . '/blog/' . $nome . '/');
$paginacao->exePager($getPage, 20);




?>

<section id="postagens" class="postagens">
    <div class="col-md-12">
        <a href="<?= HOME ?>/blog/todas-as-postagens" class="btn btn-info" >Todas as Postagens</a>
        <a href="<?= HOME ?>" class="btn btn-danger pull-right">Inicio</a>
        <h1>Todas as Postagens</h1>
    </div>

    <div class="col-md-8">

            <?php
                $posts = new Read;
                $posts->exeRead("post" , "WHERE status = 1 AND (categoriapai = :cat OR categoriafilho =:cat) ORDER BY datacad DESC LIMIT :limit OFFSET :offset", "cat={$codigo}&limit={$paginacao->getLimit()}&offset={$paginacao->getOffset()}");
                if(!$posts->getResult()):
                    MSG("Nenhum Trabalho Postado", MSG_ERROR);
                else:
                    foreach($posts->getResult() as $allPosts):
                        $allPosts['descricao'] = Check::word($allPosts['descricao'], 50);
                        $allPosts['datetime'] = date('Y-m-d' , strtotime($allPosts['datacad']));
                        $allPosts['pubdate'] = date('d/m H:i' , strtotime($allPosts['datacad']));
                        $view->Show($allPosts, $tpl_postagens);
                    endforeach;
                endif;

            ?>
        </div>

    <div class="col-md-4">
        <div class="well">
            <ul class="list-group">
            <li class="list-group-item">Navegação</li>
            <?php
                $category = new Read;
                $category->exeRead("categoria_post", "WHERE subcodigo = :sub", "sub={$codigo}");
                if(!$category->getResult()):
                    $dad = new Read;
                    $dad->exeRead("categoria_post", "WHERE subcodigo IS NULL ORDER BY titulo ASC");
                    if($dad->getResult()):
                        foreach ($dad->getResult() as $allDads):
            ?>
                            <li class="list-group-item"><a href="<?= $allDads['nome'] ?>" class="linka"><b><?= $allDads['titulo'] ?></b></a></li>
            <?php
                        endforeach;
                endif;
                else:
                    foreach($category->getResult() as $subCat):
            ?>
            <li class="list-group-item"><b><a href="<?= HOME ?>/blog/<?= $subCat['nome'] ?>" class="linka"><?= $subCat['titulo'] ?></a></b></li>
            <?php
                endforeach;
                endif;
            ?>
            </ul>
        </div>

    </div>
    <div class="col-md-12">
        <div class="curto_espaco"></div>
        <div class="container">
            <nav>

                <ul class="pagination pull-left">
                    <?php
                    $paginacao->returnPage();
                    $paginacao->exePaginator("trabalho");
                    echo $paginacao->getPaginator();

                    ?>
                </ul>
            </nav>
        </div>

    </div>
</section>

