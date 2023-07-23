<?php

namespace App;

use Illuminate\Http\Request;

class DataForm
{
	protected $form;

  public function __construct(Request $request, string $action, string $method = 'POST')
	{
		$this->form = [
			'action' => $action,
			'method' => $method,
			'token' => $request->session()->token(),
			'inputs' => [],
		];
	}

	public function addInput(string $type, string $name, string $label = null, $value = null, bool $required = false, string $placeholder = null) {
		$this->form['inputs'][] = [
			'type' => $type,
			'name' => $name,
			'label' => $label,
			'value' => $value,
			'required' => $required,
			'placeholder' => $placeholder,
		];
	}

	public function render() {
		$html = sprintf('
		<form class="web-box dk" href="%s" method="%s">', $this->form['action'], $this->form['method']);

		$html .= sprintf('
		<input type="hidden" name="_token" value="%s" />', $this->form['token']);

		foreach ($this->form['inputs'] as $key => $input) {
			switch ($input['type']) {
				case 'text':
					$html .= sprintf('
					<label for="%1$s">%2$s%6$s</label>
					<input type="text" name="%1$s" value="%3$s" placeholder="%4$s" %5$s />',
						$input['name'],
						$input['label'],
						$input['value'],
						$input['placeholder'],
						$input['required'] ? 'required' : '',
						$input['required'] ? '<span> *</span>' : ''
					);
					break;
				
				case 'password':
					$html .= sprintf('
					<label for="%1$s">%2$s%6$s</label>
					<label for="password" class="show-password">
						<i id="show-password" class="fa-solid fa-eye"></i>
					</label>
					<input type="password" name="%1$s" value="%3$s" placeholder="%4$s" %5$s />',
						$input['name'],
						$input['label'],
						$input['value'],
						$input['placeholder'],
						$input['required'] ? 'required' : '',
						$input['required'] ? '<span> *</span>' : ''
					);
					break;

				case 'checkbox':
					$html .= sprintf('
					<div class="checkbox-container">
						<label for="%1$s">%2$s%6$s</label>
						<input type="checkbox" name="%1$s" value="%3$s" placeholder="%4$s" %5$s />
					</div>',
						$input['name'],
						$input['label'],
						$input['value'],
						$input['placeholder'],
						$input['required'] ? 'required' : '',
						$input['required'] ? '<span> *</span>' : ''
					);
					break;
				
			}
		}

		$html .= '
		</form>';

		$return = [
			'html' => trim(preg_replace('/\s\s+/', '', $html)),
		];

		return $return;
	}
}