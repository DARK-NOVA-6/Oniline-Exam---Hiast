<?php  

class homeView extends View{
    function render(){
        $this->loadHeader('Home');
        $this->loadLinks();
        $this->loadHomePage();
        $this->loadFooter();
    }
    function loadHomePage(){
        $roleTitle = $_SESSION['role_title'];
        require 'application/views/client_pages/home.page.php';
    }

    function loadLinks(){
        require 'application/views/links/home.links.php';
    }

}