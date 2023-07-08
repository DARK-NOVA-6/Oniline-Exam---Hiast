<?php  

class DashboardListView extends View{
    function render(){
        $this->loadHeader('Dashboard');
        $this->loadLinks();
        $this->loadHomePage();
        $this->loadFooter();
    }

    function loadHomePage(){
        $roleTitle = $_SESSION['role_title'];
        require 'application/views/client_pages/dashboard_list.page.php';
    }

    function loadLinks(){
        require 'application/views/links/dashboard_list.links.php';
    }

}