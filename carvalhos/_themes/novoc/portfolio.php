 <!-- Page Content -->
    <div id="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h1>Meus Últimos Trabalhos</h1>
                    <h2>Web Developer</h2>
                    <hr>
                    <div id="conteudo">

                    <?php


                        $myWork = new Read;
                        $myWork->exeRead("trabalho" ,  "WHERE status = 1 ORDER BY data DESC LIMIT :limit", "limit=10");
                           if(!$myWork->getResult()):
                               MSG("Nenhum trabalho cadastrado", MSG_INFO);
                           else:
                               foreach ($myWork->getResult() as $works):
                                 $works['descricao'] = Check::word($works['descricao'], 60);
                                 $view = new View;
                                 $tpl_work = $view->Load('ultimos_trabalhos');
                                 $view->Show($works, $tpl_work);
                               endforeach;
                           endif;
                    ?>

                   <!-- <a href="#menu-toggle" class="btn btn-default" id="menu-toggle">Toggle Menu</a>-->
                    </div>
                    </div>
            </div>
        </div>
    </div>
<!-- /#page-content-wrapper -->