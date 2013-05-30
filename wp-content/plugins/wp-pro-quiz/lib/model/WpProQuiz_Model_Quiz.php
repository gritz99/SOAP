<?php
class WpProQuiz_Model_Quiz extends WpProQuiz_Model_Model {
	
	const QUIZ_RUN_ONCE_TYPE_ALL = 1;
	const QUIZ_RUN_ONCE_TYPE_ONLY_USER = 2;
	const QUIZ_RUN_ONCE_TYPE_ONLY_ANONYM = 3;
	
	protected $_id;
	protected $_name;
	protected $_text;
	protected $_resultText;
	protected $_titleHidden;
	protected $_btnRestartQuizHidden = false;
	protected $_btnViewQuestionHidden = false;
	protected $_questionRandom;
	protected $_answerRandom;
	protected $_timeLimit = 0;
	protected $_backButton;
	protected $_checkAnswer;
	protected $_statisticsOn;
	protected $_statisticsIpLock;
	protected $_resultGradeEnabled = false;
	protected $_showPoints = false;
	protected $_quizRunOnce = false;
	protected $_quizRunOnceType = 0;
	protected $_quizRunOnceCookie = false;
	protected $_quizRunOnceTime = 0;
	protected $_questionOnSinglePage = false;
	protected $_numberedAnswer = false;
	protected $_hideAnswerMessageBox = false;
	protected $_disabledAnswerMark = false;
	protected $_showMaxQuestion = false;
	protected $_showMaxQuestionValue = 1;
	protected $_showMaxQuestionPercent = false;
	
	public function getId() {
		return $this->_id;
	}
	
	public function setId($id) {
		$this->_id = $id;
		return $this;
	}
	
	public function getName() {
		return $this->_name;
	}
	
	public function setName($name) {
		$this->_name = $name;
		return $this;
	}

	public function setText($text) {
		$this->_text = $text;
		return $this;
	}
	
	public function getText() {
		return $this->_text;
	}
	
	public function setResultText($_resultText) {
		$this->_resultText = $_resultText;
		return $this;
	}
	
	public function getResultText() {
		return $this->_resultText;
	}
	
	public function setTitleHidden($_titleHidden) {
		$this->_titleHidden = (bool)$_titleHidden;
		return $this;
	}
	
	public function isTitleHidden() {
		return $this->_titleHidden;
	}
	
	public function setQuestionRandom($_questionRandom) {
		$this->_questionRandom = (bool)$_questionRandom;
		return $this;
	}
	
	public function isQuestionRandom() {
		return $this->_questionRandom;
	}

	public function setAnswerRandom($_answerRandom) {
		$this->_answerRandom = (bool)$_answerRandom;
		return $this;
	}
	
	public function isAnswerRandom() {
		return $this->_answerRandom;
	}
	
	public function setTimeLimit($_timeLimit) {
		$this->_timeLimit = $_timeLimit;
		return $this;
	}
	
	public function getTimeLimit() {
		return $this->_timeLimit;
	}
	
	public function setBackButton($_backButton) {
		$this->_backButton = (bool)$_backButton;
		return $this;
	}
	
	public function isBackButton() {
		return $this->_backButton;
	}
	
	public function setCheckAnswer($_checkAnswer) {
		$this->_checkAnswer = (bool)$_checkAnswer;
		return $this;
	}
	
	public function isCheckAnswer() {
		return $this->_checkAnswer;
	}
	
	public function setStatisticsOn($_statisticsOn) {
		$this->_statisticsOn = (bool)$_statisticsOn;
		return $this;
	}
	
	public function isStatisticsOn() {
		return $this->_statisticsOn;
	}
	
	public function setStatisticsIpLock($_statisticsIpLock) {
		$this->_statisticsIpLock = (int)$_statisticsIpLock;
		return $this;
	}
	
	public function getStatisticsIpLock() {
		return $this->_statisticsIpLock;
	}
	
	public function setResultGradeEnabled($_resultGradeEnabled) {
		$this->_resultGradeEnabled = (bool)$_resultGradeEnabled;
		return $this;
	}
	
	public function isResultGradeEnabled() {
		return $this->_resultGradeEnabled;
	}
	
	public function setShowPoints($_showPoints) {
		$this->_showPoints = (bool)$_showPoints;
		return $this;
	}
	
	public function isShowPoints() {
		return $this->_showPoints;
	}
	
	public function fetchSumQuestionPoints() {
		$m = new WpProQuiz_Model_QuizMapper();
		
		return $m->sumQuestionPoints($this->_id);
	}
	
	public function fetchCountQuestions() {
		$m = new WpProQuiz_Model_QuizMapper();
	
		return $m->countQuestion($this->_id);
	}
	
	public function setBtnRestartQuizHidden($_btnRestartQuizHidden) {
		$this->_btnRestartQuizHidden = (bool)$_btnRestartQuizHidden;
		return $this;
	}
	
	public function isBtnRestartQuizHidden() {
		return $this->_btnRestartQuizHidden;
	}
	
	public function setBtnViewQuestionHidden($_btnViewQuestionHidden) {
		$this->_btnViewQuestionHidden = (bool)$_btnViewQuestionHidden;
		return $this;
	}
	
	public function isBtnViewQuestionHidden() {
		return $this->_btnViewQuestionHidden;
	}
	
	public function setQuizRunOnce($_quizRunOnce) {
		$this->_quizRunOnce = (bool)$_quizRunOnce;
		return $this;
	}
	
	public function isQuizRunOnce() {
		return $this->_quizRunOnce;
	}
	
	public function setQuizRunOnceCookie($_quizRunOnceCookie) {
		$this->_quizRunOnceCookie = (bool)$_quizRunOnceCookie;
		return $this;
	}
	
	public function isQuizRunOnceCookie() {
		return $this->_quizRunOnceCookie;
	}
	
	public function setQuizRunOnceType($_quizRunOnceType) {
		$this->_quizRunOnceType = (int)$_quizRunOnceType;
		return $this;
	}
	
	public function getQuizRunOnceType() {
		return $this->_quizRunOnceType;
	}
	
	public function setQuizRunOnceTime($_quizRunOnceTime) {
		$this->_quizRunOnceTime = (int)$_quizRunOnceTime;
		return $this;
	}
	
	public function getQuizRunOnceTime() {
		return $this->_quizRunOnceTime;
	}
	
	public function setQuestionOnSinglePage($_questionOnSinglePage) {
		$this->_questionOnSinglePage = (bool)$_questionOnSinglePage;
		return $this;
	}
	
	public function isQuestionOnSinglePage() {
		return $this->_questionOnSinglePage;
	}
	
	public function setNumberedAnswer($_numberedAnswer) {
		$this->_numberedAnswer = (bool)$_numberedAnswer;
		return $this;
	}
	
	public function isNumberedAnswer() {
		return $this->_numberedAnswer;
	}
	
	public function setHideAnswerMessageBox($_hideAnswerMessageBox) {
		$this->_hideAnswerMessageBox = (bool)$_hideAnswerMessageBox;
		return $this;
	}
	
	public function isHideAnswerMessageBox() {
		return $this->_hideAnswerMessageBox;
	}
	
	public function setDisabledAnswerMark($_disabledAnswerMark) {
		$this->_disabledAnswerMark = (bool)$_disabledAnswerMark;
		return $this;
	}
	
	public function isDisabledAnswerMark() {
		return $this->_disabledAnswerMark;
	}
	
	public function setShowMaxQuestion($_showMaxQuestion) {
		$this->_showMaxQuestion = (bool)$_showMaxQuestion;
		return $this;
	}
	
	public function isShowMaxQuestion() {
		return $this->_showMaxQuestion;
	}
	
	public function setShowMaxQuestionValue($_showMaxQuestionValue) {
		$this->_showMaxQuestionValue = (int)$_showMaxQuestionValue;
		return $this;
	}
	
	public function getShowMaxQuestionValue() {
		return $this->_showMaxQuestionValue;
	}
	
	public function setShowMaxQuestionPercent($_showMaxQuestionPercent) {
		$this->_showMaxQuestionPercent = (bool)$_showMaxQuestionPercent;
		return $this;
	}
	
	public function isShowMaxQuestionPercent() {
		return $this->_showMaxQuestionPercent;
	}
}