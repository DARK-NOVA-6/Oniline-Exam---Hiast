<?php

class UsersTableView extends AdminView {
    function render(Array $tColName,Array $tData,int $curTotRow,int $allRow,int $from,int $to){
        $this->buildUsersTable($tColName, $tData, $curTotRow, $allRow, $from, $to);
        $this->renderPage();
    }
    
    private function buildUsersTable(Array $tColName,Array $tData,int $curTotRow,int $allRow,int $from,int $to){
        $this->buildTable('Users',$tColName, $tData, $curTotRow, $allRow, $from, $to);
        return $this;
    }
}