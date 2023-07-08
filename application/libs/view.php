<?php

class View{
    protected function loadHeader($pageTitle){
        $this->loadMeta($pageTitle);
        $this->loadMainLinks();
        $this->loadVisibleHeader();
    }   

    private function loadMeta($pageTitle){
        require 'application/views/client_pages/_templates/meta.php';
    }

    private function loadVisibleHeader(){        
        $userName = $_SESSION['user_name'];
        $roleTitle = $_SESSION['role_title'];
        $testCenterName = $_SESSION['test_center_name'];
        $photoLink  = $_SESSION['photo_link'];
        require 'application/views/client_pages/_templates/visibleHeader.php';
    }

    private function loadMainLinks(){
        require 'application/views/links/main.links.php';
    }
    
    protected function loadFooter(){
        require 'application/views/client_pages/_templates/footer.php';
    }
}