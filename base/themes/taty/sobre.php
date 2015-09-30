<?php
 if($link->getData()):
        extract($link->getData());
    else:
        header('Location: ' . HOME . DIRECTORY_SEPARATOR . '404');
    endif;

?>

<div id="mt_conteudo" class="container-fluid">

    <?php
        $sobre = new Read;
        $sobre->exeRead("cweb_sobre");
        if(!$sobre->getResult()):
            MSG('Nenhuma Pagina Sobre foi Incluida' , MSG_INFO);
        else:
            $ativoSobre = $sobre->getResult()[0];
            $view = new View;
            $tpl_sobre = $view->Load('sobre');
            $ativoSobre['datetime'] = date('Y-m-d', strtotime($ativoSobre['sobre_data']));
            $ativoSobre['pubdate'] = date('d/m/Y H:i', strtotime($ativoSobre['sobre_data']));

            $view->Show($ativoSobre, $tpl_sobre);

        endif;

    ?>
</div>