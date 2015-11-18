<?php

namespace Core\Controller;

class Controller{
	
	protected $viewPath;
	protected $template;
	
	protected function render($view, $variables = []){
		ob_start();
		extract($variables);
		require($this->viewPath . $view . '.php');
		$content = ob_get_clean();
		require($this->viewPath . 'templates/' . $this->template . '.php');
	}
	
	public function redirect($location = '/', $page = 'index', $get = null){
		header("Location: {$location}" . (($get != null) ? '?' . (($get == 1) ? 'do' : $get) . '=' . $page : ''));
	}
	
}
