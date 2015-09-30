<?php require(REQUIRE_PATH . '/inc/_topo.php');


?>
<!-- Intro Section -->
    <section id="topo" class="topo">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 ">
                    <h1>Carlos Mateus Carvalho</h1>
                    <h2>Ciências da Computação</h2>
                    <a class="btn btn-primary page-scroll" href="#sobre" >Clique aqui para descer!</a>
                    <br>
                    <div class="row redes" >

                    <div class="col-md-8 col-md-offset-4 ">

                        <div class="col-md-1"><a href="https://br.linkedin.com/in/carvalhosti" title="Acessar meu linkedin" target="_blank"><img src="<?= HOME ?>/_images/linkedin.png" /></a></div>
                        <div class="col-md-1 "><a href="https://twitter.com/ticarvalhos" title="Acessar meu Twitter" target="_blank"><img src="<?= HOME ?>/_images/twitter.png" /></a></div>
                        <div class="col-md-1 "><a href="https://plus.google.com/u/0/+CMCarvalho/about" title="Acessar meu Google Plus" target="_blank"><img src="<?= HOME ?>/_images/googleplus.png" /></a></div>
                        <div class="col-md-1 "><a href="#" title="Acessar meu Email"  data-toggle="modal" data-target="#bannerformmodal" ><img src="<?= HOME ?>/_images/email.png" /></a></div>
                        <div class="col-md-1 "><a href="#" title="Acessar meu Skype"  data-toggle="modal" data-target="#skype"><img src="<?= HOME ?>/_images/skype.png" /></a></div>


                    </div>

                        <!-- -->
                        <div class="modal fade bannerformmodal" tabindex="-1" role="dialog" aria-labelledby="bannerformmodal" aria-hidden="true" id="bannerformmodal">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Meu Email</h4>
                                    </div>
                                    <div class="modal-body">
                                        Email: carvalho.ti.adm@gmail.com
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>

                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- -->

                        <!-- -->
                        <div class="modal fade bannerformmodal" tabindex="-1" role="dialog" aria-labelledby="skype" aria-hidden="true" id="skype">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Meu Skype</h4>
                                    </div>
                                    <div class="modal-body">
                                        Skype: @devcarvalhos
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>

                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- -->

                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="sobre" class="sobre">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <?php 
                    	$myAbout = new Read;
                    	$myAbout->exeRead("sobre");
                    	if($myAbout->getResult()):
                    		foreach ($myAbout->getResult() as $about):
                    			
                    	
                    
                    ?>
                    
                    <h1>Sobre</h1>
                
                    <div class="col-md-8">
                        <div class="well">
                            <h3><?= $about['titulo'] ?></h3>
                              <p><?= $about['descricao'] ?></p>
                        </div>
                    </div>
                    
                  
                     <div class="col-md-4">
                        <div class="well">
                        	<h3>Sobre esse site</h3>
                        	<p>Esse site foi criado em PHP, sem uso de framework e com conceito de MVC, Orientação a objetos e Bootstrap, o mesmo tem 
                        	o objetivo acadêmico, coisas que aprendi no decorrer dos estudos na universiade e autoditada. </p>
                        </div>
                    </div>
                    <?php
					endforeach;
                    endif; ?>
                    
                </div>
            </div>
        </div>
     
    </section>

    <!-- Services Section -->
    <section id="portfolio" class="portfolio">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1>Ultimos Projetos</h1>
                    
                   
                    
                    <div class="row">
                    	<?php 
                    		$myWorks = new Read;
                    		$myWorks->exeRead("trabalho" ,  "WHERE status = 1 ORDER BY data DESC LIMIT :limit", "limit=4");
                    		if(!$myWorks->getResult()):
                    			MSG("Nenhum trabalho cadastrado", MSG_INFO);
                    		else:
                    		foreach ($myWorks->getResult() as $works):
                    		
                    		$view = new View;
                    		$tpl_works = $view->Load('works');
                    		$works['titulo']= Check::word($works['titulo'], 3);
                    		$works['descricao'] = Check::word($works['descricao'], 10);
                    		$view->Show($works, $tpl_works);
                    		endforeach;
                    		endif;
                    	?>
  
                        <div class="centro"><a href="<?= HOME ?>/categoria/todos-os-projetos" class="btn btn-danger btn-sm">Abrir Todos os Projetos</a></div>
                    
                    
                </div>
            </div>
        </div>
    </section>


<?php
$lastPost = new Read;
$lastPost->exeRead("post", "WHERE status = 1 ORDER BY datacad DESC LIMIT 1");
if($lastPost->getResult()):
    $foto = $lastPost->getResult()[0]['foto'];
    $titulo = $lastPost->getResult()[0]['titulo'];
    $nome = $lastPost->getResult()[0]['nome'];
    $descricao = $lastPost->getResult()[0]['descricao'];
    $descricao = Check::word($descricao, 80);
?>
    <!-- Postagem Section -->
<section id="postagem" class="sobre">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1>Ultima Postagem</h1>

                    <div class="col-lg-12">
                        <div class="well">

                            <img alt="#titulo#" title="#titulo#" src="<?= HOME ?>/tim.php?src=<?= HOME ?>/uploads/<?= $foto ?>&w=1100&h=180" class="img-responsive"/>
                            <h4><?= $titulo ?></h4>
                            <p><?= $descricao ?></p>

                            <p><a class="btn btn-primary" href="<?= HOME ?>/post/<?= $nome ?>">Mais Detalhes</a> </p>



                        </div>
                    </div>




                <div class="row"> <div class="centro"><a href="<?= HOME ?>/blog/todas-as-postagens" class="btn btn-danger btn-sm">Abrir Todas as Postagens</a></div></div>
            </div>
        </div>
    </div>
</section>
<?php
endif;
?>
    <!-- Fim de Postagem Section-->
    
    <!-- Contact Section -->
    <section id="contato" class="contato">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="fonte">Contato</h1>
                 
                    <form action="#contato" name="EnviarContatoForm" method="post">
                     <?php

            $contato = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            if(isset($contato) && $contato['EnviarContato']):
                unset($contato['EnviarContato']);
                $contato['assunto'] = "Mensagem via site";
                $contato['destinoNome'] = "Carlos Mateus Carvalho";
                $contato['destinoEmail'] = 'carvalho.ti.adm@gmail.com';

                $email = new Email;
                $email->enviar($contato);
                if($email->getErro()):
                    MSG($email->getErro()[0], $email->getErro()[1]);
                endif;
            endif;

            ?>
  <div class="form-group">
    <label for="nome">Nome:</label>
    <input type="text" class="form-control" id="nome" name="remetenteNome" value="<?php if(isset($contato['remetenteNome'])) echo $contato['remetenteNome'];?>" placeholder="Coloque seu nome...">
  </div>
  <div class="form-group">
    <label for="email">E-Mail:</label>
    <input type="email" class="form-control" id="email" name="remetenteEmail" value="<?php if(isset($contato['remetenteEmail'])) echo $contato['remetenteEmail'];?>"  placeholder="Coloque seu e-mail...">
  </div>
  <div class="form-group">
    <label for="mensagem">Mensagem:</label>
    <textarea class="form-control" id="mensagem" name="mensagem" rows="8" placeholder="Coloque sua mensagem..."><?php if(isset($contato['mensagem'])) echo $contato['mensagem'];?></textarea>
  </div>
  
   <input type="submit" name="EnviarContato" class="btn btn-primary" value="Enviar"> </input>
</form>
                        
                    </div>
                    
                    
               
            </div>
      
    </section>