<?php

class GenerateTestView
	extends View {
	function render (array $answers, QuestionItem ...$questions) {
		$this->buildTestQuesArr( $answers,  ...$questions);
		$this->loadHeader('Test');
		$this->loadLinks();
		$this->loadTestPage();
		$this->loadFooter();
	}
	
	private function loadTestPage () {
		$questions = $this->questions;
		$answers   = $this->answers;
		require 'application/views/client_pages/generate_test.page.php';
	}
	
	private function loadLinks () {
		require 'application/views/links/generate_test.links.php';
	}
	
	private function buildTestQuesArr (array $answers, QuestionItem ...$questions) {
		$this->answers   = $answers;
		foreach ($questions as $q) {
			str_replace("\n", "<br/>", $q->text);
			foreach ($q->options as $o) {
				str_replace("\n", "<br/>", $o->text);
			}
		}
		$this->questions = $questions;
	}
	
}