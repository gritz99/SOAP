<?php
class WpProQuiz_Controller_Preview extends WpProQuiz_Controller_Controller {
	
	public function route() {
		
		wp_enqueue_script(
			'wpProQuiz_fron_javascript', 
			plugins_url('js/wpProQuiz_front.min.js', WPPROQUIZ_FILE),
			array('jquery', 'jquery-ui-sortable'),
			WPPROQUIZ_VERSION
		);
		
		wp_enqueue_style(
			'wpProQuiz_front_style', 
			plugins_url('css/wpProQuiz_front.min.css', WPPROQUIZ_FILE),
			array(),
			WPPROQUIZ_VERSION
		);
		
		$this->showAction($_GET['id']);
	}
	
	public function showAction($id) {
		$view = new WpProQuiz_View_FrontQuiz();
		
		$quizMapper = new WpProQuiz_Model_QuizMapper();
		$questionMapper = new WpProQuiz_Model_QuestionMapper();
		
		$quiz = $quizMapper->fetch($id);
		
		if($quiz->isShowMaxQuestion() && $quiz->getShowMaxQuestionValue() > 0) {
				
			$value = $quiz->getShowMaxQuestionValue();
				
			if($quiz->isShowMaxQuestionPercent()) {
				$count = $questionMapper->count($id);
		
				$value = ceil($count * $value / 100);
			}
				
			$question = $questionMapper->fetchAll($id, true, $value);
				
		} else {
			$question = $questionMapper->fetchAll($id);
		}
		
		$view->quiz = $quiz;
		$view->question = $question;
		$view->show(true);
	}
}