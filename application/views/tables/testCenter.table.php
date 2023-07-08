<?php

class TestCenterTableView extends AdminView {
    function render(Array $tColName,Array $tData,int $curTotRow,int $allRow,int $from,int $to){
        $this->buildTestCenterTable($tColName, $tData, $curTotRow, $allRow, $from, $to);
        $this->renderPage();
    }

    private function buildTestCenterTable(Array $tColName,Array $tData,int $curTotRow,int $allRow,int $from,int $to){
        $this->buildTable('TestCenter',$tColName, $tData, $curTotRow, $allRow, $from, $to);
        return $this;
    }
}