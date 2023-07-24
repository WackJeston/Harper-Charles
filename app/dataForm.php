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

	public function addInput(string $type, string $name, string $label = null, $value = null, int $max = 1, int $min = 1, bool $required = false, string $placeholder = null) {
		$this->form['inputs'][] = [
			'type' => $type,
			'name' => $name,
			'label' => $label,
			'value' => $value,
			'max' => $max,
			'min' => $min,
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
						<label for="%1$s">%2$s%8$s</label>
						<input type="text" name="%1$s" value="%3$s" minlength="%4$s" maxlength="%5$s" placeholder="%6$s" %7$s />',
							$input['name'],
							$input['label'],
							$input['value'],
							$input['min'],
							$input['max'],
							$input['placeholder'],
							$input['required'] ? 'required' : '',
							$input['required'] ? '<span> *</span>' : ''
						);
						break;
					
					case 'password':
						$html .= sprintf('
						<label for="%1$s">%2$s%8$s</label>
						<label for="password" class="show-password">
							<i id="show-password" class="fa-solid fa-eye"></i>
						</label>
						<input type="password" name="%1$s" value="%3$s" minlength="%4$s" maxlength="%5$s" placeholder="%6$s" %7$s />',
							$input['name'],
							$input['label'],
							$input['value'],
							$input['min'],
							$input['max'],
							$input['placeholder'],
							$input['required'] ? 'required' : '',
							$input['required'] ? '<span> *</span>' : ''
						);
						break;

					case 'checkbox':
						$html .= sprintf('
						<div class="checkbox-container">
							<input type="checkbox" name="%1$s" value="%3$s" minlength="%4$s" maxlength="%5$s" placeholder="%6$s" %7$s />
							<label for="%1$s">%2$s%8$s</label>
						</div>',
							$input['name'],
							$input['label'],
							$input['value'],
							$input['min'],
							$input['max'],
							$input['placeholder'],
							$input['required'] ? 'required' : '',
							$input['required'] ? '<span> *</span>' : ''
						);
						break;
					
				}
			}

			$html .= '
			<button class="submit" type="submit">Save</button>';

		$html .= '
		</form>';

		$return = [
			'html' => trim(preg_replace('/\s\s+/', '', $html)),
		];

		return $return;
	}
}