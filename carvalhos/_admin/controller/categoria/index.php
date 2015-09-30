<?php

if (!class_exists('Login')):
    header('Location: ../../painel.php');
    die;
endif;

?>
<section>
  <header>
    <ol class="breadcrumb">
      <li><a href="painel.php?mt=home">Dashboard</a></li>
      <li class="active">Listagem de Categorias</li>
      <li class="pull-right"><a href="painel.php?mt=categoria/adicionar" class="label label-danger">+ Nova Categoria</a></li>
    </ol>
    <h2>Listagem de Categorias</h2>
 
  </header>
  
  <?php
  
  $empty = filter_input(INPUT_GET, 'empty', FILTER_VALIDATE_BOOLEAN);
  if ($empty):
  	MSG("Você tentou editar uma categoria inexistente", MSG_ALERT);
  endif;
  
  $created = filter_input(INPUT_GET, 'created', FILTER_VALIDATE_BOOLEAN);
  if ($created):
  	MSG("Categoria Cadastrada com Sucesso!", MSG_ACCEPT);
  endif;

  $updated = filter_input(INPUT_GET, 'updated', FILTER_VALIDATE_BOOLEAN);
  if ($updated):
  	MSG("Categoria Atualizada com Sucesso!", MSG_ACCEPT);
  endif;
  

  
   $delcat  = filter_input(INPUT_GET, 'deleta' , FILTER_VALIDATE_INT);
         if($delcat):
        require ('models/AdminCatTrab.class.php');
        $deletar = new AdminCatTrab;
        $deletar->exeDelete($delcat);
        MSG($deletar->getError()[0], $deletar->getError()[1]);
        
    endif;
    
    $readSecao = new Read;
    $readSecao->exeRead("categoria" , "WHERE subcodigo IS NULL ORDER BY titulo ASC");
    if($readSecao->getResult()):
      foreach ($readSecao->getResult() as $secao):
        extract($secao);
        
        $readTrabalhos = new Read;
        $readTrabalhos->exeRead("trabalho", "WHERE categoriapai= :parente", "parente={$codigo}");
      
        $readCategoria = new Read;
        $readCategoria->exeRead("categoria", "WHERE subcodigo = :parente", "parente={$codigo}");
        
        $contTrabalhos = $readTrabalhos->getRowCount();
        $contCategorias = $readCategoria->getRowCount();
        
    
  ?>
  
  <article class="col-md-4">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><?= $titulo ?></h3>
      </div>
      <div class="panel-body">
        Postagens <span class="badge"><?=  $contTrabalhos; ?></span> Sub-categorias <span class="badge"><?=  $contCategorias ?></span>
        <p>Panel content</p>

         <a class="btn btn-primary glyphicon glyphicon-pencil" href="painel.php?mt=categoria/atualizar&catid=<?= $codigo; ?>" title="Editar Sobre" ></a>
        <a class="btn btn-danger glyphicon glyphicon-remove" href="painel.php?mt=categoria/index&deleta=<?= $codigo; ?>" title="Excluir Sobre" ></a>
      </div>
      
      <?php 
        $readSubcat = new Read;
        $readSubcat->exeRead("categoria", "WHERE subcodigo = :subparente", "subparente={$codigo}");
        if($readSubcat->getResult()):
          foreach ($readSubcat->getResult()as $sub):
            $readSubCatTrabalhos = new Read;
            $readSubCatTrabalhos->exeRead("trabalho", "WHERE categoriafilho = :catid", "catid={$sub['codigo']}");
          
            
     ?>
      <div class="panel-footer">
         <h3 class="panel-title"><?= $sub['titulo'];?></h3>
         Postagens <span class="badge"><?= $readSubCatTrabalhos->getRowCount();?></span>
          <br><br>
         
          <a class="btn btn-primary glyphicon glyphicon-pencil" href="painel.php?mt=categoria/atualizar&catid=<?= $sub['codigo']; ?>" title="Editar Sobre" ></a>
      <a class="btn btn-danger glyphicon glyphicon-remove" href="painel.php?mt=categoria/index&deleta=<?= $sub['codigo']; ?>" title="Excluir Sobre" ></a>
      <ul class="nav navbar-nav navbar-right datadir">
       
        
      </ul>
         
      </div>
      <?php 
          endforeach;
          else:
       ?>     
          
             <div class="panel-footer">
         <h3 class="panel-title">Nenhuma Sub-Categoria</h3>
        
          <br><br>
          
          </div>
       <?php endif;
      ?>
    </div>
  </article>
  <?php
    endforeach;
    else:
      
    endif;
  
  
  ?>

</section>
	