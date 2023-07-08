<?php

class CoursesTableView extends AdminView {
    function render(Array $tColName,Array $tData,int $curTotRow,int $allRow,int $from,int $to){
        $this->buildCoursesTable( $tColName, $tData, $curTotRow, $allRow, $from, $to);
        $this->renderPage();
    }

    private function buildCoursesTable(Array $tColName,Array $tData,int $curTotRow,int $allRow,int $from,int $to)
    {
        $this->buildTable('Courses',$tColName,$tData, $curTotRow, $allRow, $from, $to);
        return $this;
    }
}