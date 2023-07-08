<?php

class TestView
	extends View {
	function render (string $startD, int $duration, array $answers, int $fullMark, QuestionItem ...$questions) {
		$this->buildTestQuesArr($startD, $duration, $answers, $fullMark, ...$questions);
		$this->loadHeader('Test');
		$this->loadLinks();
		$this->loadTestPage();
		$this->loadFooter();
	}
	
	private function loadTestPage () {
		$questions = $this->questions;
		$answers   = $this->answers;
		$startDate = $this->startDate;
		$fullMark  = $this->fullMark;
		
		if (!isset($_COOKIE['curQ'])) {
			setcookie('curQ', 1, $this->duration);
			$curQuestions = 1;
		} else {
			$curQuestions = $_COOKIE['curQ'];
		}
		
		unset($_COOKIE['examFinished']);
		
		
		if (!isset($_COOKIE['examFinished'])) {
			setcookie('examFinished', false, $this->duration);
			$examFinished = false;
		} else {
			$examFinished = $_COOKIE['examFinished'];
		}
		
		unset($_COOKIE['fTime']);
		
		if (!isset($_COOKIE['fTime'])) {
			setcookie('fTime', '00:00:00', $this->duration);
			$fTime = '00:00:00';
		} else {
			$fTime = $_COOKIE['fTime'];
		}
		
		$duration = $this->duration;
		require 'application/views/client_pages/test.page.php';
	}
	
	private function loadLinks () {
		require 'application/views/links/test.links.php';
	}
	
	private function buildTestQuesArr (string       $startD,
	                                   int          $duration,
	                                   array        $answers,
	                                   int          $fullMark,
	                                   QuestionItem ...$questions
	) {
		$this->startDate = $startD;
		$this->answers   = $answers;
		$this->fullMark  = $fullMark;
		$this->duration  = $duration;
		foreach ($questions as $q) {
			str_replace("\n", "<br/>", $q->text);
			foreach ($q->options as $o) {
				str_replace("\n", "<br/>", $o->text);
			}
		}
		$this->questions = $questions;
	}
	
}