<?php

 if($link->getData()):
        extract($link->getData());
    else:
        header('Location: ' . HOME . DIRECTORY_SEPARATOR . '404');
    endif;
    //var_dump($link->getData());
    
   $nomeCategoria = new Read;
   $nomeCategoria->exeRead("cweb_cattrab", "WHERE ctrab_codigo = :id", "id={$trab_categoria}");
   $titulo = $nomeCategoria->getResult()[0]['ctrab_titulo'];
   $nome = $nomeCategoria->getResult()[0]['ctrab_nome'];
   $nomeCategoria->setPlaces("id={$trab_cat_parente}");
   $parente = $nomeCategoria->getResult()[0]['ctrab_titulo'];
   
           ?>
<section id="mt_conteudo" class="container-fluid">
 
  <header id="mt_barra">
    <div class="mt_sobre">
      
      <h5>Publicado em:  <time datetime="<?php date('Y-m-d' , strtotime($trab_data));?>" pubdate><b><?= date("d/m/Y H:s" , strtotime($trab_data)) ?> hs</b></time></h5>
    <h1><?= $trab_titulo ?> <span class="badge"><?= $trab_visualizacao ?></span></h1>
    <p><?= Check::word($trab_descricao, 200) ?></p>
    <br>
    <p><a href="<?= HOME ?>/categoria/<?= $nome ?>" class="btn btn-primary btn-sm"><?= $titulo ?></a>
      <a href="<?= HOME ?>/categoria/projetos" class="btn btn-info btn-sm"><?= $parente ?></a></p>
    </div>
  </header>
   <div id='divcar'>
    <img src="<?= HOME ?>/uploads/<?= $trab_foto ?>" alt="...">
  </div>
  
  <div class="janela_galeria">
     <?php
            $gb = 0;
            $readGb = new Read;
            $readGb->exeRead("cweb_galeria" , "WHERE post_codigo = :codigo ORDER BY galeria_data DESC", "codigo={$trab_codigo}");
            if($readGb->getResult()):
                foreach($readGb->getResult() as $galeria):
                    $gb++;
                extract($galeria);
         ?>
  <div class="col-xs-12  col-lg-3 col-md-3 col-sm-4">
    <a href="<?= HOME; ?>/uploads/<?= $galeria_imagem; ?>" class="thumbnail" data-lightbox="image-1" data-title="Galeria de: <?= $trab_titulo ?>">
     <?= Check::Image('uploads' . DIRECTORY_SEPARATOR . $galeria_imagem, "Imagem {$gb} do post {$trab_titulo}", 171 ,180); ?>
    </a>
  </div>
    
    <?php
     endforeach;
            endif;
    ?>
  
</div>

</section>