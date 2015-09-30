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
            <li class="active">Nova Postagem</li>
        </ol>
        <h2>Nova Postagens</h2>
        <p>Coloque aqui infomações do sua postagem</p>
    </header>
    <?php
    $trabalho = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if(isset($trabalho) && $trabalho['EnviarTrabalho']):
        $trabalho['status'] = ($trabalho['EnviarTrabalho'] == 'Cadastrar' ? '0' : '1');
        $trabalho['foto'] = ($_FILES['foto']['tmp_name'] ? $_FILES['foto'] : null);
        unset($trabalho['EnviarTrabalho']);
        require('models/AdminPost.class.php');
        $cadastra = new AdminPost;
        $cadastra->exeCreate($trabalho);
        if($cadastra->getResult()):
        	 header('Location: painel.php?mt=post/atualizar&created=true&postId=' . $cadastra->getResult());
        else:
            MSG($cadastra->getError()[0], $cadastra->getError()[1]);
        endif;
    endif;


    ?>
    
    
    <form action="#" method="post" name="EnviarTrabalhoForm" enctype="multipart/form-data">
        <div class="form-group">
            <label for="exampleInputFile">Foto de Capa</label>
            <input type="file" id="exampleInputFile" name="foto">
        </div>
        <div class="form-group">
            <label for="titulo">Titulo:</label>
            <input type="text" class="form-control" id="titulo" name="titulo" value="<?php if(isset($trabalho['titulo'])) echo $trabalho['titulo']?>" placeholder="Insira um Titulo" required autofocus>
        </div>
        <section>
            <ul class="nav nav-tabs">

  <li role="presentation" class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false" >
      Icones <span class="caret"></span>
    </a>
    <ul class="dropdown-menu" role="menu">
       <li role="presentation"><a href="#" class="glyphicon glyphicon-asterisk" id="1"></a></li>
    </ul>
  </li>
 <li role="presentation"><a href="#" data-toggle="modal" data-target=".bs-example-modal-lg">Icones</a></li>
</ul>
            
        </section> 
        <br>
        <div class="form-group">
            <label for="descricao">Descrição:</label>
            <textarea class="form-control" id="descricao" name="descricao" rows="15"><?php if(isset($trabalho['descricao'])) echo $trabalho['descricao']?></textarea>
        </div>

          <div class="form-group">
            <label for="data"> <b>Modificado em:</b></label>
            <input type="text" class="form-control" id="data" name="dataup" value="<?= date('d/m/Y H:i:s') ?>" required autofocus>
         </div>
           <div class="form-group">
            <label for="data">Data de Inclusão:</label>
            <input type="text" class="form-control" id="data" name="datacad" value="<?php if (isset($trabalho['datacad'])): echo $trabalho['datacad'];
            else: echo date('d/m/Y H:i:s');
            endif; ?>" required autofocus>
            </div>
           <div class="form-group">
            <label for="catsec">Categoria:</label>
            <select class="form-control" id="catsec" name="categoriafilho">
                <option value="null">Selecione uma Categoria</option>
                <?php
                $readSes = new Read;
                $readSes->exeRead("categoria_post", "WHERE subcodigo IS NULL ORDER BY titulo ASC");
                if($readSes->getRowCount() >= 1):
                    foreach ($readSes->getResult() as $ses):
                        echo "<option disabled=\"disable\" value=\"\"><b> Seção: {$ses['titulo']}</b></option>";
                        $readCat = new Read;
                        $readCat->exeRead("categoria_post", "WHERE subcodigo = :parente ORDER BY titulo ASC", "parente={$ses['codigo']}");
                        if($readCat->getRowCount() >= 1):
                            foreach ($readCat->getResult() as $cat):
                                echo " <option ";
                                if($trabalho['categoriafilho'] == $cat['titulo']):
                                    echo "selected=\"selected\" ";
                                endif;
                                echo "value=\"{$cat['codigo']}\">{$cat['titulo']}</option>";
                            endforeach;
                        endif;
                    endforeach;
                endif;

                ?>
            </select>


        </div>
               

        <br>
    

        <hr>



        <br>
        <input type="submit" class="btn btn-primary" name="EnviarTrabalho" value="Cadastrar" />
        <input type="submit" class="btn btn-info" name="EnviarTrabalho" value="Cadastrar e Publicar" />

    </form>
    <div class="branco"></div>
</section>

<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      ...
    </div>
  </div>
</div>