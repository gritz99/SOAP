<?php
class WpProQuiz_View_View {
	
	private $data = array();
	
	public function __set($name, $value) {
		$this->data[$name] = $value;	
	}
	
	public function __get($name) {
		if(isset($this->data[$name]))
			return $this->data[$name];
	}
	
	public static function admin_notices($msg, $type = 'error') {
		if($type === 'info')
			echo '<div class="updated"><p><strong>'.$msg.'</strong></p></div>';
		else
			echo '<div class="error"><p><strong>'.$msg.'</strong></p></div>';
	}
	
	public function redirect($url) {
		
	}
}