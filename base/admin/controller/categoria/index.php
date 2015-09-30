<?php
if (!class_exists('Login')):
    header('Location: ../../painel.php');
    die;
endif;

$empty = filter_input(INPUT_GET, 'empty', FILTER_VALIDATE_BOOLEAN);
if ($empty):
    MSG("Você tentou editar uma categoria inexistente", MSG_INFO);
endif;

$created = filter_input(INPUT_GET, 'created', FILTER_VALIDATE_BOOLEAN);
if ($created):
	MSG("Categoria criada com sucesso!", MSG_ACCEPT);
endif;

$updated = filter_input(INPUT_GET, 'updated', FILTER_VALIDATE_BOOLEAN);
if ($updated):
	MSG("Categoria atualizada com sucesso!", MSG_ACCEPT);
endif;


?>

<section>
  <header>
    <ol class="breadcrumb">
      <li><a href="painel.php?mt=home">Dashboard</a></li>
      <li class="active">Listagem de Categorias</li>
    </ol>
    <h2>Listagem de Categorias</h2>
  </header>
  <?php
   $delcat  = filter_input(INPUT_GET, 'deleta' , FILTER_VALIDATE_INT);
         if($delcat):
        require ('_models/AdminCatTrab.class.php');
        $deletar = new AdminCatTrab;
        $deletar->exeDelete($delcat);
        MSG($deletar->getError()[0], $deletar->getError()[1]);
        
    endif;
    
    $readSecao = new Read;
    $readSecao->exeRead("cweb_cattrab" , "WHERE ctrab_parente IS NULL ORDER BY ctrab_titulo ASC");
    if($readSecao->getResult()):
      foreach ($readSecao->getResult() as $secao):
        extract($secao);
        
        $readTrabalhos = new Read;
        $readTrabalhos->exeRead("cweb_trabalho", "WHERE trab_cat_parente = :parente", "parente={$ctrab_codigo}");
      
        $readCategoria = new Read;
        $readCategoria->exeRead("cweb_cattrab", "WHERE ctrab_parente = :parente", "parente={$ctrab_codigo}");
        
        $contTrabalhos = $readTrabalhos->getRowCount();
        $contCategorias = $readCategoria->getRowCount();
        
    
  ?>
  
  <article class="col-md-4">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><?= $ctrab_titulo ?></h3>
      </div>
      <div class="panel-body">
        Postagens <span class="badge"><?=  $contTrabalhos; ?></span> Sub-categorias <span class="badge"><?=  $contCategorias ?></span>
        <p>Panel content</p>

        <h4>Data:  <strong><?= date('d/m/Y H:i', strtotime($ctrab_data)) ?></strong></h4>
        <a class="btn btn-primary glyphicon glyphicon-pencil" href="painel.php?mt=categoria/atualizar&catid=<?= $ctrab_codigo; ?>" title="Editar Sobre" ></a>
        <a class="btn btn-danger glyphicon glyphicon-remove" href="painel.php?mt=categoria/index&deleta=<?= $ctrab_codigo; ?>" title="Excluir Sobre" ></a>
      </div>
      
      <?php 
        $readSubcat = new Read;
        $readSubcat->exeRead("cweb_cattrab", "WHERE ctrab_parente = :subparente", "subparente={$ctrab_codigo}");
        if($readSubcat->getResult()):
          foreach ($readSubcat->getResult()as $sub):
            $readSubCatTrabalhos = new Read;
            $readSubCatTrabalhos->exeRead("cweb_trabalho", "WHERE trab_categoria = :catid", "catid={$sub['ctrab_codigo']}");
          
            
     ?>
      <div class="panel-footer">
         <h3 class="panel-title"><?= $sub['ctrab_titulo'];?></h3>
         Postagens <span class="badge"><?= $readSubCatTrabalhos->getRowCount();?></span>
          <br><br>
         
          <a class="btn btn-primary glyphicon glyphicon-pencil" href="painel.php?mt=categoria/atualizar&catid=<?= $sub['ctrab_codigo']; ?>" title="Editar Sobre" ></a>
      <a class="btn btn-danger glyphicon glyphicon-remove" href="painel.php?mt=categoria/index&deleta=<?= $sub['ctrab_codigo']; ?>" title="Excluir Sobre" ></a>
      <ul class="nav navbar-nav navbar-right datadir">
        <li> Data:<strong><?= date('d/m/Y H:i', strtotime($sub['ctrab_data'])) ?></strong></li>
        
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