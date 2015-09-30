<?php

class Seo {
    private $file;
    private $link;
    private $data;
    private $tags;
    
    private $seoTags;
    private $seoData;
    
    function __construct($file, $link) {
      $this->file = strip_tags(trim($file));
      $this->link =  strip_tags(trim($link));
    }
    
    public function  getTags() {
      $this->checkData();
      return $this->seoTags;
    }
    
    public function  getData() {
      $this->checkData();
      return $this->seoData;
    }
    
    private function checkData() {
      if(!$this->seoData):
        $this->getSeo();
      endif;
    }
    
    private function getSeo() {
      $readSeo = new Read;
      
      switch ($this->file):
          case 'sobre':
              $readSeo->exeRead("cweb_sobre" , "WHERE sobre_nome = :link" , "link={$this->link}");
              if(!$readSeo->getResult()):
                  $this->seoData = null;
                  $this->seoTags = null;
              else:
                extract($readSeo->getResult()[0]);
                  $this->seoData = $readSeo->getResult()[0];
				  $this->data = [$sobre_titulo . ' - ' . SITENAME, $sobre_descricao, HOME . "/sobre/{$sobre_nome}" ,INCLUDE_PATH . '/images/site.png' ];

              endif;
              break;

        case 'trabalho':
          $Admin = (isset($_SERVER['userlogin']['usu_nivel']) && $_SERVER['userlogin']['usu_nivel'] == 3  ? true : false );
          $check = ($Admin ? '' :  'trab_status = 1 AND');
          $readSeo->exeRead("cweb_trabalho" , "WHERE {$check} trab_nome = :link" , "link={$this->link}");
          if(!$readSeo->getResult()):
            $this->seoData = null;
            $this->seoTags = null;
          else:
            extract($readSeo->getResult()[0]);  
            $this->seoData = $readSeo->getResult()[0];
            
            $this->data = [$trab_titulo . ' - ' . SITENAME, $trab_descricao, HOME . "/trabalho/{$trab_nome}" , HOME  . "/uploads/{$trab_foto}" ];
            //views
            $arrUpdate = ['trab_visualizacao' => $trab_visualizacao + 1];
            $update = new Update;
            $update->exeUpdate("cweb_trabalho", $arrUpdate, "WHERE trab_codigo = :id", "id={$trab_codigo}");
            
          endif;
        break;

          case 'categorias':
              $this->data = [SITENAME . ' - Listagem de Geral de Categoria', SITEDESC, HOME, INCLUDE_PATH . '/images/site.png'];
              break;

          case 'categoria':
              $readSeo->exeRead("cweb_cattrab" , "WHERE ctrab_nome = :link" , "link={$this->link}");
              if(!$readSeo->getResult()):
                  $this->seoData = null;
                  $this->seoTags = null;
              else:
                  extract($readSeo->getResult()[0]);
                  $this->seoData = $readSeo->getResult()[0];

                  $this->data = [$ctrab_titulo . ' - ' . SITENAME, $ctrab_descricao, HOME . "/categoria/{$ctrab_nome}" ,  INCLUDE_PATH . '/images/site.png' ];
                  /*//views
                  $arrUpdate = ['trab_visualizacao' => $trab_visualizacao + 1];
                  $update = new Update;
                  $update->exeUpdate("cweb_trabalho", $arrUpdate, "WHERE trab_codigo = :id", "id={$trab_codigo}");
*/
              endif;
          break;

          case 'contato':
              $Admin = (isset($_SERVER['userlogin']['usu_nivel']) && $_SERVER['userlogin']['usu_nivel'] == 3  ? true : false );
              $readSeo->exeRead("cweb_contato" , "WHERE cont_nome = :link" , "link={$this->link}");
              if(!$readSeo->getResult()):
                  $this->seoData = null;
                  $this->seoTags = null;
              else:
                  extract($readSeo->getResult()[0]);
                  $this->seoData = $readSeo->getResult()[0];

                  $this->data = [$cont_titulo . ' - ' . SITENAME, $cont_descricao, HOME . "/trabalho/{$cont_nome}" , HOME  . "/uploads/{$cont_foto}" ];

              endif;
              break;
        case 'index':
            $this->data = [SITENAME . ' - Portfólio', SITEDESC, HOME, INCLUDE_PATH . '/images/site.png'];

          break;



        default:
            $this->data = ['404, Nada encontrado!', SITEDESC, HOME . '/404' , INCLUDE_PATH . '/images/site.png'];
          
      endswitch;
      
      if($this->data):
        $this->setTags();
      endif;
    }
    
    private function setTags() {
      $this->tags['Title'] = $this->data[0];
      $this->tags['Content'] = Check::word(html_entity_decode($this->data[1]), 25);
      $this->tags['Link'] = $this->data[2];
      $this->tags['Image'] = $this->data[3];
      
      $this->tags = array_map('strip_tags', $this->tags);
      $this->tags = array_map('trim', $this->tags);
      
      $this->data = null;
      
      //Pagina
      $this->seoTags = '<title>' . $this->tags['Title'] . '</title>' . "\n";
      $this->seoTags .= '<meta name="description" content="' . $this->tags['Content'] . '"/>' . "\n";
      $this->seoTags .= '<meta name="robots" content="index, follow"/>' . "\n";
      $this->seoTags .= '<link rel="canonical" href="'. $this->tags['Link'] . '">' ."\n";
      $this->seoTags .= "\n";
      
      //face
      $this->seoTags .= '<meta property="og:site_name" content="' . SITENAME .'"/>' ."\n";
      $this->seoTags .= '<meta property="og:locale" content="pt_BR" />' ."\n";
      $this->seoTags .= '<meta property="og:title" content="' . $this->tags['Title'] .'"/>' ."\n";
      $this->seoTags .= '<meta property="og:description" content="' . $this->tags['Content'] .'"/>' ."\n";
      $this->seoTags .= '<meta property="og:image" content="' . $this->tags['Image'] .'"/>' ."\n";
      $this->seoTags .= '<meta property="og:url" content="' . $this->tags['Link'] .'"/>' ."\n";
      $this->seoTags .= '<meta property="og:type" content="article" />' ."\n";
      
      
      //twiter
      $this->seoTags .= '<meta itemprop="name" content="'. $this->tags['Title'] . '">' . "\n";
      $this->seoTags .= '<meta itemprop="description" content="'. $this->tags['Content'] . '">' . "\n";
      $this->seoTags .= '<meta itemprop="url" content="'. $this->tags['Link'] . '">' . "\n";
      
      $this->tags = null;
      
    }

  
}
