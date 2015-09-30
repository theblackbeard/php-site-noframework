<?php

class Upload {
   
    /**/
    private $file;
    private $name;
    private $send;
    
    /*image upload*/
    private $width;
    private $image;
    
    /*Resulteset*/
    private $resultado;
    private $error;
    
    /*Diretorios*/
    private $folder;
    private static $baseDir;
    
    function __construct($baseDir = null ) {
        self::$baseDir = ((string)$baseDir ? $baseDir : '../uploads/');
        if(!file_exists(self::$baseDir) && !is_dir(self::$baseDir)):
            mkdir(self::$baseDir, 0777);
        endif;
    }
    
    public function imagem(array $image, $name = null, $width = null, $folder = null) {
        $this->file = $image;
        $this->name = ((string) $name ? $name : substr($image['name'], 0 , strrpos($image['name'], '.')));
        $this->width = ((int) $width ? $width : 1920);
        $this->folder = ((String) $folder ? $folder : 'imagens');
        $this->checkFolder($this->folder);
        $this->setFileName();
        $this->uploadImage();
    }
    
    public function file(array $file, $name = null, $folder = null, $maxFileSize= null) {
        $this->file = $file;
        $this->name = ((string) $name ? $name : substr($file['name'], 0 , strrpos($file['name'], '.')));
        $this->folder = ((String) $folder ? $folder : 'files');
        $maxFileSize = ((int)$maxFileSize ? $maxFileSize : 2);
        
        $fileAccept = [
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/pdf',
            'text/plain'
        ];
        
        if($this->file['size'] > ($maxFileSize * (1920*1920))):
            $this->resultado = false;
            $this->error = "Arquivo Muito Grande, tamanho maximo permitido {$maxFileSize} mb";
        elseif(!in_array($this->file['type'], $fileAccept)):
            $this->resultado = false;
            $this->error = "Tipo de Arquivo Não Suportado";
        else:
            $this->checkFolder($this->folder);
            $this->setFileName();
            $this->MoveFile();
        endif;
    }
    
      public function media(array $media, $name = null, $folder = null, $maxFileSize= null) {
        $this->file = $media;
        $this->name = ((string) $name ? $name : substr($media['name'], 0 , strrpos($media['name'], '.')));
        $this->folder = ((String) $folder ? $folder : 'medias');
        $maxFileSize = ((int)$maxFileSize ? $maxFileSize : 50);
        
        $fileAccept = [
            'audio/mp3',
            'video/mp4',
            'audio/mpeg'
        ];
        
        if($this->file['size'] > ($maxFileSize * (1920*1920))):
            $this->resultado = false;
            $this->error = "Arquivo Muito Grande, tamanho maximo permitido {$maxFileSize} mb";
        elseif(!in_array($this->file['type'], $fileAccept)):
            $this->resultado = false;
            $this->error = "Tipo de Arquivo Não Suportado";
        else:
            $this->checkFolder($this->folder);
            $this->setFileName();
            $this->MoveFile();
        endif;
    }
    
    
    function getResultado() {
        return $this->resultado;
    }

    function getError() {
        return $this->error;
    }

        
    //
    private function checkFolder($folder) {
        list($y, $m) = explode('/', date('Y/m'));
        $this->createFolder("{$folder}");
        $this->createFolder("{$folder}/{$y}");
        $this->createFolder("{$folder}/{$y}/{$m}/");
        $this->send = "{$folder}/{$y}/{$m}/";
        
    }
    private function createFolder($folder) {
         if(!file_exists(self::$baseDir . $folder) && !is_dir(self::$baseDir . $folder)):
            mkdir(self::$baseDir . $folder, 0777);
        endif;
        
    }
    
    private function setFileName() {
        $fileName= Check::name($this->name). strrchr($this->file['name'], '.');
        if(file_exists(self::$baseDir . $this->send .$fileName)):
              $fileName= Check::name($this->name).'-' .time(). strrchr($this->file['name'], '.');
        endif;
        $this->name = $fileName;
    }
    
    /* Realiza o upload de imagens redemesionandp*/
    private function uploadImage() {
        switch ($this->file['type']):
            case 'image/jpg':
            case 'image/jpeg':
            case 'image/pjpeg':
            $this->image = imagecreatefromjpeg($this->file['tmp_name']);
            break;
        
            case 'image/png':
            case 'image/x-png':
                $this->image = imagecreatefrompng($this->file['tmp_name']);
                break;
        endswitch;
        
        if(!$this->image):
            $this->resultado = false;
        $this->error = "Tipo de Arquivo Invalido";
        else:
            $x = imagesx($this->image);
            $y = imagesy($this->image);
            $imagemX = ($this->width < $x ? $this->width : $x);
            $imagemH = ($imagemX * $y) / $x;
            
            $newImage = imagecreatetruecolor($imagemX, $imagemH);
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            imagecopyresampled($newImage, $this->image, 0, 0, 0, 0, $imagemX, $imagemH, $x, $y);
            
            switch ($this->file['type']):
            case 'image/jpg':
            case 'image/jpeg':
            case 'image/pjpeg':
                imagejpeg($newImage, self::$baseDir . $this->send . $this->name);
            break;
        
            case 'image/png':
            case 'image/x-png':
                 imagepng($newImage, self::$baseDir . $this->send . $this->name);
                break;
        endswitch;
        
        if(!$newImage):
            $this->resultado = false;
            $this->error = "Tipo de Arquivo Invalido";
        else:
            $this->resultado = $this->send . $this->name;
            $this->error = null;
        endif;
          
        imagedestroy($this->image);
        imagedestroy($newImage);
                    
        endif;
    }
    
    //envia arquivos e midias
    private function MoveFile() {
        if(move_uploaded_file($this->file['tmp_name'], self::$baseDir . $this->send . $this->name)):
            $this->resultado = $this->send . $this->name;
            $this->error = null;
        else:
            $this->resultado = false;
            $this->error = 'Erro ao Mover o arquivo. favor tente mais tarde';
        endif;
        
    }
    

}
