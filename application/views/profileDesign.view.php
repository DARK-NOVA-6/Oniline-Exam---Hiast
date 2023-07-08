<?php

class ProfileDesignView extends View{
    function render(ProfileItem ...$profileItem)
    {
        $this->buildItems(...$profileItem);
        $this->loadHeader('Profile Edit');
        $this->loadLinks();
        $this->loadProfileDesinePage();
        $this->loadFooter();
    }

    function loadProfileDesinePage(){
        $profileArr = $this->profileArr;
        require 'application/views/client_pages/profile_design.page.php';
    }

    function loadLinks(){
        require 'application/views/links/profile_design.links.php';
    }

    function buildItems(ProfileItem ...$profileItem){
        $this->profileArr = array();
        $this->profileArr[0] = [];
        $this->profileArr[1] = [];
        $this->profileArr[2] = [];
        foreach ($profileItem as $pi) {
            if($pi->posY!=null)
                $this->profileArr[$pi->posY][$pi->posX]= $pi;
        }
        foreach ($profileItem as $pi) {
            if($pi->posY==null)
                $this->profileArr[0][]= $pi;
        }
        sort($this->profileArr[0]);
        sort($this->profileArr[1]);
        sort($this->profileArr[2]);
    }
}