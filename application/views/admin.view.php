<?php

abstract class AdminView extends View{
    abstract function render(Array $tColName,Array $tData,int $curTotRow,int $allRow,int $from,int $to);

    protected function renderPage(){
        $this->loadHeader('Admin Page');
        $this->loadLinks();
        $this->loadAdminPage();
        $this->loadFooter();
    }

    function loadAdminPage(){
        $tableName = $this->tableName;
        $colName = $this->colName;
        $data = $this->data;
        $curTotRow = $this->curTotRow;
        $allRow = $this->allRow;
        $from = $this->from;
        $to = $this->to;
        require 'application/views/client_pages/admin.page.php';
    }

    protected function buildTable(
        String $tName,
        Array $tColName,
        Array $tData, 
        int $curTotRow, 
        int $allRow,
        int $from,
        int $to
        ){
        $this->tableName = $tName;
        $this->colName = $tColName;
        $this->data = $tData;
        $this->curTotRow = $curTotRow;
        $this->allRow = $allRow;
        $this->from = $from;
        $this->to = $to;
    }

    function loadLinks(){
        require 'application/views/links/admin_Page.links.php';
    }
}