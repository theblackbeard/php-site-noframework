<?php

if($link->getData()):
extract($link->getData());

 //var_dump($link->getData());
 

else:
header('Location: ' . HOME . DIRECTORY_SEPARATOR . '404');
endif;

$view = new View;
$tpl_views = $view->load('viewwork');
$tpl_gallery = $view->Load('gallery');
$tpl_relations = $view->Load('relations');
?>

 <section id="projetos" class="trabesp">
            <div class="container">
                <div class="row">
               	<div class="col-md-12">
               		 <a href="<?= HOME ?>" class="btn btn-primary pull-right">Página Inicial</a>
                          	<a href="<?= HOME ?>/categoria/todos-os-projetos" class="btn btn-danger pull-left">Ver todos os Trabalhos</a>
               	</div>
               
               
                		<!--  Primeira View -->
                		<?php 
                		
                	
                		$view->Show($link->getData(), $tpl_views);

                		$gallery = new Read;
                		$gallery->exeRead("galeria" , "WHERE trabalho = :codigo ORDER BY data DESC", "codigo={$codigo}");
                		if($gallery->getResult()):?>

                        <div class="col-md-12">
                        <div class="well">

                            <h3>Galeria de Foto</h3>
                            <div class="row">
                        <?php
                			foreach ($gallery->getResult() as $photos):
                				extract($photos);
                				$photos['gallery'] = $imagem;
                				$view->Show($photos, $tpl_gallery);
                		endforeach;
                        ?>
                            </div>
                                </div>
                                     </div>
                        <?php
                	   	endif;
                		?>
                		<!-- Fim da Primeira View -->

                  
                  
                            
                            <div class="col-md-12">
                                <div class="well">
                                
                                    <h3>Compartilhe</h3>
                                 <div class="row">
                                    <span class='st_linkedin_hcount' displayText='LinkedIn'></span>
<span class='st_twitter_hcount' displayText='Tweet'></span>
<span class='st_facebook_hcount' displayText='Facebook'></span>
<span class='st_googleplus_hcount' displayText='Google +'></span>
<span class='st_email_hcount' displayText='Email'></span>
<span class='st_sharethis_hcount' displayText='ShareThis'></span>
</div>
                                </div>
                            </div>
                           
											
												<?php 
													$relations = new Read;
													$relations->exeRead("trabalho", "WHERE status = 1 AND codigo != :id AND categoriafilho = :cat ORDER BY RAND() LIMIT 10", "id={$codigo}&cat={$categoriafilho}");
          											if($relations->getResult()):
          											?>
          											 <div class="col-md-12">
                               							 <div class="well">
                                   							 <h3>Projetos Relacionados</h3>
                                   							 	<ul class="list-group">
          											<?php 
														foreach ($relations->getResult() as $allRelations):
          													$view->Show($allRelations, $tpl_relations);
          												endforeach;
          											endif;
												
												?>
											
																</ul>			
                                    						</div>
                           							 </div>
                            <a href="<?= HOME ?>" class="btn btn-primary pull-right">Página Inicial</a>
                          	<a href="<?= HOME ?>/categoria/todos-os-projetos" class="btn btn-danger pull-left">Ver todos os Trabalhos</a>
                        </div>
                    </div>
             

        </section>

