<?php

namespace Core\Form;

class BootstrapForm extends Form{
	
	protected function surround($html){
		return '<fieldset class="form-group">' . $html . '</fieldset>';
	}
	
	public function input($name = null, $label = null, $options = []){
		$type = isset($options['type']) ? $options['type'] : 'text';
		$placeholder = isset($options['placeholder']) ? " placeholder=\"{$options['placeholder']}\"" : null;
		$id = isset($options['id']) ? " id=\"{$options['id']}\"" : null;
		$required = isset($options['required']) ? ' required=""' : null;
		$help_text = isset($options['help-text']) ? "<small class=\"text-muted\">{$options['help-text']}</small>" : null;
		$disabled = isset($options['disabled']) ? ' disabled' : null;
		$value = !isset($options['value']) ? $this->getValue($name) : (($options['value'] === false) ? null : $options['value']);
		return $this->surround((($label != null) ? "<label>{$label}</label>" : null) . '<input class="form-control" type="' . $type . '" name="' . $name . '" value="' . $value . '"' . $placeholder . $id . $required . $disabled . ' />' . $help_text);
	}
	
	public function checkbox($name = null, $value = 1, $text = 'Se souvenir de moi', $checked = null){
		return '
		<p>
			<label class="c-input c-checkbox">
				<input type="checkbox" name="' . $name . '" value="' . $value . '"' . (($checked) ? ' ' . $checked : null) . '>
				<span class="c-indicator"></span>
				' . $text . '
			</label>
		</p>
		';
	}
}
