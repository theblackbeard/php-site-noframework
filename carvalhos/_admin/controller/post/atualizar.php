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
            <li class="active">Atualizar Postagem</li>
        </ol>
        <h2>Atualizar Postagens</h2>
        <p>Coloque aqui infomações do sua postagem</p>
    </header>
    <?php
    
      $created = filter_input(INPUT_GET, 'created' , FILTER_VALIDATE_BOOLEAN);
    if($created):
        MSG("Postagem Incluida Com Sucesso!", MSG_ACCEPT);
    endif;
    
    $postId = filter_input(INPUT_GET, 'postId' , FILTER_VALIDATE_INT);
    $trabalho = filter_input_array(INPUT_POST, FILTER_DEFAULT); 
    
     
    
    if(isset($trabalho) && $trabalho['EnviarTrabalho']):
	        $trabalho['status'] = ($trabalho['EnviarTrabalho'] == 'Atualizar' ? '0' : '1');
	        $trabalho['foto'] = ($_FILES['foto']['tmp_name'] ? $_FILES['foto'] : 'null');
	        unset($trabalho['EnviarTrabalho']);
	        require('models/AdminPost.class.php');
	        $atualiza = new AdminPost;
	        $atualiza->exeUpdate($postId, $trabalho);
	
	
	        if($atualiza->getResult()):
	             header('Location: painel.php?mt=post/index&updated=true');
	        else:
	            MSG($atualiza->getError()[0], $atualiza->getError()[1]);
	        endif;
	       
	    else:
	        $read = new Read;
	        $read->exeRead("post", "WHERE codigo = :id", "id={$postId}");
	        if(!$read->getResult()):
	            header('Location: painel.php?mt=post/index&empty=true');
	        else:
	            $atualiza =$read->getResult()[0];
	            $atualiza['datacad'] = date('d/m/Y H:i:s' , strtotime($atualiza['datacad']));
	        endif;
	 
    endif;



    ?>
    <form action="#" method="post" name="EnviarTrabalhoForm" enctype="multipart/form-data">
        <div class="form-group">
            <label for="exampleInputFile">Foto de Capa</label>
            <input type="file" id="exampleInputFile" name="foto">
        </div>
        <div class="form-group" id="descricao">
            <label for="titulo">Titulo:</label>
            <input type="text" class="form-control" id="titulo" name="titulo" value="<?php if(isset($atualiza['titulo'])) echo $atualiza['titulo']?>" placeholder="Insira um Titulo" required autofocus>
        </div>

        <div class="form-group">
            <label for="titulo">Tipo:</label>
            <input type="text" class="form-control" id="titulo" name="tipo" value="<?php if(isset($atualiza['tipo'])) echo $atualiza['tipo']?>" placeholder="Insira o Tipo da Postagem" required autofocus>
        </div>

        <div class="form-group" >
            <label for="descricao">Descrição: </label><a class="label label-success pull-right">Salvar</a>
            <textarea class="form-control" id="descricao" name="descricao" rows="15"><?php if(isset($atualiza['descricao'])) echo $atualiza['descricao']?></textarea>
        </div>

        <div class="form-inline">

            <label for="data">Incluido em:</label>
            <input type="text" class="form-control" id="data" name="datacad" value="<?php if(isset($atualiza['datacad'])):  echo $atualiza['datacad']; endif ?>"

            <label for="data"> <b>Modificado em:</b></label>
            <input type="text" class="form-control" id="data" name="dataup" value="<?= date('d/m/Y H:i:s') ?>" required autofocus>


            <label for="catsec">Categoria:</label>
            <select class="form-control" id="catsec" name="categoriafilho">
                <option value="null">Selecione uma Categoria</option>
                <?php
                $readSes = new Read;
                $readSes->exeRead("categoria_post", "WHERE subcodigo IS NULL ORDER BY titulo ASC");
                if($readSes->getRowCount() >= 1):
                    foreach ($readSes->getResult() as $ses):
                        echo "<option disabled=\"disable\" value=\"\"><b> {$ses['titulo']}</b></option>";
                        $readCat = new Read;
                        $readCat->exeRead("categoria_post", "WHERE subcodigo = :parente ORDER BY titulo ASC", "parente={$ses['codigo']}");
                        if($readCat->getRowCount() >= 1):
                            foreach ($readCat->getResult() as $cat):
                                echo "<option value=\"{$cat['codigo']}\" ";
                                if($cat['codigo'] == $atualiza['categoriafilho']) : echo ' selected="selected" ';
                                endif;
                                echo ">{$cat['titulo']}</option>";
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
        <input type="submit" class="btn btn-primary" name="EnviarTrabalho" value="Atualizar" />
        <input type="submit" class="btn btn-info" name="EnviarTrabalho" value="Publicar" />
		<input type="submit" class="btn btn-success" name="SalvarTrabalho" value="Salvar" />
    </form>
    
    <div class="branco">
    
    </div>
</section>
<script>
	$("#titulo").change(function(){
    alert("O valor do input foi modificado.");
});


</script>