<?php
date_default_timezone_set ( 'America/Sao_Paulo' );
ob_start ();
session_start ();
require ('../_app/Config.inc.php');
$getmt = filter_input ( INPUT_GET, 'mt', FILTER_DEFAULT );
$login = new Login ( 3 );
$logoff = filter_input ( INPUT_GET, 'logoff', FILTER_VALIDATE_BOOLEAN );
if (! $login->checkLogin ()) :
	unset ( $_SESSION ['userlogin'] );
	header ( 'Location: index.php?mt=restrito' );
 else :
	$userlogin = ($_SESSION ['userlogin']);
endif;
if ($logoff) :
	unset ( $_SESSION ['userlogin'] );
	header ( 'Location: index.php?mt=logoff' );

  endif;
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<link rel="icon" href="../../favicon.ico">

<title>Admin - Dashboard</title>

<!-- Bootstrap core CSS -->
<link href="css/bootstrap.min.css" rel="stylesheet">

<!-- Custom styles for this template -->
<link href="css/signin.css" rel="stylesheet">
<link href="css/mttaty.css" rel="stylesheet">
<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
<!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
<script src="js/ie-emulation.js"></script>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</script>


<!-- TyneMC -->
<script type="text/javascript" src="js/editor/tinymce.min.js"></script>
<script>
tinymce.init({
    selector: "textarea",
    theme: "modern",
    
    height: 300,
    plugins: [
         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
         "save table contextmenu directionality emoticons template paste textcolor wordcount"
   ],
   content_css: "css/content.css",
   toolbar: "link | image| bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | forecolor backcolor emoticons | undo redo", 
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


</head>


<body>
        <?php
								if (isset ( $getmt )) :
									$linkto = explode ( '/', $getmt );
								 else :
									$linkto = array ();
								endif;
								?>
       <nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed"
					data-toggle="collapse" data-target="#navbar" aria-expanded="false"
					aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span> <span
						class="icon-bar"></span> <span class="icon-bar"></span> <span
						class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="painel.php?mt=home">Área Administrativa</a>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<li class="<?php if(in_array('home', $linkto))?>"><a
						href="painel.php?mt=home">Dashboard</a></li>

					<li
						class="dropdown <?php if(in_array('sobre', $linkto)) echo ' active';?>">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"
						role="button" a0ria-expanded="false">Sobre <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="painel.php?mt=sobre/adicionar">Novo Sobre</a></li>
							<li><a href="painel.php?mt=sobre/index">Listar Sobre</a></li>

						</ul>
					</li>
					<li
						class="dropdown <?php if(in_array('categoria', $linkto)) echo ' active'?>">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"
						role="button" aria-expanded="false">Categoria <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="painel.php?mt=categoria/adicionar">Nova Categoria</a></li>
							<li><a href="painel.php?mt=categoria/index">Listar Categoria</a></li>

						</ul>
					</li>

					<li
						class="dropdown <?php if(in_array('trabalho', $linkto)) echo ' active'?>">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"
						role="button" aria-expanded="false">Trabalhos <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="painel.php?mt=trabalho/adicionar">Nova Trabalho</a></li>
							<li><a href="painel.php?mt=trabalho/index">Listar Trabalho </a></li>

						</ul>
					</li>

					<li
						class="dropdown <?php if(in_array('contato', $linkto)) echo ' active'?>">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"
						role="button" aria-expanded="false">Contato <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="painel.php?mt=contato/adicionar">Nova Pagina</a></li>
							<li><a href="painel.php?mt=contato/index">Listar Pagina </a></li>

						</ul>
					</li>

					<li class="dropdown <?php if(in_array('opcoes', $linkto))?>"><a
						href="#" class="dropdown-toggle" data-toggle="dropdown"
						role="button" aria-expanded="false">Opções <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="painel.php?mt=opcoes/novo_usuario">Novo Usuario</a></li>
							<li><a href="painel.php?mt=opcoes/lista_usuario">Listagem Usuario</a></li>
							<li class="divider"></li>
							<li class="dropdown-header"><?= $userlogin['usu_nome'] ?> <?= $userlogin['usu_sobrenome']?></li>
							<li><a href="painel.php?logoff=true">Sair do Sistema</a></li>
						</ul></li>

				</ul>
			</div>
			<!--/.nav-collapse -->
		</div>
	</nav>
	<div class="container-fluid">
		<div class="container">
          <?php
										if (! empty ( $getmt )) :
											$includepath = __DIR__ . '/controller/' . strip_tags ( trim ( $getmt ) . '.php' );
										 else :
											$includepath = __DIR__ . '/controller/home.php';
										endif;
										if (file_exists ( $includepath )) :
											require_once ($includepath);
										 else :
											echo "<div class=\"content notfound\">";
											MSG ( "Erro ao incluir tela no controle /{$getmt}.php", MSG_ERROR );
											echo "</div>";
										endif;
										?>
        </div>
	</div>



	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script
		src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="js/bootstrap.min.js"></script>
	<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-61654495-1', 'auto');
  ga('send', 'pageview');

</script>


</body>
</html>