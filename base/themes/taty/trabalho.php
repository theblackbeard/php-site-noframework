<?php
if($link->getData()):
        extract($link->getData());
		
    else:
        header('Location: ' . HOME . DIRECTORY_SEPARATOR . '404');
    endif;
    //var_dump($link->getData());


    /*Buscando indormacoes da categoria que pertence ao post*/
    $nomeCategoria = new Read;
    $nomeCategoria->exeRead("cweb_cattrab" , "WHERE ctrab_codigo = :cat" , "cat={$trab_categoria}");
    if($nomeCategoria->getResult()):
        $categoria = $nomeCategoria->getResult()[0]['ctrab_titulo'];
        $nomecate = $nomeCategoria->getResult()[0]['ctrab_nome'];
    else:
        $categoria = "Sem Categoria";
    endif;

?>

<div id="mt_conteudo" class="container-fluid">


    <div id="divcar">
        <img src="<?= HOME ?>/uploads/<?= $trab_foto ?>" alt="...">


        <div class="slide_conteudo">

            <header class="cabecalho">
                <h5>Publicado em:  <?= date('d/m/Y H:i', strtotime($trab_data));?> hs</b></time></h5>
                <h1><?= $trab_titulo; ?> <span class="badge"><?= $trab_visualizacao ?></span></h1>
            </header>
            <article class="corpo">
                <?= Check::word($trab_descricao, 80); ?>
                <hgroup class="botoes_inicial">
                    <a href="<?= HOME ?>/categoria/<?= $nomecate ?>" class="btn btn-info btn-sm" title="Categoria: <?= $categoria; ?>"><?= $categoria ?></a>
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#detalhes">
                        Mais Detalhes
                    </button>
                </hgroup>
            </article>

	   

        </div>




    </div>

    <!--- -->
    <div class="modal fade" id="detalhes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><?= $trab_titulo; ?></h4>
                </div>
                <div class="modal-body">
                   <?= $trab_descricao ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- -->


   <div class="col-md-offset-6 gal">
	
	
            <?php
                    $gb = 0;
                    $readGal = new Read;
                    $readGal->exeRead("cweb_galeria" , "WHERE post_codigo = :codigo ORDER BY galeria_data DESC" , "codigo={$trab_codigo}");
                    if($readGal->getResult()):
                        foreach($readGal->getResult() as $galeria):
                            $gb++;
                            extract($galeria);
            ?>
        <div class="col-lg-3 col-md-3 col-sm-4">
        <a href="<?= HOME; ?>/uploads/<?= $galeria_imagem; ?>" data-lightbox="image-1" class="thumbnail" data-title="Galeria de: <?= $trab_titulo ?>">
        <?= Check::Image('uploads' . DIRECTORY_SEPARATOR . $galeria_imagem, "Imagem {$gb} do post {$trab_titulo}", 150,180); ?>
        </a>
        </div>
            <?php
                        endforeach;
            endif;

            ?>
	


    </div>
		


</div>