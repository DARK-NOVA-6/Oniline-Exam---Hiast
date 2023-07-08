<?php

class SignupView extends View{
    function render()
    {
        $this->loadHeader('Sign Up');
        $this->loadSignUpPage();
        $this->loadLinks();
        $this->loadFooter();
    }

    function loadSignUpPage(){
        require 'application/views/client_pages/singup.page.php';
    }

    function loadLinks(){
        require 'application/views/links/signup.links.php';
    }
}