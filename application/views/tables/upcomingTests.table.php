<?php

class UpcomingTestsTableView extends TCAdminView {
    function render(Array $tColName,Array $tData,int $curTotRow,int $allRow,int $from,int $to){
        $this->buildUpcomingTestsTable( $tColName, $tData, $curTotRow, $allRow, $from, $to);
        $this->renderPage();
    }

    private function buildUpcomingTestsTable(Array $tColName,Array $tData,int $curTotRow,int $allRow,int $from,int $to)
    {
        $this->buildTable('Upcoming Tests',$tColName,$tData, $curTotRow, $allRow, $from, $to);
        return $this;
    }
}