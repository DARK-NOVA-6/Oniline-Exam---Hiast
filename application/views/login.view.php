<?php

class LoginView extends View{
    function render()
    { 
        $this->loadHeader('Log In');
        $this->loadLoginPage();
        $this->loadLinks();
        $this->loadFooter();
    }

    function loadLoginPage(){
        $roleTitle = $_SESSION['role_title'];
        require 'application/views/client_pages/login.page.php';
    }

    function loadLinks(){
        require 'application/views/links/login.links.php';
    }
}