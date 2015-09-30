<?php
if($link->getData()):
	extract($link->getData());
else:
	header('Location: ' . HOME . DIRECTORY_SEPARATOR . '404');
endif;
?>
<div id="pagina">
    <header id="cabecalho">
        <h1><?= $titulo ?></h1>
        <h2>Web Developer</h2>
        <hr>
    </header>
    <main id="conteudo">
        <article>
<?php
$getPage = (!empty($link->getLocal()[2]) ? $link->getLocal()[2] : 1);
$paginacao = new Pager( HOME . '/categoria/' . $nome . '/');
$paginacao->exePager($getPage, 20);
$works = new Read;
$works->exeRead("post" , "WHERE status = 1 AND (categoriapai = :cat OR categoriafilho =:cat) ORDER BY datacad DESC LIMIT :limit OFFSET :offset", "cat={$codigo}&limit={$paginacao->getLimit()}&offset={$paginacao->getOffset()}");
if(!$works->getResult()):
 MSG("Nenhum Trabalho Postado" , MSG_ERROR);
else:
 foreach ($works->getResult() as $allWorks):
   $view = new View;
   $tpl_allWorks = $view->load('ultimos_posts');
   $allWorks['descricao'] = Check::word($allWorks['descricao'], 60);
   $allWorks['datetime'] = date('Y-m-d' , strtotime($allWorks['datacad']));
   $allWorks['pubdate'] = date('d/m/Y' , strtotime($allWorks['datacad']));
   $view->Show($allWorks, $tpl_allWorks);
 endforeach;
endif;?>
        </article>
    </main>
</div>