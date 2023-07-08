<?php

class PastTestsTableView extends TCAdminView {
    function render(Array $tColName,Array $tData,int $curTotRow,int $allRow,int $from,int $to){
        $this->buildPastTestsTable( $tColName, $tData, $curTotRow, $allRow, $from, $to);
        $this->renderPage();
    }

    private function buildPastTestsTable(Array $tColName,Array $tData,int $curTotRow,int $allRow,int $from,int $to)
    {
        $this->buildTable('Past Tests',$tColName,$tData, $curTotRow, $allRow, $from, $to);
        return $this;
    }
}