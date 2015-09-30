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
            <li class="active">Atualizar Pagina Pessoal</li>
        </ol>
        <h2>Atualizar Pagina Pessoal</h2>
    </header>
    <?php
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    $taverna = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if(!empty($taverna['EnviarTav'])):
        $taverna['status'] = ($taverna['EnviarTav'] == 'Rascunho' ? '0' : '1');
        unset($taverna['EnviarTav']);

        require('models/AdminTaverna.class.php');
        $tavCreate = new AdminTaverna;
        $tavCreate->exeUpdate($id, $taverna);
        if($tavCreate->getResult()):
            header('Location: painel.php?mt=taverna/index&updated=true');
        else:
            header('Location: painel.php?mt=taverna/index&errorCreated=true');
        endif;
    else:
        $read = new Read;
        $read->exeRead("taverna" , "WHERE codigo = :id" , "id={$id}");
        if(!$read->getResult()):
            header('Location: painel.php?mt=sobre/index&empty=true');
        else:
            $taverna = $read->getResult()[0];
        endif;


    endif;
    ?>
    <form action="#" method="post" name="EnviarTavForm">
        <div class="form-group">
            <label for="titulo">Titulo</label>
            <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Insira um Titulo" value="<?php if(isset($taverna)) echo $taverna['titulo']; ?>" required autofocus />
        </div>
        <div class="form-group">
            <label for="descricao">Descrição</label>
            <textarea class="form-control" id="descricao" name="descricao" rows="15" placeholder="Insira um Texto"  ><?php if(isset($taverna)) echo $taverna['descricao']; ?> </textarea>
        </div>

        <div class="form-group">
            <label for="titulo">Data:</label>
            <input type="text" class="form-control" id="titulo" name="data" placeholder="Insira um Titulo" value="<?= date('d/m/Y H:i:s') ?>" required autofocus />
        </div>

        <input type="submit" class="btn btn-primary" name="EnviarTav" value="Rascunho" />
        <input type="submit" class="btn btn-primary" name="EnviarTav" value="Atualizar e Publicar" />
    </form>
    <div class="branco"></div>
</section>