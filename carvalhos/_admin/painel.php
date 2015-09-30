<?php
date_default_timezone_set('America/Sao_Paulo');
ob_start();
session_start();
require('../_app/Config.inc.php');

$getmt = filter_input(INPUT_GET, 'mt' , FILTER_DEFAULT);

$login = new Login(1);
$logoff= filter_input(INPUT_GET,'logoff', FILTER_VALIDATE_BOOLEAN);
if(!$login->checkLogin()):
    unset($_SESSION['userlogin']);
    header('Location: index.php?mt=restrito');
else:
    $userlogin = ($_SESSION['userlogin']);
endif;
if($logoff):
    unset($_SESSION['userlogin']);
    header('Location: index.php?mt=logoff');
endif;

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Area Admninistrativa">
    <meta name="author" content="Carlos Mateus Carvalho">
    <link rel="icon" href="../../favicon.ico">
    <title>Area Administrativa - Inicial</title>
    <link href="<?= HOME ?>/_items/css/boot/bootstrap.min.css" rel="stylesheet" >
    <link href="css/estilo.css" rel="stylesheet" >
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="<?= HOME ?>/_items/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="<?= HOME ?>/_items/js/ie-emulation-modes-warning.js"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- TyneMC -->
    <script type="text/javascript" src="js/editor/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: "textarea",
            theme: "modern",
            height: 300,

            plugins: [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak ",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "save table contextmenu directionality emoticons template paste textcolor wordcount badge bprimary bbloq syntax bimagem"
            ],
            content_css: "css/content.css, css/boot/bootstrap.css, css/syntax/shCore.css, css/syntax/shCoreDefault.css",
            toolbar: "badge| link | image| bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | forecolor backcolor emoticons | undo redo | ",
            style_formats: [
                {title: 'Bold text', inline: 'b'},
                {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
                {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
                {title: 'Example 1', inline: 'span', classes: 'example1'},
                {title: 'Example 2', inline: 'span', classes: 'example2'},
                {title: 'Table styles'},
                {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
            ]
        });
    </script>
    <script type="text/javascript">
        SyntaxHighlighter.all()
    </script>
</head>
<?php
if(isset($getmt)):
    $linkto = explode('/', $getmt);
else:
    $linkto = array();
endif;
?>
<body>
<!-- Meno do Topo -->
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Área Administrativa</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="<?= HOME ?>/_admin">Dashboard</a></li>
                <li><a href="painel.php?mt=conf/index">Configurações</a></li>
                <li><a href="#">Profile</a></li>
                 <li><a href="<?= HOME ?>" target="_blank">Front-End</a></li>
                <li><a href="painel.php?logoff=true">Sair</a></li>
            </ul>

        </div>
    </div>
</nav>
<!-- Fim Meno do Topo -->

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-4 col-md-3 sidebar">
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">


                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="menu">
                        <h4 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#atual" aria-expanded="false" aria-controls="atual">
                                Atualizações
                            </a>
                        </h4>
                    </div>
                    <div id="atual" class="panel-collapse collapse" role="tabpanel" aria-labelledby="menu">
                        <div class="panel-body">
                            <ul class="list-group">
                                <li class="list-group-item">Nova Atualização</li>
                                <li class="list-group-item">Listar Atualizações</li>

                            </ul>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="menu1">
                        <h4 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#sobre" aria-expanded="false" aria-controls="sobre">
                                Sobre
                            </a>
                        </h4>
                    </div>
                    <div id="sobre" class="panel-collapse collapse" role="tabpanel" aria-labelledby="menu1">
                        <div class="panel-body">
                            <ul class="list-group">
                                <li class="list-group-item"><a href="painel.php?mt=sobre/adicionar">Novo Sobre</a></li>
                                <li class="list-group-item"><a href="painel.php?mt=sobre/index">Listar Sobre</a></li>

                            </ul>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="menu2">
                        <h4 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#cattrab" aria-expanded="false" aria-controls="cattrab">
                                Categoria de Trabalhos
                            </a>
                        </h4>
                    </div>
                    <div id="cattrab" class="panel-collapse collapse" role="tabpanel" aria-labelledby="menu2">
                        <div class="panel-body">
                            <ul class="list-group">
                                <li class="list-group-item"><a href="painel.php?mt=categoria/adicionar">Nova Categoria</a></li>
                                <li class="list-group-item"><a href="painel.php?mt=categoria/index">Listar Categorias</a></li>

                            </ul>
                        </div>
                    </div>
                </div>


                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="menu3">
                        <h4 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#catpost" aria-expanded="false" aria-controls="catpost">
                                Categoria de Postagem
                            </a>
                        </h4>
                    </div>
                    <div id="catpost" class="panel-collapse collapse" role="tabpanel" aria-labelledby="menu3">
                        <div class="panel-body">
                            <ul class="list-group">
                                <li class="list-group-item"><a href="painel.php?mt=categoria/adicionar_post">Nova Categoria</a></li>
                                <li class="list-group-item"><a href="painel.php?mt=categoria/index_post">Listar Categorias</a></li>

                            </ul>
                        </div>
                    </div>
                </div>


                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="menu4">
                        <h4 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#trabalho" aria-expanded="false" aria-controls="trabalho">
                                Trabalho
                            </a>
                        </h4>
                    </div>
                    <div id="trabalho" class="panel-collapse collapse" role="tabpanel" aria-labelledby="menu4">
                        <div class="panel-body">
                            <ul class="list-group">
                                <li class="list-group-item"><a href="painel.php?mt=trabalho/adicionar">Novo Trabalho</a></li>
                                <li class="list-group-item"><a href="painel.php?mt=trabalho/index">Listar Trabalhos</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="menu5">
                        <h4 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#postagem" aria-expanded="false" aria-controls="postagem">
                                Postagem
                            </a>
                        </h4>
                    </div>
                    <div id="postagem" class="panel-collapse collapse" role="tabpanel" aria-labelledby="menu5">
                        <div class="panel-body">
                            <ul class="list-group">
                                <li class="list-group-item"><a href="painel.php?mt=post/adicionar">Novo Post</a></li>
                                <li class="list-group-item"><a href="painel.php?mt=post/index">Listar Posts</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="menu5">
                        <h4 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#usuario" aria-expanded="false" aria-controls="usuario">
                                Usuarios
                            </a>
                        </h4>
                    </div>
                    <div id="usuario" class="panel-collapse collapse" role="tabpanel" aria-labelledby="menu5">
                        <div class="panel-body">
                            <ul class="list-group">
                                <li class="list-group-item"><a href="painel.php?mt=usuario/adicionar">Nova Usuario</a></li>
                                <li class="list-group-item"><a href="painel.php?mt=usuario/index">Listar Usuarios</a></li>

                            </ul>
                        </div>
                    </div>
                </div>


            </div>
        </div>


        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

            <?php
            if(!empty($getmt)):
                $includepath = __DIR__. '/controller/' . strip_tags(trim($getmt) .'.php');
            else:
                $includepath = __DIR__. '/controller/home.php';
            endif;
            if(file_exists($includepath)):
                require_once ($includepath);
            else:
                echo "<div class=\"content notfound\">";
                MSG("Erro ao incluir tela no controle /{$getmt}.php" , MSG_ERROR);
                echo "</div>";
            endif;
            ?>
        </div>
</div>


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="<?= HOME ?>/_items/js/jquery.js"></script>
<script src="<?= HOME ?>/_items/js/boot/bootstrap.min.js"></script>

</body>