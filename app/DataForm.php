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

	public function addInput(string $type, string $name, string $label = null, $value = null, int $max = null, int $min = null, bool $required = false, string $placeholder = null, array $attributes = [], bool $emptyValue = true) {
		$this->form['inputs'][] = [
			'type' => $type,
			'name' => in_array('multiple', $attributes) ? $name . '[]' : $name,
			'label' => $label,
			'value' => $value,
			'max' => $max,
			'min' => $min,
			'required' => $required,
			'placeholder' => $placeholder,
			'attributes' => implode(' ', $attributes),
			'options' => [],
			'optionspre' => [],
			'emptyValue' => $emptyValue,
		];
	}

	public function populateOptions(string $ref, array $options, bool $showValue = true) {
		foreach ($this->form['inputs'] as $i => $input) {
			if ($input['name'] == $ref && in_array($input['type'], ['select', 'radio'])) {
				foreach ($options as $i2 => $option) {
					if (is_array($option)) {
						$this->form['inputs'][$i]['optionspre'][] = [
							'value' => $option['value'],
							'label' => $showValue ? sprintf('%1$s (#%2$s)', $option['label'], $option['value']) : $option['label'],
							'parent' => $option['parent'] ?? null,
							'active' => $option['active'] ?? 0,
						];
					} else {
						$this->form['inputs'][$i]['optionspre'][] = [
							'value' => $option->value,
							'label' => $showValue ? sprintf('%1$s (#%2$s)', $option->label, $option->value) : $option->label,
							'parent' => $option->parent ?? null,
							'active' => $option->active ?? 0,
						];
					}
				}
			}
		}
	}

	public function calculate() {
		foreach ($this->form['inputs'] as $i => $input) {
			if (in_array($input['type'], ['select', 'radio'])) {
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
					case 'url':
						$html .= sprintf('
						<label for="%1$s">%2$s%8$s</label>
						<input type="%9$s" id="%1$s" name="%1$s" value="%3$s" minlength="%4$s" maxlength="%5$s" placeholder="%6$s" %7$s %10$s />',
							$input['name'],
							$input['label'],
							$input['value'],
							$input['min'],
							$input['max'],
							$input['placeholder'],
							$input['required'] ? 'required' : '',
							$input['required'] ? '<span class="red"> *</span>' : '',
							$input['type'],
							$input['attributes']
						);
						break;

					case 'date':
						$html .= sprintf('
						<label for="%1$s">%2$s%8$s</label>
						<input class="date-input" type="date" id="%1$s" name="%1$s" value="%3$s" minlength="%4$s" maxlength="%5$s" placeholder="%6$s" %7$s %9$s />',
							$input['name'],
							$input['label'],
							date('Y-m-d', strtotime($input['value'])),
							$input['min'],
							$input['max'],
							$input['placeholder'],
							$input['required'] ? 'required' : '',
							$input['required'] ? '<span class="red"> *</span>' : '',
							$input['attributes']
						);
						break;

					case 'datetime':
						$html .= sprintf('
						<label for="%1$s">%2$s%8$s</label>
						<input class="date-input" type="datetime-local" id="%1$s" name="%1$s" value="%3$s" minlength="%4$s" maxlength="%5$s" placeholder="%6$s" %7$s %9$s />',
							$input['name'],
							$input['label'],
							date('Y-m-d H:i:s', strtotime($input['value'])),
							$input['min'],
							$input['max'],
							$input['placeholder'],
							$input['required'] ? 'required' : '',
							$input['required'] ? '<span class="red"> *</span>' : '',
							$input['attributes']
						);
						break;
					
					case 'time':
						$html .= sprintf('
						<label for="%1$s">%2$s%8$s</label>
						<input class="date-input" type="time" id="%1$s" name="%1$s" value="%3$s" minlength="%4$s" maxlength="%5$s" placeholder="%6$s" %7$s %9$s />',
							$input['name'],
							$input['label'],
							date('H:i:s', strtotime($input['value'])),
							$input['min'],
							$input['max'],
							$input['placeholder'],
							$input['required'] ? 'required' : '',
							$input['required'] ? '<span class="red"> *</span>' : '',
							$input['attributes']
						);
						break;

					case 'textarea':
						$html .= sprintf('
						<label for="%1$s">%2$s%8$s</label>
						<textarea type="text" id="%1$s" name="%1$s" minlength="%4$s" maxlength="%5$s" placeholder="%6$s" %7$s %9$s />%3$s</textarea>',
							$input['name'],
							$input['label'],
							$input['value'],
							$input['min'],
							$input['max'],
							$input['placeholder'],
							$input['required'] ? 'required' : '',
							$input['required'] ? '<span class="red"> *</span>' : '',
							$input['attributes']
						);
						break;
					
					case 'num':
					case 'number':
						$html .= sprintf('
						<label for="%1$s">%2$s%8$s</label>
						<input type="number" id="%1$s" name="%1$s" value="%3$s" step="any" %4$s %5$s placeholder="%6$s" %7$s %9$s />',
							$input['name'],
							$input['label'],
							$input['value'],
							$input['min'] ? sprintf('min="%s"', $input['min']) : '',
							$input['max'] ? sprintf('maxlength="%s"', $input['max']) : '',
							$input['placeholder'],
							$input['required'] ? 'required' : '',
							$input['required'] ? '<span class="red"> *</span>' : '',
							$input['attributes']
						);
						break;

					case 'phone':
					case 'tel':
						$html .= sprintf('
						<label for="%1$s">%2$s%8$s</label>
						<input type="tel" id="%1$s" name="%1$s" value="%3$s" step="any" %4$s %5$s placeholder="%6$s" %7$s %9$s />',
							$input['name'],
							$input['label'],
							$input['value'],
							$input['min'] ? sprintf('min="%s"', $input['min']) : '',
							$input['max'] ? sprintf('maxlength="%s"', $input['max']) : '',
							$input['placeholder'],
							$input['required'] ? 'required' : '',
							$input['required'] ? '<span class="red"> *</span>' : '',
							$input['attributes']
						);
						break;
					
					case 'password':
						$html .= sprintf('
						<label for="%1$s">%2$s%8$s</label>
						<label for="password" class="show-password">
							<i class="fa-solid fa-eye"></i>
						</label>
						<input class="password-input" type="password" id="%1$s" name="%1$s" value="%3$s" minlength="%4$s" maxlength="%5$s" placeholder="%6$s" %7$s %10$s />',
							$input['name'],
							$input['label'],
							$input['value'],
							$input['min'],
							$input['max'],
							$input['placeholder'],
							$input['required'] ? 'required' : '',
							$input['required'] ? '<span class="red"> *</span>' : '',
							$i,
							$input['attributes']
						);
						break;

					case 'colour':
					case 'color':
						$html .= sprintf('
						<label for="%1$s">%2$s%6$s</label>
						<input class="date-input" type="color" id="%1$s" name="%1$s" value="%3$s" placeholder="%4$s" %5$s %7$s />',
							$input['name'],
							$input['label'],
							$input['value'],
							$input['placeholder'],
							$input['required'] ? 'required' : '',
							$input['required'] ? '<span class="red"> *</span>' : '',
							$input['attributes']
						);
						break;
					
					case 'radio':
						$html .= sprintf('
						<label for="%1$s">%2$s%3$s</label>
						<div class="radio-container">',
							$input['name'],
							$input['label'],
							$input['required'] ? '<span class="red"> *</span>' : ''
						);

							foreach ($input['options'] as $i2 => $option) {
								$html .= sprintf('
								<div>
									<input type="radio" id="%2$s" name="%3$s" value="%1$s" %4$s />
									<label for="%2$s">%2$s</label>
								</div>',
									$option['value'],
									$option['label'],
									$input['name'],
									$input['value'] == $option['value'] ? 'checked' : ''
								);
							}

						$html .= '
						</div>';
						break;

					case 'checkbox':
						$html .= sprintf('
						<div class="checkbox-container">
							<input type="checkbox" id="%1$s" name="%1$s" %3$s minlength="%4$s" maxlength="%5$s" placeholder="%6$s" %7$s %9$s />
							<label for="%1$s">%2$s%8$s</label>
						</div>',
							$input['name'],
							$input['label'],
							$input['value'] ? 'checked' : '',
							$input['min'],
							$input['max'],
							$input['placeholder'],
							$input['required'] ? 'required' : '',
							$input['required'] ? '<span class="red"> *</span>' : '',
							$input['attributes']
						);
						break;

					case 'file':
						$html .= sprintf('
						<label for="%1$s">%2$s%6$s</label>
						<label class="file-input-label" for="%1$s">
							<input class="file-input" type="file" id="%1$s" name="%1$s" value="%3$s" min="1" accept="image/jpg/png/svg/webp" placeholder="%4$s" %5$s %7$s>
							<div>No file selected</div>
						</label>',
							$input['name'],
							$input['label'],
							$input['value'],
							$input['placeholder'],
							$input['required'] ? 'required' : '',
							$input['required'] ? '<span class="red"> *</span>' : '',
							$input['attributes']
						);
						break;
					
					case 'select':
						$html .= sprintf('
						<label for="%1$s">%2$s%5$s</label>
						<select id="%1$s" name="%1$s" placeholder="%3$s" %4$s %6$s>
							%7$s',
							$input['name'],
							$input['label'],
							$input['placeholder'],
							$input['required'] ? 'required' : '',
							$input['required'] ? '<span class="red"> *</span>' : '',
							$input['attributes'],
							$input['emptyValue'] ? '<option></option>' : '',
						);

							foreach ($input['options'] as $i2 => $option) {
								$selected = (isset($option['value']) && $option['value'] == $input['value']) ? 'selected' : '';

								if (is_numeric($i2)) {
									$html .= sprintf('
									<option value="%1$s" %3$s>%2$s</option>',
										$option['value'],
										$option['label'],
										$selected,
									);
								} else {
									$html .= sprintf('
									<optgroup label="%s">', $i2
									);

										foreach ($option as $i3 => $option2) {
											$html .= sprintf('
											<option value="%1$s" %3$s>%2$s</option>',
												$option2['value'],
												$option2['label'],
												$selected,
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
