<?php
if($link->getData()):
extract($link->getData());
    //var_dump($link->getData());
else:
    header('Location: ' . HOME . DIRECTORY_SEPARATOR . '404');
endif;
?>
<div id="pagina">
<?php
    $view = new View;
    $tpl_about = $view->Load('sobre');
    $view->Show($link->getData(), $tpl_about);
?>
</div>