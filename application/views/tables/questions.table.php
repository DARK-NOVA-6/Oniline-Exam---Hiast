<?php

class QuestionsTableView extends AdminView {
    function render(Array $tColName,Array $tData,int $curTotRow,int $allRow,int $from,int $to){
        $this->buildQuestionsTable($tColName, $tData, $curTotRow, $allRow, $from, $to);
        $this->renderPage();
    }

    private function buildQuestionsTable(Array $tColName,Array $tData,int $curTotRow,int $allRow,int $from,int $to){
        $this->buildTable('Questions',$tColName, $tData, $curTotRow, $allRow, $from, $to);
        return $this;
    }
}