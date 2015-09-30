<?php 
    require ('../_app/Config.inc.php');
    session_start();
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

    <title>Admin - Login</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="js/ie-emulation.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    </head>
    <body>
        
      <div class="container">
        
  
        <form class="form-signin" role="form" name="AdminLoginForm" action="#" method="post">
          <?php  
          $login = new Login(3);
          if($login->checkLogin()):
            header('Location: painel.php');
          endif;
          
          $dataLogin = filter_input_array(INPUT_POST, FILTER_DEFAULT);
          if(!empty($dataLogin['LoginForm'])):
            
          $login->exeLogin($dataLogin);
           if(!$login->getResult()):
                MSG($login->getError()[0], $login->getError()[1]);
              else:
                header('Location: painel.php');
              endif;
           endif;
          
          $get = filter_input(INPUT_GET, 'mt' , FILTER_DEFAULT);
          if(!empty($get)):
            if($get == 'restrito'):
              MSG('<b>Opsss</b> Acesso Negado, efetue o login' , MSG_ALERT);
            elseif($get == 'logoff'):
              MSG('Sucesso ao Deslogar do Sistema' , MSG_ACCEPT);
            endif;
          endif;
          
        ?>
        <h2 class="form-signin-heading">Área Administrativa</h2>
        <div class="form-group">
        <label for="inputEmail" class="sr-only">Endereço de E-mail</label>
        <input type="email" id="inputEmail" class="form-control" name="user" placeholder="Endereço de E-Mail" required autofocus >
        </div>
        <div class="form-group">
        <label for="inputPassword" class="sr-only">Senha</label>
        <input type="password" id="inputPassword" class="form-control" name="pass" placeholder="Senha" required>
        </div>
        <input class="btn btn-lg btn-primary btn-block" type="submit" name="LoginForm" value="Logar" />
            <a class="btn btn-lg btn-info btn-block" type="submit" href="<?= HOME ?>">Pagina Inicial</a>
      </form>

      </div>
      
      
       <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    
        </body>
</html>
