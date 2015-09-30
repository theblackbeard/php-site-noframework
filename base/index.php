<!DOCTYPE html>
<?php
    require('./_app/Config.inc.php');
    ob_start();
?>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
        $link = new Link;
        $link->getTags();
    ?>
    <link rel="icon" href="http://carvcom.com/tatianasouza/taty.ico">
    <link href="<?= INCLUDE_PATH; ?>/css/taty.css" rel="stylesheet">
    <link href="<?= INCLUDE_PATH; ?>/css/estilo.css" rel="stylesheet">
    <!-- Bootstrap core CSS -->
    <link href="<?= INCLUDE_PATH; ?>/css/boot/bootstrap.css" rel="stylesheet">
   <link href="<?= INCLUDE_PATH; ?>/css/lightbox.css" rel="stylesheet" />
    <link href="<?= INCLUDE_PATH; ?>/css/responsive.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?= HOME; ?>/js/ie-emulation.js">


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript">var switchTo5x=true;</script>
<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "811f4141-f20b-4f5d-b962-070ede6983fa", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
<script type="text/javascript">var switchTo5x=true;</script>
<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript" src="http://s.sharethis.com/loader.js"></script>


</head>

<body>
<?php
    require(REQUIRE_PATH . '/inc/topo.php');
    
    if(!require($link->getPatch())):
        MSG("Erro ao Incluir arquivo de navegação" , MSG_ERROR, true);
    endif;
  
    require(REQUIRE_PATH . '/inc/rodape.php');
?>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="<?= INCLUDE_PATH; ?>/boot/bootstrap.js"></script>
<script src="<?= INCLUDE_PATH; ?>/boot/bootstrap-hover-dropdown.js"></script>
<script src="<?= HOME; ?>/js/lightbox.min.js"></script>
<script type="text/javascript">stLight.options({publisher: "811f4141-f20b-4f5d-b962-070ede6983fa", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
<script>
var options={ "publisher": "811f4141-f20b-4f5d-b962-070ede6983fa", "position": "left", "ad": { "visible": false, "openDelay": 5, "closeDelay": 0}, "chicklets": { "items": ["linkedin", "facebook", "twitter", "googleplus", "email", "sharethis"]}};
var st_hover_widget = new sharethis.widgets.hoverbuttons(options);
</script>


</body>
</html>