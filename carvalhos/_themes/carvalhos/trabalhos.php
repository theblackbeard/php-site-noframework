<?php

	$getPage = filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT);
	$paginacao = new Pager( HOME . '/trabalhos&pagina=', '&laquo;', '&raquo;');
	$paginacao->exePager($getPage, 20);
	
	
	$works = new Read;
	$works->exeRead("trabalho" , "WHERE status = 1 ORDER BY data DESC LIMIT :limit OFFSET :offset", "limit={$paginacao->getLimit()}&offset={$paginacao->getOffset()}");
	$posts = $works->getRowCount();
	

						
?>
<section id="projetos" class="projetos">
       <div class="col-md-12">
       <h1>Listagem de Trabalhos</h1>
       </div>
       		
       		<div class="col-md-2">
       		
       		</div>
           <div class="col-md-10" id="works">
                       
                        <?php
							if(!$works->getResult()):
								MSG("Nenhum Trabalho Postado" , MSG_ERROR);
							else:
								foreach ($works->getResult() as $allWorks):
								
									$category = new Read;
									$category->exeRead("categoria" , "WHERE subcodigo= :cod", "cod={$allWorks['categoriafilho']}");
									//$category->getResult()['nome'];
								
									
									
									$view = new View;
									$tpl_allWorks = $view->load('allworks');
									$allWorks['descricao'] = Check::word($allWorks['descricao'], 100);
									$allWorks['datetime'] = date('Y-m-d' , strtotime($allWorks['data']));
									$allWorks['pubdate'] = date('d/m' , strtotime($allWorks['data']));
									
									
									//$allWorks['nomeCategory'] = $subCat['nome'];
									$view->Show($allWorks, $tpl_allWorks);
											
								endforeach;
							endif;
						
                        ?>
                     
                    </div>
                    
                    <div class="col-md-12">
                    <h1><a href="<?= HOME;?>" class="btn btn-danger pull-left">Voltar para o Site</a></h1>
                
                
                    <nav>
  
  <ul class="pagination pull-right">
    <?php 
    $paginacao->returnPage();
    	$paginacao->exePaginator("trabalho");
    echo $paginacao->getPaginator();
    
    ?>
  </ul>
</nav>
                    </div>           
                  
                    
                </div>
          
        </div>
     
    </section>