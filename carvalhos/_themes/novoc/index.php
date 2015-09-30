<div id="pagina">
<header id="cabecalho">
    <h1>Carlos M. Carvalhos </h1>
    <h2>Web Developer</h2>
    <hr>
</header>
<main id="conteudo">
    <article>
    <?php
        $about = new Read;
        $about->exeRead("sobre");
        echo $about->getResult()[0]['descricao'];
     ?>


    </article>
    <hr>
    <section class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Curte a Nossa Página no Facebook</h3>
      </div>
      <div class="panel-body">
      <div class="fb-like" data-href="https://www.facebook.com/devcarv" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>
      </div>
    </div>
    </section>

    <section class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Contato Google+</h3>
      </div>
      <div class="panel-body">
        <!-- Posicione esta tag no cabeçalho ou imediatamente antes da tag de fechamento do corpo. -->
        <script src="https://apis.google.com/js/platform.js" async defer>
          {lang: 'pt-BR'}
        </script>

        
        <div class="g-page" data-width="300" data-href="//plus.google.com/u/0/100624809176293290562" data-layout="landscape" data-rel="publisher"></div>
      </div>
    </div>
    </section>
</main>
<footer>

</footer>
</div>


