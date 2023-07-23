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
			'tableName' => explode('_REF_', $ref)[0],
			'primary' => $primary,
			'count' => 0,
			'columns' => [],
			'records' => [],
			'buttons' => [],
		];
	}

	public function setQuery(string $query, array $params = []) {
		$query = str_replace('?', '%s', $query);
		$query = vsprintf($query, $params);

		$this->table['records'] = DB::select($query);

		$this->table['count'] = count($this->table['records']);
	}

	public function addColumn(string $name, string $title = null, int $width = 1, bool $hideMobile = false, string $type = 'default') {
		if ($title == null) {
			$title = $name;
		}

		$typeArray = explode(':', $type);

		$type = $typeArray[0];
		$parent = isset($typeArray[1]) ? $typeArray[1] : null;
		$parentId = isset($typeArray[2]) ? $typeArray[2] : null;

		$this->table['columns'][] = [
			'name' => $name,
			'title' => $title,
			'width' => $width,
			'maxWidth' => $width,
			'mobileMaxWidth' => $width,
			'hideMobile' => $hideMobile,
			'type' => $type,
			'parent' => $parent,
			'parentId' => $parentId,
		];
	}

	public function addLinkButton(string $url, string $icon, string $label = null) {
		$this->table['buttons'][] = [
			'type' => 'link',
			'icon' => $icon,
			'label' => $label,
		];

		if (!str_starts_with($url, '/')) {
			$url = '/' . $url;
		}

		if (str_contains(url()->current(), '/admin') && !preg_match('/[A-Z]/', $url)) {
			$url = '/admin' . $url;
		}

		foreach ($this->table['records'] as $i => $record) {
			$recordArray = (array) $record;
			$record->buttonRecords[] = str_replace('?', $recordArray[$this->table['primary']], $url);
		}
	}

	public function addJsButton(string $function, array $values, string $icon, string $label = null) {
		$this->table['buttons'][] = [
			'type' => 'js',
			'icon' => $icon,
			'label' => $label,
		];

		foreach ($this->table['records'] as $i => $record) {
			$finalValues = [];

			foreach ($values as $i2 => $value) {
				$value = explode(':', $value);

				$tempValue = '';
				
				switch ($value[0]) {
					case 'string':
						$tempValue = $value[1];
						break;
					
					case 'record':
						$tempValue = $record->{$value[1]};
						break;
					
					case 'url':
						$tempValue = str_replace('?', $record->{$this->table['primary']}, $value[1]);
						break;
				}
	
				$finalValues[] = "'$tempValue'";
			}

			$record->buttonRecords[] = sprintf('%s(%s);', $function, implode(', ', $finalValues));
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

	public function render() {
		self::calculate();

		$result = sprintf('
		<table class="web-box" id="table-%s">
			<thead>
				<tr>', $this->table['ref']);

				foreach ($this->table['columns'] as $i => $column) {
					$style = $column['name'] == 'id' ? '50px' : $column['maxWidth'] . '%';
					$mobileStyle = $column['name'] == 'id' ? '50px' : $column['mobileMaxWidth'] . '%';

					if ($column['hideMobile'] == true) {
						$result .= sprintf('<th class="hide-mobile-marker" style="width:%s;">%s</th>', $style, $column['title']);
					} else {
						$result .= sprintf('<th class="show-mobile-marker" data-width="%s" data-mobile-width="%s">%s</th>', $style, $mobileStyle, $column['title']);
					}
				}

				$result .= '
				</tr>
			</thead>';

			if (count($this->table['records']) > 0) {
				$result .= '
				<tbody>';

					foreach ($this->table['records'] as $i => $record) {
						$result .= '
						<tr>';
						
						foreach ($this->table['columns'] as $i2 => $column) {
							$style = $column['name'] == 'id' ? '50px' : $column['maxWidth'] . '%';
							$mobileStyle = $column['name'] == 'id' ? '50px' : $column['mobileMaxWidth'] . '%';

							$tempResult = $record->{$column['name']};
							
							// Column Type Switch
							switch ($column['type']) {
								case 'currency':
									$tempResult = '£' . $record->{$column['name']};
									break;

								case 'toggle':
									if ($record->{$column['name']} == 1) {
										$tempResult = sprintf('<i class="fa-solid fa-circle-check toggle-true" id="%3$s-%5$s" onclick="toggleButton(\'%1$s\', \'%2$s\', \'%3$s\', \'%4$s\', \'%5$s\')"></i>', 
											$this->table['tableName'], 
											$this->table['ref'], 
											$column['name'], 
											$this->table['primary'], 
											$record->{$this->table['primary']}
										);
									} else {
										$tempResult = sprintf('<i class="fa-solid fa-circle-xmark toggle-false" id="%3$s-%5$s" onclick="toggleButton(\'%1$s\', \'%2$s\', \'%3$s\', \'%4$s\', \'%5$s\')"></i>', 
											$this->table['tableName'], 
											$this->table['ref'], 
											$column['name'], 
											$this->table['primary'], 
											$record->{$this->table['primary']}
										);
									}
									break;
								case 'setPrimary':
									if ($record->{$column['name']} == 1) {
										$tempResult = sprintf('<i class="fa-solid fa-circle-check toggle-true" id="%3$s-%5$s" onclick="setPrimary(\'%1$s\', \'%2$s\', \'%3$s\', \'%4$s\', \'%5$s\', \'%6$s\', \'%7$s\')"></i>', 
											$this->table['tableName'], 
											$this->table['ref'], 
											$column['name'], 
											$this->table['primary'], 
											$record->{$this->table['primary']},
											$column['parent'],
											$column['parentId'],
										);
									} else {
										$tempResult = sprintf('<i class="fa-solid fa-circle-xmark toggle-false" id="%3$s-%5$s" onclick="setPrimary(\'%1$s\', \'%2$s\', \'%3$s\', \'%4$s\', \'%5$s\', \'%6$s\', \'%7$s\')"></i>', 
											$this->table['tableName'], 
											$this->table['ref'], 
											$column['name'], 
											$this->table['primary'], 
											$record->{$this->table['primary']},
											$column['parent'],
											$column['parentId'],
										);
									}
									break;
							}

							if ($column['hideMobile'] == true) {
								$result .= sprintf('<td id="column-%s" class="hide-mobile-marker" style="width:%s;">%s</tds>', $column['name'], $style, $tempResult);
							} else {
								$result .= sprintf('<td id="column-%s" class="show-mobile-marker" data-width="%s" data-mobile-width="%s">%s</tds>', $column['name'], $style, $mobileStyle, $tempResult);
							}
						}
	
						if (count($this->table['buttons']) >= 1) {
							$result .= '
							<td class="tr-buttons">';
	
							foreach ($this->table['buttons'] as $i3 => $button) {
								if ($button['type'] == 'link') {
									$result .= sprintf('
									<a href="%s">
										<i class="%s tr-button">', $record->buttonRecords[$i3], $button['icon']);
		
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

								} elseif ($button['type'] == 'js') {
									$result .= sprintf('
									<i onclick="%s" class="%s tr-button">', $record->buttonRecords[$i3], $button['icon']);
		
									if ($button['label'] != null) {
										$result .= sprintf('
										<div class="button-label">
											<p>%s</p>
											<div></div>
										</div>', $button['label']);
									}
									
									$result .= '
									</i>';
								}
							}
	
							$result .= '
							</td>';
						}
	
						$result .= '
						</tr>';
					}

				$result .= '
				</tbody>';

			} else {
				$result .= '
				<tr class="empty-table">
					<td><h3>No Records</h3></td>
				</tr>';
			}
				
		$result .= '
		</table>';

		$return = [
			'html' => trim(preg_replace('/\s\s+/', '', $result)),
			'count' => $this->table['count'],
		];

		return $return;
	}
}