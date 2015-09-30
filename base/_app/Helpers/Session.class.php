<?php

class Session {
   private $data;
   private $cache;
   private $traffic;
   private $browser;
   
   function __construct($cache = null) {
       session_start(); 
       $this->checkSession($cache);
   }

   
   public function checkSession($cache = null) {
       $this->data = date('Y-m-d');
       $this->cache = ((int) $cache ? $cache : 20); 
       
       if(empty($_SESSION['useronline'])):
           $this->setTraffic();
           $this->setSession();
           $this->checkBrowser();
           $this->setUsuario();
           $this->browserUpdate();
       else:
            $this->checkBrowser();
            $this->trafficUpdate();
            $this->sessionUpdate();
            $this->usuarioUpdate();
       endif;
       
       $this->data = null;
   }
   //iniar sessao
   private function setSession() {
       $_SESSION['useronline'] = [
           "online_session" => session_id(),
           "online_startview" => date('Y-m-d H:i:s'),
           "online_endview" => date('Y-m-d H:i:s' , strtotime("+{$this->cache}minutes")),
           "online_ip" => filter_input(INPUT_SERVER, 'REMOTE_ADDR' , FILTER_VALIDATE_IP),
           "online_url" => filter_input(INPUT_SERVER, 'REQUEST_URI' , FILTER_DEFAULT),
           "online_agent" => filter_input(INPUT_SERVER, "HTTP_USER_AGENT" , FILTER_DEFAULT)
   
       ];
   }
   
   //atualizar sessao do usuario
   private function sessionUpdate() {
       $_SESSION['useronline']['online_endview'] = date('Y-m-d H:i:s' , strtotime("+{$this->cache}minutes"));
       $_SESSION['useronline']['online_url'] = filter_input(INPUT_SERVER, 'REQUEST_URI' , FILTER_DEFAULT);
       
   }
   
   //verirfia e inser o trafico
   
   private function setTraffic() {
       $this->getTraffic();
       if(!$this->traffic):
           $arrSiteViews = ['site_date' => $this->data, 'site_users' => 1, 'site_views' => 1, 'site_pages' => 1];
       $createSiteView = new Create;
       $createSiteView->exeCreate('siteviews', $arrSiteViews);
       else:
           if(!$this->getCookie()):
                $arrSiteViews = ['site_users' => $this->traffic['site_users']+1, 'site_views' => $this->traffic['site_views']+1, 'site_pages' => $this->traffic['site_pages']+1];
           else:
             $arrSiteViews = ['site_views' => $this->traffic['site_views']+1, 'site_pages' => $this->traffic['site_pages']+1];
          
               endif;
               
               $updateSiteViews = new Update;
               $updateSiteViews->exeUpdate('siteviews', $arrSiteViews, "WHERE site_date= :data", "data={$this->data}");
       endif;
   }
   
   private function trafficUpdate() {
       $this->getTraffic();
       $arrSiteViews = ['site_pages' => $this->traffic['site_pages']+1];
       $updatePageViews = new Update;
       $updatePageViews->exeUpdate('siteviews', $arrSiteViews, "WHERE site_date= :data", "data={$this->data}");
       $this->traffic = null;
   }
   
   
   //obtem dados da tabela
   private function getTraffic() {
       $readSiteViews = new Read;
       $readSiteViews->exeRead('siteviews' , "WHERE site_date = :date" , "date={$this->data}");
       if($readSiteViews->getRowCount()):
        $this->traffic = $readSiteViews->getResult()[0];
       endif;
   }
   
   
   //verica cookie cria
   private function getCookie() {
       $cookie = filter_input(INPUT_COOKIE, 'useronline', FILTER_DEFAULT);
       setcookie("useronline" , base64_encode("carvalhos") , time() + 86400);
       if(!$cookie):
           return false;
       else:
           return true;
       endif;
       
       
   }
   
   /*Identificando navegador*/
   private function checkBrowser() {
       $this->browser = $_SESSION['useronline']['online_agent'];
       if(strpos($this->browser, 'Chrome')):
           $this->browser = 'Chrome';
       elseif(strpos($this->browser, 'Mozila')):
           $this->browser = 'Firefox';
       elseif(strpos($this->browser, 'MSIE') || strpos($this->browser, 'Trident/')):
           $this->browser = 'IE';
       else: 
           $this->browser = 'Outros';
       endif;
   }
   
   //atualiza tabela com dados de nabegadores
   private function browserUpdate() {
       $readAgente = new Read;
       $readAgente->exeRead('ws_siteviews_agent' , "WHERE agent_name= :agent", "agent={$this->browser}");
       if(!$readAgente->getResult()):
           $arrAgente = ['agent_name' => $this->browser , 'agent_views' => 1];
           $createAgent = new Create;
           $createAgent->exeCreate('ws_siteviews_agent', $arrAgente);
       else:
           $arrAgente = ['agent_views' => $readAgente->getResult()[0]['agent_views'] + 1];
           $updateAgent = new Update;
           $updateAgent->exeUpdate('ws_siteviews_agent', $arrAgente, "WHERE agent_name = :name", "name={$this->browser}");
       endif;
   }
   
   
   //cadastra usuario na tabela;
   private function setUsuario() {
       $sesOnline = $_SESSION['useronline'];
       $sesOnline['agent_name'] = $this->browser;
       $userCreate = new Create;
       $userCreate->exeCreate('ws_siteviews_online', $sesOnline);
   }
   
   
   private function usuarioUpdate(){
       $arrOnline = [
           'online_endview'=> $_SESSION['useronline']['online_endview'],
           'online_url' => $_SESSION['useronline']['online_url']
           
       ];
         $userUpdate =  new Update;
         $userUpdate->exeUpdate('ws_siteviews_online', $arrOnline, "WHERE online_session = :session", "session={$_SESSION['useronline']['online_session']}");
                 
         if(!$userUpdate->getRowCount()):
             $readSes = new Read;
             $readSes->exeRead('ws_siteviews_online' , "WHERE online_session = :ses", "ses={$_SESSION['useronline']['online_session']}");
             if(!$readSes->getRowCount()):
                 $this->setUsuario();
             endif;
         endif;
               
   }
}
