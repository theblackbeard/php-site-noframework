 <?php
 if($link->getData()):
 	extract($link->getData());
 else:
 	header('Location: ' . HOME . DIRECTORY_SEPARATOR . '404');
 endif;
$view = new View;
$tpl_work = $view->load('trabalho');
$tpl_gallery = $view->Load('galeria_trabalho');
$tpl_relations = $view->load('relations');

?>

<div id="pagina">

	<?php  $view->Show($link->getData(), $tpl_work); ?>


    <?php
    $gallery = new Read;
    $gallery->exeRead("galeria" , "WHERE trabalho = :codigo ORDER BY data DESC", "codigo={$codigo}");
    if($gallery->getResult()): ?>
    <section id="conteudo">


       <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Fotos Relacionadas</h3>
          </div>
          <div class="panel-body">
             <div class="row">
                        <?php
                         foreach ($gallery->getResult() as $photos):
                             extract($photos);
                             $photos['gallery'] = $imagem;
                             $view->Show($photos, $tpl_gallery);
                         endforeach; ?>
             </div>
          </div>
        </div>


    </section>
    <?php endif; ?>

<?php
$relations = new Read;
$relations->exeRead("trabalho", "WHERE status = 1 AND codigo != :id AND categoriafilho = :cat ORDER BY RAND() LIMIT 4", "id={$codigo}&cat={$categoriafilho}");
   if($relations->getResult()): ?>
        <section id="conteudo">

        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Trabalhos Relacionados</h3>
          </div>
          <div class="panel-body">
            <div class="row">
                    <?php
                        foreach($relations->getResult() as $allRel):
                            $view->Show($allRel, $tpl_relations);
                        endforeach;
                    ?>
            </div>
          </div>
        </div>



        </section>
<?php endif; ?>


</div>

   <section id="conteudo">

    <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Curte a Nossa Página no Facebook</h3>
          </div>
          <div class="panel-body">
          <div class="fb-like" data-href="https://www.facebook.com/devcarv" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>
          </div>
        </div>
    </div>

        <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Contato Google+</h3>
          </div>
          <div class="panel-body">
            <!-- Posicione esta tag no cabeçalho ou imediatamente antes da tag de fechamento do corpo. -->
            <script src="https://apis.google.com/js/platform.js" async defer>
              {lang: 'pt-BR'}
            </script>

            <!-- Posicione esta tag onde você deseja que o widget apareça. -->
            <div class="g-page" data-width="350" data-href="//plus.google.com/u/0/100624809176293290562" data-layout="landscape" data-rel="publisher"></div>
          </div>
        </div>
        </div>
        </section>




<section id="conteudo">
<div class="fb-comments" data-href="<?= HOME ?>/trabalho/<?= $nome ?>" data-width="100%" data-numposts="10" data-colorscheme="light"></div>
</section>

