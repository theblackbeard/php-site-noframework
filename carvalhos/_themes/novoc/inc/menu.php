<!-- Sidebar -->
    <div id="sidebar-wrapper">
        <div class="logo">
           <img src="<?= HOME ?>/_images/foto.jpg" class="img-circle borda" />           
        </div>
        <ul class="sidebar-nav">
         
            <li><span class="glyphicon glyphicon-home icon"></span>
                <a href="<?= HOME ?>">Home</a>
            </li>


            <li id="meuMenu"><span class="glyphicon glyphicon-th-large icon"></span>
                <a href="#" >Portfólio</a>
            </li>
                <ul class="submenu" id="menu">
                <li><a href="<?= HOME ?>/categoria/todos-os-projetos">Todos os Trabalhos</a></li>
                    <?php
                        $category = new Read;
                        $category->exeRead("categoria", "WHERE subcodigo IS NOT NULL ORDER BY titulo ASC");
                            if($category->getResult()):
                               foreach($category->getResult() as $categories):
                    ?>
                    <li>
                        <a href="<?= HOME ?>/categoria/<?= $categories['nome'] ?>"><?= $categories['titulo']; ?></a></li>
                    <?php
                        endforeach;
                        endif;
                     ?>
                </ul>

             <?php
               $posts = new Read;
               $posts->exeRead("post", "WHERE status = 1");
               if($posts->getRowCount() > 0):
             ?>
               <li id="meuPost"><span class="glyphicon glyphicon-file icon"></span>
                    <a href="#">Postagens</a>
               </li>
                    <ul class="submenu" id="post">
                      <li><a href="<?= HOME ?>/blog/todas-as-postagens">Todas as Postagens</a></li>
                        <?php
                           $category = new Read;
                           $category->exeRead("categoria_post", "WHERE subcodigo IS NOT NULL ORDER BY titulo ASC");
                           if($category->getResult()):
                               foreach($category->getResult() as $categories):
                        ?>
                              <li><a href="<?= HOME ?>/blog/<?= $categories['nome'] ?>"><?= $categories['titulo']; ?></a></li>

                        <?php
                         endforeach;
                         endif;
                        ?>
                    </ul>
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
            <li><span class="glyphicon glyphicon-user icon"></span>
                <a href="<?= HOME ?>/_admin">Login</a>
            </li>

        </ul>

        <div class="rodapeSocial"></div>
    </div>
    <!-- /#sidebar-wrapper -->