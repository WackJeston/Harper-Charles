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

	public function addColumn(string $name, string $title = null, bool $hideMobile = false) {
		if ($title == null) {
			$title = $name;
		}

		$this->table['columns'][] = [
			'name' => $name,
			'title' => $title,
			'hideMobile' => $hideMobile,
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
		return $this->table;
	}
}