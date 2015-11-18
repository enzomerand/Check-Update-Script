<?php

namespace Core\Form;

class Form {
	private $data;
	public $surround = 'p';
	
	public function __construct($data){
		$this->data = $data;
	}
	
	protected function surround($html){
		return "<{$this->surround}>{$html}</{$this->surround}>";
	}
	
	protected function getValue($index){
		return isset($this->data[$index]) ? $this->data[$index] : null;
	}
	
	public function input($name = null, $label = null, $options = []){
		$type = isset($options['type']) ? $options['type'] : 'text';
		$placeholder = isset($options['placeholder']) ? " placeholder=\"{$options['placeholder']}\"" : null;
		$id = isset($options['id']) ? " id=\"{$options['id']}\"" : null;
		return $this->surround(
		    (($label != null) ? "<label>{$label}</label>" : '') . '
		    <input type="' . $type . '" name="' . $name . '" value="' . $this->getValue($name) . '"' . $placeholder . $id . ' />');
	}
	
	public function captcha($public_key = null){
		if(defined('CAPTCHA_PUBLIC'))
			return '<div class="g-recaptcha" data-sitekey="' . CAPTCHA_PUBLIC . '"></div>';
		else
			return '<div class="g-recaptcha" data-sitekey="' . $public_key . '"></div>';
	}
	
	public function checkbox($name = null, $value = 1, $text = 'Se souvenir de moi', $checked = null){
		return '<input type="checkbox" name="' . $name . '" value="' . $value . '"' . (($checked) ? ' ' . $checked : null) . '> ' . $text;
	}
}
