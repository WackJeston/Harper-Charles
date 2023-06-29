<?php

namespace App;

Use DB;

class DataTable
{
	protected $table;

  public function __construct()
	{
		$this->table = [
			'primary' => 'id',
			'columns' => [],
			'records' => [],
			'buttons' => [],
		];
	}

	public function setQuery(string $query) {
		$this->table['records'] = DB::select($query);
	}

	public function setPrimary(string $column) {
		$this->table['primary'] = $column;
	}

	public function addColumn(string $name, string $title = null, int $width = 1, bool $hideMobile = false) {
		if ($title == null) {
			$title = $name;
		}

		$this->table['columns'][] = [
			'name' => $name,
			'title' => $title,
			'hideMobile' => $hideMobile,
			'width' => $width,
			'maxWidth' => $width,
		];
	}

	public function addButton(string $url, string $icon, string $label = null) {
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

	public function output(): array {
		$columnWidthCount = 0;
		
		foreach ($this->table['columns'] as $i => $column) {
			if ($column['name'] != 'id') {
				$columnWidthCount += $column['width'];
			}
		}

		foreach ($this->table['columns'] as $i => $column) {
			if ($column['name'] != 'id') {
				$this->table['columns'][$i]['maxWidth'] = round((100 / $columnWidthCount) * $column['width'], 2);
			}
		}

		return $this->table;
	}

	public function display() {
		$result = '
		<table class="web-box">
			<thead>
				<tr>';

				foreach ($this->table['columns'] as $i => $column) {
					$style = $column['name'] == 'id' ? '50px' : $column['maxWidth'] . '%';

					$result .= sprintf('<th style="%s">%s</th>', $style, $column['title']);
				}

				$result .= '
				</tr>
			</thead>

			<tbody>';
					
				foreach ($this->table['records'] as $i => $record) {
					$result .= '
					<tr>';
					
					foreach ($this->table['columns'] as $i2 => $column) {
						$style = $column['name'] == 'id' ? '50px' : $column['maxWidth'] . '%';

						$result .= sprintf('<td style="%s">%s</td>', $style, $column['title']);
						
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
					}

					$result .='
					</tr>';
				}
				
			$result .='
			</tbody>
		</table>

		<script>
			window.onload = addButtonsPadding();

			function addButtonsPadding() {
				let width = document.querySelector(".tr-buttons").offsetWidth;
				let rows = document.querySelectorAll("tr");

				let input = width + "px";

				setTimeout(
					rows.forEach(row => {
						row.style.paddingRight = input;
					}),
					500
				);
			};
		</script>
		';

		echo $result;
	}
}