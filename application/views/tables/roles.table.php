<?php

class RolesTableView extends AdminView {
    function render(Array $tColName,Array $tData,int $curTotRow,int $allRow,int $from,int $to){
        $this->buildRolesTable($tColName, $tData, $curTotRow, $allRow, $from, $to);
        $this->renderPage();
    }

    private function buildRolesTable(Array $tColName,Array $tData,int $curTotRow,int $allRow,int $from,int $to){
        $this->buildTable('Roles',$tColName, $tData, $curTotRow, $allRow, $from, $to);
        return $this;
    }
}