<?php
 if($link->getData()):
        extract($link->getData());
    else:
        header('Location: ' . HOME . DIRECTORY_SEPARATOR . '404');
    endif;

?>
<div id="mt_conteudo" class="container-fluid">


    <?php
    $sobre = new Read;
    $sobre->exeRead("cweb_contato");
    if(!$sobre->getResult()):
        MSG('Nenhuma Pagina Sobre foi Incluida' , MSG_INFO);
    else:
        $ativoContato = $sobre->getResult()[0];
        $view = new View;
        $tpl_contato = $view->Load('contato');
        $ativoContato['datetime'] = date('Y-m-d', strtotime($ativoContato['cont_data']));
        $ativoContato['pubdate'] = date('d/m/Y H:i', strtotime($ativoContato['cont_data']));

        $view->Show($ativoContato, $tpl_contato);

    endif;

    ?>


    <div class="contato">
        <?php

        $contato = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if(isset($contato) && $contato['EnviarContato']):
            unset($contato['EnviarContato']);
            $contato['assunto'] = "Mensagem via site";
            $contato['destinoNome'] = "Tatiana de Souza Santana";
            $contato['destinoEmail'] = 'tatianasouza01@gmail.com';

            $email = new Email;
            $email->enviar($contato);
            if($email->getErro()):
                echo '<p class=\"contato_msg\">'.MSG($email->getErro()[0], $email->getErro()[1]).'</p>';
            endif;
        endif;

        ?>


        <form action="#" name="EnviarContatoForm" method="post">
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" class="form-control" id="nome" name="remetenteNome" value="<?php if(isset($contato['remetenteNome'])) echo $contato['nome'];?>" placeholder="Coloque seu nome" required>
            </div>

            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" class="form-control" id="email" name="remetenteEmail" value="<?php if(isset($contato['remetenteEmail'])) echo $contato['email'];?>" placeholder="Coloque seu e-mail" required>
            </div>

            <div class="form-group">
                <label for="mensagem">E-mail:</label>
                <textarea class="form-control" name="mensagem" rows="4" id="mensagem" required><?php
                    if(isset($contato['mensagem'])) echo $contato['mensagem'];
                    ?></textarea>
            </div>


            <input type="submit" name="EnviarContato" class="btn btn-info" value="Enviar"></input>
        </form>


    </div>

</div>