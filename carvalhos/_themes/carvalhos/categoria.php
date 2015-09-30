<?php
if($link->getData()):
	extract($link->getData());
/*
var_dump($link->getData());
	die;
*/
else:
	header('Location: ' . HOME . DIRECTORY_SEPARATOR . '404');
endif;

?>
<section id="projetos" class="projetos">
          <div class="container">
                <div class="row">
          
               <div class="col-md-12">
               		 <a href="<?= HOME ?>" class="btn btn-primary pull-right">Página Inicial</a>
                          	<a href="<?= HOME ?>/categoria/todos-os-projetos" class="btn btn-danger pull-left">Ver todos os Trabalhos</a>
               	</div>
          		<div class="curto_espaco"></div>
          	     	
                      <div class="col-md-3">
                     	<div class="well">
                  			<ul class="list-group">
               				<li class="list-group-item">Navegação</li>   			
                   <?php 
                  	 $categories = new Read;
                 	  $categories->exeRead("categoria", "WHERE subcodigo =:sub" , "sub={$codigo}");
                		if(!$categories->getResult()):
                		$dadCategory = new Read;
                		$dadCategory->exeRead("categoria", "WHERE subcodigo IS NULL ORDER BY titulo ASC");
                		if($dadCategory->getResult()):
                			foreach ($dadCategory->getResult() as $allDads):
                		?>
                		
                		<li class="list-group-item"><a href="<?= $allDads['nome'] ?>" class="label label-danger"><b><?= $allDads['titulo'] ?></b></a></li>
                		<?php 
                			endforeach;
                		endif;
                		else:
                			foreach ($categories->getResult() as $subCat):
                	?>
                		<li class="list-group-item"><b><a href="<?= HOME ?>/categoria/<?= $subCat['nome'] ?>" class="label label-info"><?= $subCat['titulo'] ?></a></b></li>
                	<?php 
	               			endforeach;
	               		endif;                	
                	?>
                     		</ul>
                       </div>
                     </div>
                    
                    
                     
                    <div class="col-md-9">
                        <?php
                        
                        $getPage = (!empty($link->getLocal()[2]) ? $link->getLocal()[2] : 1);	
                        $paginacao = new Pager( HOME . '/categoria/' . $nome . '/');
                        $paginacao->exePager($getPage, 20);
                        
                        $works = new Read;
                        $works->exeRead("trabalho" , "WHERE status = 1 AND (categoriapai = :cat OR categoriafilho =:cat) ORDER BY data DESC LIMIT :limit OFFSET :offset", "cat={$codigo}&limit={$paginacao->getLimit()}&offset={$paginacao->getOffset()}");
                        if(!$works->getResult()):
								MSG("Nenhum Trabalho Postado" , MSG_ERROR);
						else:
								foreach ($works->getResult() as $allWorks):
									$view = new View;
									$tpl_allWorks = $view->load('allworks');
									$allWorks['descricao'] = Check::word($allWorks['descricao'], 100);
									$allWorks['datetime'] = date('Y-m-d' , strtotime($allWorks['data']));
									$allWorks['pubdate'] = date('d/m' , strtotime($allWorks['data']));
									$view->Show($allWorks, $tpl_allWorks);
											
								endforeach;
							endif;
                        
                        ?>
                     
                    </div>
                    
                    <div class="col-md-12">
                    <a href="<?= HOME ?>" class="btn btn-primary pull-right">Página Inicial</a>
                          	<a href="<?= HOME ?>/categoria/todos-os-projetos" class="btn btn-danger pull-left">Ver todos os Trabalhos</a>
                        
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
                </div>
          
        </div>
     
    </section>