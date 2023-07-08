<?php

class StudentsTableView extends TCAdminView {
    function render(Array $tColName,Array $tData,int $curTotRow,int $allRow,int $from,int $to){
        $this->buildStudentsTable( $tColName, $tData, $curTotRow, $allRow, $from, $to);
        $this->renderPage();
    }

    private function buildStudentsTable(Array $tColName,Array $tData,int $curTotRow,int $allRow,int $from,int $to)
    {
        $this->buildTable('Students',$tColName,$tData, $curTotRow, $allRow, $from, $to);
        return $this;
    }
}