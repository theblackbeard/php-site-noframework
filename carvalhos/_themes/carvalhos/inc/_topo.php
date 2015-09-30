
<!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand page-scroll" href="#topo">Carvalhos</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav">
                    <!-- Hidden li included to remove active class from about link when scrolled up past about section -->
                    <li class="hidden">
                        <a class="page-scroll" href="#topo"></a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#sobre">Sobre</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#portfolio">Porfólio</a>
                    </li>

                    <?php
                        $posts = new Read;
                        $posts->exeRead("post", "WHERE status = 1");
                        if($posts->getRowCount() > 0):
                    ?>
                    <li>
                        <a class="page-scroll" href="#postagem">Postagens</a>
                    </li>

                    <?php
                        endif;
                        $taverna = new Read;
                        $taverna->exeRead("taverna");
                        if($taverna->getRowCount() > 0):
                    ?>
                    <li>
                        <a class="page-scroll" href="<?= HOME ?>/taverna/pessoal">Taverna</a>
                    </li>

                        <?php
                            endif;
                        ?>
                    <li>
                        <a class="page-scroll" href="#contato">Contato</a>
                    </li>
                    
                    
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="<?= HOME ?>/categoria/todos-os-projetos" title="Acesse todos meus trabalhos"><span class="label label-danger ">TODOS MEUS TRABALHOS</span></a></li>
                    
                    <li><a href="https://github.com/mtcarvalhos" target="_blank" title="Acesse o meu git"><span class="label label-info ">Acesse o meu Github</span></a></li>
                    
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Ultimos Projetos <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php
				$moreWorks = new Read;
				$moreWorks->exeRead("trabalho" , "WHERE status = 1 ORDER BY titulo ASC LIMIT :limit" , "limit=6");
				if(!$moreWorks->getResult()):
				?>	
					<li>Nenhum Trabalho Criado</li>
				<?php 
					else:	
					foreach ($moreWorks->getResult() as $mWorks):
            ?>
            <li><a href="<?= HOME ?>/trabalho/<?= $mWorks['nome']?>"><?= $mWorks['titulo'] ?></a></li>
           <?php 
           		endforeach;
           		endif;
           ?>
            <li class="divider"></li>
            <li><a href="<?= HOME ?>/categoria/todos-os-projetos">Listar Todos</a></li>
          </ul>
        </li>
         
         
            <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Login<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php
            
            
            
          $login = new Login(1);
          if(!$login->checkLogin()):
            $formulario = false;
            unset($_SESSION['userlogin']);
          else:
          	 $formulario = true;
          	 $userlogin = ($_SESSION['userlogin']);  
          endif;
          
          $dataLogin = filter_input_array(INPUT_POST, FILTER_DEFAULT);
          if(!empty($dataLogin['LoginForm'])):
             
        
          $login->exeLogin($dataLogin);
           if(!$login->getResult()):
                MSG($login->getError()[0], $login->getError()[1]);
              else: 
              $formulario = true;
               header('Location: ' . HOME);
              //$userlogin = ($_SESSION['userlogin']);
           	endif;
           	
           endif;
           
           if($formulario):?>
              <div class="col-md-12">
              	
               Olá <?= $userlogin['nome'] ?> <?= $userlogin['sobrenome']?>
              
               </div>
                
               <li><a href="<?= HOME ?>/?logoff=true">Sair do Sistema</a></li>
               
           <?php endif;	?>   
            <?php
            	if(!$formulario):
            ?>
            <form class="navbar-form espform" action="#" method="post">
            <div class="form-group">
      <input type="text" placeholder="E-mail" name="email" class="form-control">
            </div>
            
            <div class="form-group">
      <input type="password" placeholder="Senha" name="senha" class="form-control">
            </div>
            
    		<input type="submit" class="btn btn-primary" value="Entrar" name="LoginForm" />
          </form>
          <?php 
          	endif;
          ?>
            <li class="divider"></li>
           <li><a href="<?= HOME ?>/admin">Area Administrativa</a></li>
          </ul>
        </li>
         
         
         
      </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
   