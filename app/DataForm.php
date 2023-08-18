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
			'id' => strval($js == null ? uniqid() : explode('(', $js)[0]),
			'inputs' => [],
		];
	}

	public function setTitle(string $title) {
		$this->form['title'] = $title;
	}

	public function addInput(string $type, string $name, string $label = null, $value = null, int $max = null, int $min = null, bool $required = false, string $placeholder = null) {
		$this->form['inputs'][] = [
			'type' => $type,
			'name' => $name,
			'label' => $label,
			'value' => $value,
			'max' => $max,
			'min' => $min,
			'required' => $required,
			'placeholder' => $placeholder,
			'options' => [],
			'optionspre' => [],
		];
	}

	public function populateOptions(string $ref, array $options) {
		foreach ($this->form['inputs'] as $i => $input) {
			if ($input['name'] == $ref && $input['type'] == 'select') {
				foreach ($options as $i2 => $option) {
					$this->form['inputs'][$i]['optionspre'][] = [
						'value' => $option->value,
						'label' => $option->label,
						'parent' => $option->parent ?? null,
						'active' => $option->active ?? 0,
					];
				}
			}
		}
	}

	public function calculate() {
		foreach ($this->form['inputs'] as $i => $input) {
			if ($input['type'] == 'select') {
				foreach ($input['optionspre'] as $i2 => $option) {
					if (!$option['active']) {
						if ($option['parent'] == null) {
							$this->form['inputs'][$i]['options'][] = [
								'value' => $option['value'],
								'label' => $option['label'],
							];
						} else {
							$this->form['inputs'][$i]['options'][$option['parent']][] = [
								'value' => $option['value'],
								'label' => $option['label'],
							];
						}
					}
				}
			}
		}
	}

	public function render() {
		self::calculate();
		$html = '';

		$html .= sprintf('
		<form id="%s" class="data-form web-box dk" action="%s" method="POST" enctype="multipart/form-data">', $this->form['id'], $this->form['action']);

			if (!empty($this->form['title'])) {
				$html .= sprintf('
				<h3 class="form-title">%s</h3>', $this->form['title']);
			}

			$html .= sprintf('
			<input type="hidden" name="_token" value="%s" />', $this->form['token']);

			foreach ($this->form['inputs'] as $i => $input) {
				switch ($input['type']) {
					case 'text':
					case 'email':
					case 'tel':
					case 'url':
						$html .= sprintf('
						<label for="%1$s">%2$s%8$s</label>
						<input type="%9$s" id="%1$s" name="%1$s" value="%3$s" minlength="%4$s" maxlength="%5$s" placeholder="%6$s" %7$s />',
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

					case 'textarea':
						$html .= sprintf('
						<label for="%1$s">%2$s%8$s</label>
						<textarea type="text" id="%1$s" name="%1$s" minlength="%4$s" maxlength="%5$s" placeholder="%6$s" %7$s />%3$s</textarea>',
							$input['name'],
							$input['label'],
							$input['value'],
							$input['min'],
							$input['max'],
							$input['placeholder'],
							$input['required'] ? 'required' : '',
							$input['required'] ? '<span> *</span>' : '',
						);
						break;
					
					case 'num':
					case 'number':
						$html .= sprintf('
						<label for="%1$s">%2$s%8$s</label>
						<input type="number" id="%1$s" name="%1$s" value="%3$s" step="any" %4$s %5$s placeholder="%6$s" %7$s />',
							$input['name'],
							$input['label'],
							$input['value'],
							$input['min'] ? sprintf('min="%s"', $input['min']) : '',
							$input['max'] ? sprintf('maxlength="%s"', $input['max']) : '',
							$input['placeholder'],
							$input['required'] ? 'required' : '',
							$input['required'] ? '<span> *</span>' : '',
						);
						break;

					case 'phone':
					case 'tel':
						$html .= sprintf('
						<label for="%1$s">%2$s%8$s</label>
						<input type="tel" id="%1$s" name="%1$s" value="%3$s" step="any" %4$s %5$s placeholder="%6$s" %7$s />',
							$input['name'],
							$input['label'],
							$input['value'],
							$input['min'] ? sprintf('min="%s"', $input['min']) : '',
							$input['max'] ? sprintf('maxlength="%s"', $input['max']) : '',
							$input['placeholder'],
							$input['required'] ? 'required' : '',
							$input['required'] ? '<span> *</span>' : '',
						);
						break;
					
					case 'password':
						$html .= sprintf('
						<label for="%1$s">%2$s%8$s</label>
						<label for="password" class="show-password">
							<i class="fa-solid fa-eye"></i>
						</label>
						<input class="password-input" type="password" id="%1$s" name="%1$s" value="%3$s" minlength="%4$s" maxlength="%5$s" placeholder="%6$s" %7$s />',
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
							<input type="checkbox" id="%1$s" name="%1$s" %3$s minlength="%4$s" maxlength="%5$s" placeholder="%6$s" %7$s />
							<label for="%1$s">%2$s%8$s</label>
						</div>',
							$input['name'],
							$input['label'],
							$input['value'] ? 'checked' : '',
							$input['min'],
							$input['max'],
							$input['placeholder'],
							$input['required'] ? 'required' : '',
							$input['required'] ? '<span> *</span>' : ''
						);
						break;

					case 'file':
						$html .= sprintf('
						<label for="%1$s">%2$s%6$s</label>
						<label class="file-input-label" for="%1$s">
							<input class="file-input" type="file" id="%1$s" name="%1$s" value="%3$s" min="1" accept="image/jpg/png/svg" placeholder="%4$s" %5$s>
							<div>No file selected</div>
						</label>',
							$input['name'],
							$input['label'],
							$input['value'],
							$input['placeholder'],
							$input['required'] ? 'required' : '',
							$input['required'] ? '<span> *</span>' : ''
						);
						break;
					
					case 'select':
						$html .= sprintf('
						<label for="%1$s">%2$s%8$s</label>
						<select id="%1$s" name="%1$s" value="%3$s" placeholder="%6$s" %7$s>
							<option></option>',
							$input['name'],
							$input['label'],
							$input['value'],
							$input['min'],
							$input['max'],
							$input['placeholder'],
							$input['required'] ? 'required' : '',
							$input['required'] ? '<span> *</span>' : ''
						);

							// dd($input['options']);

							foreach ($input['options'] as $i2 => $option) {
								if (is_numeric($i2)) {
									$html .= sprintf('
									<option value="%1$s">%2$s (#%1$s)</option>',
										$option['value'],
										$option['label'],
									);
								} else {
									$html .= sprintf('
									<optgroup label="%s">', $i2
									);

										foreach ($option as $i3 => $option2) {
											$html .= sprintf('
											<option value="%1$s">%2$s (#%1$s)</option>',
												$option2['value'],
												$option2['label'],
											);
										}

									$html .= '
									</optgroup>';
								}
							}

						$html .= '
						</select>';
						break;
				}
			}

			if ($this->form['js'] == null) {
				$html .= sprintf('
				<button class="submit" type="submit">%s</button>', $this->form['submit']);
			} else {
				$html .= sprintf('
				<button class="submit" type="button" onclick="%s">%s</button>', $this->form['js'], $this->form['submit']);
			}

		$html .= '
		</form>';

		$return = [
			'html' => trim(preg_replace('/\s\s+/', '', $html)),
		];

		return $return;
	}
}
