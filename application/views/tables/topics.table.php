<?php

class TopicsTableView extends AdminView {
    function render(Array $tColName,Array $tData,int $curTotRow,int $allRow,int $from,int $to){
        $this->buildTopicsTable($tColName, $tData, $curTotRow, $allRow, $from, $to);
        $this->renderPage();
    }
    
    private function buildTopicsTable(Array $tColName,Array $tData,int $curTotRow,int $allRow,int $from,int $to){
        $this->buildTable('Topics',$tColName, $tData, $curTotRow, $allRow, $from, $to);
        return $this;
    }
}