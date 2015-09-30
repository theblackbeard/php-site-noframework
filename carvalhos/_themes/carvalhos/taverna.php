<?php
if($link->getData()):
    extract($link->getData());
    var_dump($link->getData());
else:
    header('Location: ' . HOME . DIRECTORY_SEPARATOR . '404');
endif;
//require('../_app/Config.inc.php');
$login = new Login(1);
if(!$login->checkLogin()):
    unset($_SESSION['userlogin']);
    header('Location: ' . HOME . '/admin' );
endif;
?>
<section id="projetos" class="projetos">
          <div class="container">
              <div class="row">

               <div class="col-md-12">
               		 <a href="<?= HOME ?>?logoff=true" class="btn btn-primary pull-right">Sair</a>
                          	<a href="<?= HOME ?>/taverna/pessoal" class="btn btn-danger pull-left">Todas as Postagens</a>
               	</div>
          		<div class="curto_espaco"></div>

                      <div class="col-md-3">
                     	<div class="well">
                  			<ul class="list-group">
               				<li class="list-group-item">Navegação</li>
                            <?php
                                $tavCat = new Read;
                                $tavCat->exeRead("categoria_tav" , "WHERE subcodigo =:sub", "sub={$codigo}" );
                                if(!$tavCat->getResult()):
                                    $dadCategory = new Read;
                                    $dadCategory->exeRead("categoria_tav", "WHERE subcodigo IS NULL ORDER BY titulo ASC");
                                    if($dadCategory->getResult()):
                                            foreach($dadCategory->getResult() as $allDads):
                                    ?>
                                        <li class="list-group-item"><a href="<?= $allDads['nome'] ?>" class="label label-info"><b><?= $allDads['titulo'] ?></b></a></li>
                                    <?php
                                            endforeach;
                                    endif;
                                    else:
                                        foreach($tavCat->getResult() as $subCat):

                                    ?>
                                            <li class="list-group-item"><b><a href="<?= HOME ?>/taverna/<?= $subCat['nome'] ?>" class="label label-info"><?= $subCat['titulo'] ?></a></b></li>

                                        <?php
                                        endforeach;
                                    endif;
                            ?>
                            </ul>
                        </div>
                      </div>
                 </div>

          </div>

</section>