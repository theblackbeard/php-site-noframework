<?php
if($link->getData()):
    extract($link->getData());


else:
    header('Location: ' . HOME . DIRECTORY_SEPARATOR . '404');
endif;
$view = new View;
$tpl_postagens = $view->Load('post');
$tpl_socials = $view->Load('socials');
$tpl_relations = $view->Load('relations');
?>

<article id="post" class="postagens">
<?php
    $posts = new Read;
    $posts->exeRead("post" , "WHERE codigo = :id" , "id={$codigo}");
        if($posts->getResult()):
           foreach($posts->getResult() as $posts):
                $posts['datetime'] = date('Y-m-d' , strtotime($posts['dataup']));
                $posts['pubdate'] = date('d/m H:i' , strtotime($posts['dataup']));
                 $view->Show($posts, $tpl_postagens);
           endforeach;
        endif;

$view->Show($link->getData(), $tpl_socials);

$relations = new Read;
$relations->exeRead("post", "WHERE status = 1 AND codigo != :id AND categoriafilho = :cat ORDER BY RAND() LIMIT 10", "id={$codigo}&cat={$categoriafilho}");
    if($relations->getResult()):
?>
    <div class="col-md-12">
        <div class="well">
            <h1>Projetos Relacionados</h1>
            <ul class="list-group">
                <?php
                foreach ($relations->getResult() as $allRelations):
                    $view->Show($allRelations, $tpl_relations);
                endforeach;
                endif;

                ?>

            </ul>
        </div>
    </div>

</article>

