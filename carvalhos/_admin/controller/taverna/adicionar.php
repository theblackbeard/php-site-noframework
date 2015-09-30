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
            <li class="active">Nova Pagina Pessoal</li>
        </ol>
        <h2>Nova Pagina Pessoal</h2>
        </header>
    <?php
    $taverna = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if(!empty($taverna['EnviarTav'])):
        $taverna['status'] = ($taverna['EnviarTav'] == 'Rascunho' ? '0' : '1');
        unset($taverna['EnviarTav']);

        require('models/AdminTaverna.class.php');
        $tavCreate = new AdminTaverna;
        $tavCreate->exeCreate($taverna);
        if($tavCreate->getResult()):
            header('Location: painel.php?mt=taverna/index&created=true');
        else:
            header('Location: painel.php?mt=taverna/index&errorCreated=true');
        endif;

    endif;
    ?>
    <form action="#" method="post" name="EnviarTavForm">
        <div class="form-group">
            <label for="titulo">Titulo</label>
            <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Insira um Titulo" value="<?php if(isset($taverna)) echo $sobre['titulo']; ?>" required autofocus />
        </div>
        <div class="form-group">
            <label for="descricao">Descrição</label>
            <textarea class="form-control" id="descricao" name="descricao" rows="15" placeholder="Insira um Texto"  ><?php if(isset($taverna)) echo $sobre['descricao']; ?> </textarea>
        </div>

        <div class="form-group">
            <label for="titulo">Data:</label>
            <input type="text" class="form-control" id="titulo" name="data" placeholder="Insira um Titulo" value="<?= date('d/m/Y H:i:s') ?>" required autofocus />
        </div>

        <input type="submit" class="btn btn-primary" name="EnviarTav" value="Rascunho" />
        <input type="submit" class="btn btn-primary" name="EnviarTav" value="Cadastrar e Publicar" />
    </form>
    <div class="branco"></div>
</section>