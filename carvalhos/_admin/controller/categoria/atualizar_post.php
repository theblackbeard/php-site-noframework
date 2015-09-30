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
            <li class="active">Editar Categoria de Postagem</li>
        </ol>
        <h2>Editar Categoria de Postagem</h2>

    </header>
    <?php
    $catid = filter_input(INPUT_GET, 'catid', FILTER_VALIDATE_INT);
    $categoria = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if(!empty($categoria['EnviarCategoria'])):
        unset($categoria['EnviarCategoria']);
        require ('models/AdminCatPost.class.php');
        $cadastra = new AdminCatPost;
        $cadastra->exeUpdate($catid, $categoria);
        if(!$cadastra->getResult()):
            MSG($cadastra->getError()[0], $cadastra->getError()[1]);
        else:
           header('Location: painel.php?mt=categoria/index_post&updated=true');
        endif;
    else:
        $read = new Read;
        $read->exeRead("categoria_post", "WHERE codigo= :id", "id={$catid}");
        if(!$read->getResult()):
            header('Location: painel.php?mt=categoria/index_post&empty=true');
        else:
            $categoria = $read->getResult()[0];
        endif;
    endif;
    ?>
    <form action="#" method="post" name="EnviarCategoriaForm">
        <div class="form-group">
            <label for="titulo">Titulo</label>
            <input type="text" class="form-control" id="titulo" name="titulo" value="<?php if(isset($categoria['titulo'])) echo $categoria['titulo']?>" placeholder="Insira um Titulo" required autofocus>
        </div>
        <div class="form-group">
            <label for="descricao">Descrição</label>
            <textarea class="form-control" id="descricao" name="descricao" ><?php if(isset($categoria['descricao'])) echo $categoria['descricao']?></textarea>
        </div>

        <div class="form-group">

            <label for="catsec">Categoria ou Seção</label>
            <select class="form-control" id="catsec" name="subcodigo">
                <option value="null">Categoria Inicial</option>
                <?php
                $readSecao = new Read;
                $readSecao->exeRead("categoria_post" , "WHERE subcodigo IS NULL ORDER BY nome ASC");
                if(!$readSecao->getResult()):
                    echo '<option disabled="disabled" value="null">Cadastre Antes uma Seção!</option>';
                else:
                    foreach ($readSecao->getResult() as $secao):
                        echo "<option value=\"{$secao['codigo']}\" ";
                        if($secao['codigo'] == $categoria['subcodigo']): echo ' selected="selected" ';                endif;
                        echo "><b> Categoria Pai ( Seção ): {$secao['titulo']}</b></option>";
                    endforeach;
                endif;

                ?>
            </select>
        </div>
        <br>

        <input type="submit" class="btn btn-primary" name="EnviarCategoria" value="Atualizar" />
    </form>

</section>