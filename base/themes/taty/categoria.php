<?php
 if($link->getData()):
        extract($link->getData());
 		//var_dump($link->getData());
 	
 
    else:
        header('Location: ' . HOME . DIRECTORY_SEPARATOR . '404');
    endif;

?>
<div  id="categoria" class="container">
 
  
  <header>
     <?php
                $getPage = (!empty($link->getLocal()[2]) ? $link->getLocal()[2] : 1);
                $pager = new Pager(HOME .'/categoria/' . $ctrab_nome . '/');
                $pager->exePager($getPage, 4);

                $readCat = new Read;
                $readCat->exeRead("cweb_trabalho" , "WHERE trab_status = 1 AND (trab_categoria =:cat OR trab_cat_parente =:cat) ORDER BY trab_data DESC LIMIT :limit OFFSET :offset" , "cat={$ctrab_codigo}&limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                if(!$readCat->getResult()):
                    $pager->returnPage();
                    MSG("Não há trabalhos cadastrados" , MSG_INFO);
                else:
                    $view = new View;
                    $tpl_cat = $view->Load('categoria');

                    foreach($readCat->getResult() as $cat):
                        $cat['trab_titulo'] = Check::word($cat['trab_titulo'], 6);
                        $cat['trab_descricao']  = Check::word($cat['trab_descricao'], 15);
						$cat['datetime'] = date('Y-m-d' , strtotime($cat['trab_data']));
						$cat['pubdate'] = date('d/m H:i' , strtotime($cat['trab_data']));
                        $view->Show($cat, $tpl_cat);
                    endforeach;
                endif;
            ?>
  </header>
  <div class="espaco"></div>
 <div class="pagination pull-right">
            <?php

            $pager->exePaginator("cweb_trabalho", "WHERE trab_status = 1 AND (trab_categoria =:cat OR trab_cat_parente =:cat)" , "cat={$ctrab_codigo}");
            echo $pager->getPaginator();

            ?>

        </div>
</div>