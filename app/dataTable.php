<?php

namespace App;

Use DB;

class DataTable
{
	protected $table;

  public function __construct(string $ref = 'default', string $primary = 'id')
	{
		$this->table = [
			'ref' => $ref,
			'primary' => $primary,
			'columns' => [],
			'records' => [],
			'buttons' => [],
		];
	}

	public function setQuery(string $query, array $params = []) {
		$query = str_replace('?', '%s', $query);
		$query = vsprintf($query, $params);

		$this->table['records'] = DB::select($query);
	}

	public function addColumn(string $name, string $title = null, int $width = 1, bool $hideMobile = false, string $type = 'default') {
		if ($title == null) {
			$title = $name;
		}

		$this->table['columns'][] = [
			'name' => $name,
			'title' => $title,
			'width' => $width,
			'maxWidth' => $width,
			'mobileMaxWidth' => $width,
			'hideMobile' => $hideMobile,
			'type' => $type,
		];
	}

	public function addButton(string $url, string $icon, string $label = null, string $click = null) {
		$this->table['buttons'][] = [
			'icon' => $icon,
			'label' => $label,
			'click' => $click,
		];

		if (!str_starts_with($url, '/')) {
			$url = '/' . $url;
		}

		if (str_contains(url()->current(), '/admin') && !preg_match('/[A-Z]/', url()->current())) {
			$url = '/admin' . $url;
		}

		foreach ($this->table['records'] as $i => $record) {
			$recordArray = (array) $record;
			$record->buttonLinks[] = str_replace('?', $recordArray[$this->table['primary']], $url);
		}
	}

	public function calculate() {
		$columnWidthCount = 0;
		$mobileColumnWidthCount = 0;
		
		foreach ($this->table['columns'] as $i => $column) {
			if ($column['name'] != 'id') {
				$columnWidthCount += $column['width'];

				if ($column['hideMobile'] == false) {
					$mobileColumnWidthCount += $column['width'];
				}
			}
		}

		foreach ($this->table['columns'] as $i => $column) {
			if ($column['name'] != 'id') {
				$this->table['columns'][$i]['maxWidth'] = round((100 / $columnWidthCount) * $column['width'], 2);

				if ($column['hideMobile'] == false) {
					$this->table['columns'][$i]['mobileMaxWidth'] = round((100 / $mobileColumnWidthCount) * $column['width'], 2);
				};
			}
		}
	}

	public function display(bool $return = false) {
		self::calculate();

		$result = sprintf('
		<table class="web-box" id="table-%s">
			<thead>
				<tr>', $this->table['ref']);

				foreach ($this->table['columns'] as $i => $column) {
					$style = $column['name'] == 'id' ? '50px' : $column['maxWidth'] . '%';

					$result .= sprintf('<th style="width:%s;">%s</th>', $style, $column['title']);
				}

				$result .= '
				</tr>
			</thead>

			<tbody>';

				if (count($this->table['records']) > 0) {
					foreach ($this->table['records'] as $i => $record) {
						$result .= '
						<tr>';
						
						foreach ($this->table['columns'] as $i2 => $column) {
							$style = $column['name'] == 'id' ? '50px' : $column['maxWidth'] . '%';

							$tempResult = $record->{$column['name']};
							
							// Column Type Switch
							switch ($column['type']) {
								case 'currency':
									$tempResult = '£' . $record->{$column['name']};
									break;

								case 'toggle':
									if ($record->{$column['name']} == 1) {
										$tempResult = sprintf('<i class="fa-solid fa-circle-check toggle-true" id="%2$s-%4$s" onclick="toggleButton(\'%1$s\', \'%2$s\', \'%3$s\', \'%4$s\')"></i>', $this->table['ref'], $column['name'], $this->table['primary'], $record->{$this->table['primary']});
									} else {
										$tempResult = sprintf('<i class="fa-solid fa-circle-xmark toggle-false" id="%2$s-%4$s" onclick="toggleButton(\'%1$s\', \'%2$s\', \'%3$s\', \'%4$s\')"></i>', $this->table['ref'], $column['name'], $this->table['primary'], $record->{$this->table['primary']});
									}
									break;
								case 'setPrimary':
									if ($record->{$column['name']} == 1) {
										$tempResult = sprintf('<i class="fa-solid fa-circle-check toggle-true" id="%2$s-%4$s" onclick="setPrimary(\'%1$s\', \'%2$s\', \'%3$s\', \'%4$s\')"></i>', $this->table['ref'], $column['name'], $this->table['primary'], $record->{$this->table['primary']});
									} else {
										$tempResult = sprintf('<i class="fa-solid fa-circle-xmark toggle-false" id="%2$s-%4$s" onclick="setPrimary(\'%1$s\', \'%2$s\', \'%3$s\', \'%4$s\')"></i>', $this->table['ref'], $column['name'], $this->table['primary'], $record->{$this->table['primary']});
									}
									break;
							}
	
							$result .= sprintf('<td id="column-%s" style="width:%s;">%s</td>', $column['name'], $style, $tempResult);
						}
	
						if (count($this->table['buttons']) >= 1) {
							$result .= '
							<td class="tr-buttons">';
	
							foreach ($this->table['buttons'] as $i3 => $button) {
								$link = $record->buttonLinks[$i3];
								$icon = $button['icon'];
	
								$result .= sprintf('
								<a href="%s">
									<i class="%s">', $link, $icon);
	
									if ($button['label'] != null) {
										$result .= sprintf('
										<div class="button-label">
											<p>%s</p>
											<div></div>
										</div>', $button['label']);
									}
									
									$result .= '
									</i>
								</a>';
							}
	
							$result .= '
							</td>';
						}
	
						$result .= '
						</tr>';
					}
				} else {
					$result .= '
					<h3>No Records</h3>';
				}
				
			$result .= '
			</tbody>
		</table>';

		$script = '<script>';

		if ($return == false) {
			$script .= sprintf('
				setTimeout(function() {
					let width = document.querySelector("#table-%1$s .tr-buttons").offsetWidth;
					let rows = document.querySelectorAll("#table-%1$s tr");

					let input = width + "px";

					rows.forEach(row => {
						row.style.paddingRight = input;
					});
				}, 500);', $this->table['ref']
			);

		} else {
			$script .= sprintf('
				setTimeout(function() {
					let rows = document.querySelectorAll("#table-%1$s tr");

					rows.forEach(row => {
						row.style.paddingRight = "%2$s";
					});
				}, 500);', $this->table['ref'], (count($this->table['buttons']) * 40) . 'px'
			);
		}

		$script .= '</script>';

		if ($return == false) {
			$result = trim(preg_replace('/\s\s+/', '', $result . $script));
			echo $result;

		} else {
			$return = [];
			$return['content'] = trim(preg_replace('/\s\s+/', '', $result));
			$return['script'] = trim(preg_replace('/\s\s+/', '', $script));

			return $return;
		}
	}
}