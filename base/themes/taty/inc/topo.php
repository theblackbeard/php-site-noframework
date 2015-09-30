<?php
    $readSobre = new Read;
    $readSobre->exeRead("cweb_sobre");
    if($readSobre->getResult()):
        $menuSobre = $readSobre->getResult()[0]['sobre_nome'];

    endif;

    $readCont = new Read;
    $readCont->exeRead("cweb_contato");
    if($readCont->getResult()):
        $menuCont = $readCont->getResult()[0]['cont_nome'];
endif;




?>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand titulo" href="<?= HOME ?>">Tatiana S. Santana


            </a>
            <br>

            <h3 class="sub_titulo">Designer de Interiores</h3>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="<?= HOME ?>">INICIAL</a></li>
                <li><a href="<?= HOME ?>/sobre/<?= $menuSobre; ?>">SOBRE</a></li>

                <li class="dropdown">



                <a href="#" class="dropdown-toggle js-activated" data-toggle="dropdown" role="button" aria-expanded="false">PORTFÓLIO <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">


                        <?php
                            $trabalhosSecao = new Read;
                            $trabalhosSecao->exeRead("cweb_cattrab" , "WHERE ctrab_parente IS NULL ORDER BY ctrab_titulo ASC");
                            if($trabalhosSecao->getResult()):
                                foreach($trabalhosSecao->getResult() as $trabalhos):
                                extract($trabalhos)
                        ?>
                                <li><a href="<?= HOME;?>/categoria/<?= $ctrab_nome; ?>"><strong><?= $ctrab_titulo ?></strong></a></li>
                        <?php



                            $subTrabalho = new Read;
                            $subTrabalho->exeRead("cweb_cattrab" , "WHERE ctrab_parente = :sub" , "sub={$ctrab_codigo}");
                                if($subTrabalho->getRowCount()):
                                    foreach($subTrabalho->getResult() as $subCat):
                                ?>

                                <li class="linkmenu"> <a href="<?= HOME ?>/categoria/<?= $subCat['ctrab_nome']?>" title="<?= $subCat['ctrab_titulo']?>" ><?= $subCat['ctrab_titulo']; ?></a> </li>



                        <?php
                          endforeach;

                                endif
                       ?>

                        <?php
                            endforeach;
                        endif;
                        ?>


                    </ul>
                </li>

                <li><a href="<?= HOME ?>/contato/<?= $menuCont; ?>">CONTATO</a></li>
                <li><a href="<?= HOME ?>/admin" target="_blank">LOGIN</a></li>



            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>