<?php 

class CorrectAnswerItem {
    public function __construct(int $questionId,String $choices,String $answersAsString,int $mark,int $fMark) {
        $this->questionId = $questionId;
        $this->choices = $choices;
        $this->answersAsString = $answersAsString;
        $this->mark = $mark;
        $this->fMark = $fMark;
    }
}