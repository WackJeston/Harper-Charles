<?php

namespace App;

Use DB;

class DataTable
{
	protected $table;

  public function __construct()
	{
		$this->table = [
			'columns' => [],
			'records' => [],
		];
	}

	public function output(): array {
		return $this->table;
	}

	public function setQuery(string $query) {
		$this->table['records'] = DB::select($query);
	}

	public function addColumn(string $name, string $title = null) {
		if ($title == null) {
			$title = $name;
		}

		$this->table['columns'][] = [
			'name' => $name,
			'title' => $title,
		];
	}
}