<?php
class Seo {
	private $file;
	private $link;
	private $data;
	private $tags;
	private $seoTags;
	private $seoData;
	function __construct($file, $link) {
		$this->file = strip_tags ( trim ( $file ) );
		$this->link = strip_tags ( trim ( $link ) );
	}
	public function getTags() {
		$this->checkData ();
		return $this->seoTags;
	}
	public function getData() {
		$this->checkData ();
		return $this->seoData;
	}
	private function checkData() {
		if (! $this->seoData) :
			$this->getSeo ();
		
      endif;
	}
	private function getSeo() {
		$readSeo = new Read ();
		
		switch ($this->file) :
			case 'sobre' :
				$readSeo->exeRead ( "sobre", "WHERE nome = :link", "link={$this->link}" );
				if (! $readSeo->getResult ()) :
					$this->seoData = null;
					$this->seoTags = null;
				 else :
					extract ( $readSeo->getResult ()[0] );
					$this->seoData = $readSeo->getResult ()[0];
					
					$this->data = [ 
							$titulo . ' - ' . SITENAME,
							$descricao,
							HOME . "/sobre/{$nome}",
							INCLUDE_PATH . '/images/sobre.png'
					];
				

				endif;
				break;

			case 'portfolio' :
				$this->data = [
                						SITENAME . ' - Ultimos Trabalhos',
                						SITEDESC,
                						HOME,
                						INCLUDE_PATH . '/images/fortfolio.png'
                 ];
			break;

			case 'categoria' :
				$readSeo->exeRead ( "categoria", "WHERE nome = :link", "link={$this->link}" );
				if (! $readSeo->getResult ()) :
					$this->seoData = null;
					$this->seoTags = null;
				 else :
					extract ( $readSeo->getResult ()[0] );
					$this->seoData = $readSeo->getResult ()[0];

					$this->data = [
							'Categoria: ' . $titulo . ' - ' . SITENAME,
							$descricao,
							HOME . "/categoria/{$nome}",
							INCLUDE_PATH . '/images/site.png'
					];

				endif;
				break;
			case 'trabalho' :
				$Admin = (isset ( $_SERVER ['userlogin'] ['nivel'] ) && $_SERVER ['userlogin'] ['nivel'] == 3 ? true : false);
				$check = ($Admin ? '' : 'status = 1 AND');
				$readSeo->exeRead ( "trabalho", "WHERE {$check} nome = :link", "link={$this->link}" );
				if (! $readSeo->getResult ()) :
					$this->seoData = null;
					$this->seoTags = null;
				 else :
					extract ( $readSeo->getResult ()[0] );
					$this->seoData = $readSeo->getResult ()[0];
					
					$this->data = [ 
							$titulo . ' - ' . SITENAME,
							$descricao,
							HOME . "/trabalho/{$nome}",
							HOME . "/uploads/{$foto}"
					];
					// views
					$arrUpdate = [ 
							'aberto' => $aberto + 1 
					];
					$update = new Update ();
					$update->exeUpdate ( "trabalho", $arrUpdate, "WHERE codigo = :id", "id={$codigo}" );
				

				endif;
				break;

            case 'blog' :
                $readSeo->exeRead ( "categoria_post", "WHERE nome = :link", "link={$this->link}" );
                if (! $readSeo->getResult ()) :
                    $this->seoData = null;
                    $this->seoTags = null;
                else :
                    extract ( $readSeo->getResult ()[0] );
                    $this->seoData = $readSeo->getResult ()[0];

                    $this->data = [
                        'Categoria: ' . $titulo . ' - ' . SITENAME,
                        $descricao,
                        HOME . "/blog/{$nome}",
                        INCLUDE_PATH . '/images/site.png'
                    ];

                endif;
                break;
            case 'post' :
                $Admin = (isset ( $_SERVER ['userlogin'] ['nivel'] ) && $_SERVER ['userlogin'] ['nivel'] == 3 ? true : false);
                $check = ($Admin ? '' : 'status = 1 AND');
                $readSeo->exeRead ( "post", "WHERE {$check} nome = :link", "link={$this->link}" );
                if (! $readSeo->getResult ()) :
                    $this->seoData = null;
                    $this->seoTags = null;
                else :
                    extract ( $readSeo->getResult ()[0] );
                    $this->seoData = $readSeo->getResult ()[0];

                    $this->data = [
                        $titulo . ' - ' . SITENAME,
                        $descricao,
                        HOME . "/post/{$nome}",
                        HOME . "/uploads/{$foto}"
                    ];
                    // views
                    $arrUpdate = [
                        'aberto' => $aberto + 1
                    ];
                    $update = new Update ();
                    $update->exeUpdate ( "post", $arrUpdate, "WHERE codigo = :id", "id={$codigo}" );


                endif;
                break;

            case 'taverna':

                $readSeo->exeRead ( "categoria_tav", "WHERE nome = :link", "link={$this->link}" );
                if (! $readSeo->getResult ()) :
                    $this->seoData = null;
                    $this->seoTags = null;
                else :
                    extract ( $readSeo->getResult ()[0] );
                    $this->seoData = $readSeo->getResult ()[0];

                    $this->data = [
                        'Categoria: ' . $titulo . ' - ' . SITENAME,
                        $descricao,
                        HOME . "/taverna/{$nome}",
                        INCLUDE_PATH . '/images/love.png'
                    ];

                endif;

                break;

			case 'contato' :
				$Admin = (isset ( $_SERVER ['userlogin'] ['usu_nivel'] ) && $_SERVER ['userlogin'] ['usu_nivel'] == 3 ? true : false);
				$readSeo->exeRead ( "cweb_contato", "WHERE cont_nome = :link", "link={$this->link}" );
				if (! $readSeo->getResult ()) :
					$this->seoData = null;
					$this->seoTags = null;
				 else :
					extract ( $readSeo->getResult ()[0] );
					$this->seoData = $readSeo->getResult ()[0];
					
					$this->data = [ 
							$cont_titulo . ' - ' . SITENAME,
							$cont_descricao,
							HOME . "/trabalho/{$cont_nome}",
							HOME . "/uploads/{$cont_foto}" 
					];
				endif;
				break;
			case 'index' :
				$this->data = [ 
						SITENAME . ' - Portfólio Acadêmico',
						SITEDESC,
						HOME,
						HOME . '/_images/capa.jpg'
				];
				
				break;
			
			default :
				$this->data = [ 
						'404, Nada encontrado!',
						SITEDESC,
						HOME . '/404',
						INCLUDE_PATH . '/images/site.png' 
				];
		endswitch
		;
		
		if ($this->data) :
			$this->setTags ();
		
      endif;
	}
	private function setTags() {
		$this->tags ['Title'] = $this->data [0];
		$this->tags ['Content'] = Check::word ( html_entity_decode ( $this->data [1] ), 25 );
		$this->tags ['Link'] = $this->data [2];
		$this->tags ['Image'] = $this->data [3];
		
		$this->tags = array_map ( 'strip_tags', $this->tags );
		$this->tags = array_map ( 'trim', $this->tags );
		
		$this->data = null;
		
		// Pagina
		$this->seoTags = '<title>' . $this->tags ['Title'] . '</title>' . "\n";
		$this->seoTags .= '<meta name="description" content="' . $this->tags ['Content'] . '"/>' . "\n";
		$this->seoTags .= '<meta name="robots" content="index, follow"/>' . "\n";
		$this->seoTags .= '<link rel="canonical" href="' . $this->tags ['Link'] . '">' . "\n";
		$this->seoTags .= "\n";
		
		// face
		$this->seoTags .= '<meta property="og:site_name" content="' . SITENAME . '"/>' . "\n";
		$this->seoTags .= '<meta property="og:locale" content="pt_BR" />' . "\n";
		$this->seoTags .= '<meta property="og:title" content="' . $this->tags ['Title'] . '"/>' . "\n";
		$this->seoTags .= '<meta property="og:description" content="' . $this->tags ['Content'] . '"/>' . "\n";
		$this->seoTags .= '<meta property="og:image" content="' . $this->tags ['Image'] . '"/>' . "\n";
		$this->seoTags .= '<meta property="og:image:type" content="image/jpeg" />' . "\n";
        $this->seoTags .= '<meta property="og:image:width" content="800" />' . "\n";
        $this->seoTags .= '<meta property="og:image:height" content="600">' . "\n";
        $this->seoTags .= '<meta property="og:url" content="' . $this->tags ['Link'] . '"/>' . "\n";
		$this->seoTags .= '<meta property="og:type" content="article" />' . "\n";
        $this->seoTags .= '<meta property="og:locale" content="en_US" />' . "\n";
		
		// twiter

        //<!-- Twitter Card data -->
        $this->seoTags .= '<meta name="twitter:card" content="summary" />' ."\n";
        $this->seoTags .= '<meta name="twitter:site" content="@ticarvalhos" />' ."\n";
        $this->seoTags .= '<meta name="twitter:title" content="' . $this->tags ['Title'] . '"/>'."\n";
        $this->seoTags .= '<meta name="twitter:description" content="' . $this->tags ['Content'] . '" />'."\n";
        $this->seoTags .= '<meta name="twitter:creator" content="@ticarvalhos" />'."\n";
       // $this->seoTags .=<-- Twitter Summary card images must be at least 120x120px -->
        $this->seoTags .= '<meta name="twitter:image" content="' . $this->tags ['Image'] . '" />' ."\n";

        $this->seoTags .= '<meta itemprop="name" content="' . $this->tags ['Title'] . '"/>' . "\n";
		$this->seoTags .= '<meta itemprop="description" content="' . $this->tags ['Content'] . '"/>' . "\n";
		$this->seoTags .= '<meta itemprop="url" content="' . $this->tags ['Link'] . '"/>' . "\n";
		
		$this->tags = null;
	}
}
