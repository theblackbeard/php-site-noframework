<?php
$view = new View;
$tpl_ative = $view->Load('active');
$tpl_item = $view->Load('item')
?>
<div id="mt_conteudo" class="container-fluid">
 
  <!--
  <header class="mt_barra">
    sdsd
  </header>
  -->
  <div id="meuCarrossel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#meuCarrossel" data-slide-to="0" class="active"></li>
      <li data-target="#meuCarrossel" data-slide-to="1"></li>
      <li data-target="#meuCarrossel" data-slide-to="2"></li>
      <li data-target="#meuCarrossel" data-slide-to="3"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">

      <?php

        $categoria = new Read;
      $categoria->exeRead("cweb_cattrab", "LIMIT :limit" , "limit=1");
        if($categoria->getResult()):
            $cat = $categoria->getResult()[0]['ctrab_codigo'];
         endif;



      $trabalhos = new Read;
      $trabalhos->exeRead("cweb_trabalho", "WHERE trab_status = 1 AND (trab_cat_parente = :cat OR trab_categoria = :cat) ORDER BY trab_data DESC LIMIT :limit OFFSET :offset", "cat={$cat}&limit=1&offset=0");


      if (!$trabalhos->getResult()):
        MSG("Nenhum Trabalho Postado", MSG_ALERT);
      else:
        $ative = $trabalhos->getResult()[0];
        $readAtive = new Read;
        $readAtive->exeRead("cweb_cattrab", "WHERE ctrab_codigo = :parente", "parente={$ative['trab_categoria']}");
        $nomeCat = $readAtive->getResult()[0]['ctrab_nome'];
        $tituloCat = $readAtive->getResult()[0]['ctrab_titulo'];

        $ative['nomecate'] = $nomeCat;
        $ative['categoria'] = $tituloCat;
        $ative['trab_descricao'] = Check::word($ative['trab_descricao'], 90);
        $ative['datetime'] = date('Y-m-d', strtotime($ative['trab_data']));
        $ative['pubdate'] = date('d/m/Y H:i', strtotime($ative['trab_data']));
        $view->Show($ative, $tpl_ative);
        
      endif;
      ?>


      <?php
      
      $trabalhos->setPlaces("cat={$cat}&limit=3&offset=1");
      if ($trabalhos->getResult()):
        foreach ($trabalhos->getResult() as $items):
         
          $readAtive->setPlaces("parente={$items['trab_categoria']}");
          $nomeItems = $readAtive->getResult()[0]['ctrab_nome'];
          $tituloCat = $readAtive->getResult()[0]['ctrab_titulo'];

          $items['nomecate'] = $nomeItems;
          $items['categoria'] = $tituloCat;
          $items['trab_descricao'] = Check::word($items['trab_descricao'], 90);
          $items['datetime'] = date('Y-m-d', strtotime($items['trab_data']));
          $items['pubdate'] = date('d/m/Y H:i', strtotime($items['trab_data']));
          $view->Show($items, $tpl_item);
        endforeach;
      endif;
    
       
      ?>


    </div>
   


    <!-- Controls -->
    <a class="left carousel-control" href="#meuCarrossel" role="button" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#meuCarrossel" role="button" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>

</div>