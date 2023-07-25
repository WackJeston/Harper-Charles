<?php

namespace App;

use Illuminate\Http\Request;

class DataForm
{
	protected $form;

  public function __construct(Request $request, string $action, string $submit = 'Save', string $js = null)
	{
		$this->form = [
			'token' => $request->session()->token(),
			'action' => $action,
			'submit' => $submit,
			'js' => $js,
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
		<form id="%s" class="data-form web-box dk" action="%s" method="POST" enctype="multipart/form-data">', str_replace(['(', ')'], '', $this->form['js']), $this->form['action']);

			$html .= sprintf('
			<input type="hidden" name="_token" value="%s" />', $this->form['token']);

			foreach ($this->form['inputs'] as $i => $input) {
				switch ($input['type']) {
					case 'text':
					case 'email':
					case 'tel':
						$html .= sprintf('
						<label for="%1$s">%2$s%8$s</label>
						<input id="%1$s" type="%9$s" name="%1$s" value="%3$s" minlength="%4$s" maxlength="%5$s" placeholder="%6$s" %7$s />',
							$input['name'],
							$input['label'],
							$input['value'],
							$input['min'],
							$input['max'],
							$input['placeholder'],
							$input['required'] ? 'required' : '',
							$input['required'] ? '<span> *</span>' : '',
							$input['type'],
						);
						break;
					
					case 'password':
						$html .= sprintf('
						<label for="%1$s">%2$s%8$s</label>
						<label for="password" class="show-password">
							<i class="fa-solid fa-eye"></i>
						</label>
						<input id="%1$s" class="password-input" type="password" name="%1$s" value="%3$s" minlength="%4$s" maxlength="%5$s" placeholder="%6$s" %7$s />',
							$input['name'],
							$input['label'],
							$input['value'],
							$input['min'],
							$input['max'],
							$input['placeholder'],
							$input['required'] ? 'required' : '',
							$input['required'] ? '<span> *</span>' : '',
							$i
						);
						break;

					case 'checkbox':
						$html .= sprintf('
						<div class="checkbox-container">
							<input id="%1$s" type="checkbox" name="%1$s" value="%3$s" minlength="%4$s" maxlength="%5$s" placeholder="%6$s" %7$s />
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

			$js = $this->form['js'] ? sprintf('onclick="%s"', $this->form['js']) : '';

			if ($this->form['js'] == null) {
				$html .= sprintf('
				<button class="submit" type="submit" %s>%s</button>', $js, $this->form['submit']);
			} else {
				$html .= sprintf('
				<button class="submit" type="button" %s>%s</button>', $js, $this->form['submit']);
			}

		$html .= '
		</form>';

		$return = [
			'html' => trim(preg_replace('/\s\s+/', '', $html)),
		];

		return $return;
	}
}