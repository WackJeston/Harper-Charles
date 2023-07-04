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

	public function addButton(string $url, string $icon, string $label = null, string $warning = null) {
		$this->table['buttons'][] = [
			'icon' => $icon,
			'label' => $label,
		];

		if (!str_starts_with($url, '/')) {
			$url = '/' . $url;
		}

		if (str_contains(url()->current(), '/admin')) {
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

	public function display() {
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
	
							if ($column['type'] == 'currency') {
								$record->{$column['name']} = '£' . $record->{$column['name']};
							}
	
							$result .= sprintf('<td style="width:%s;">%s</td>', $style, $record->{$column['name']});
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
					<div class="empty-table">
						<h3>No Images</h3>
					</div>';
				}
				
			$result .= '
			</tbody>
		</table>';

		$result .= sprintf('
		<script>
			window.onload = addButtonsPadding();

			function addButtonsPadding() {
				setTimeout(function() {
					let width = document.querySelector("#table-%1$s .tr-buttons").offsetWidth;
					let rows = document.querySelectorAll("#table-%1$s tr");

					console.log(width);

					let input = width + "px";

					rows.forEach(row => {
						row.style.paddingRight = input;
					});
				}, 500);
			};
		</script>', $this->table['ref']);

		echo $result;
	}
}