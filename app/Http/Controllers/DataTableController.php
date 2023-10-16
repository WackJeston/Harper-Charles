<?php
namespace App\Http\Controllers;

use DB;
use Illuminate\Support\Facades\Session;

class DataTableController extends Controller
{
  public function toggleButton(string $table, string $column, string $primaryColumn, $primaryValue):bool {
		$record = DB::SELECT('SELECT * FROM ' . $table . ' WHERE ' . $primaryColumn . ' = ?', [$primaryValue])[0];

		$value = $record->{$column};

		if ($value == 1) {
			$value = 0;
		} else {
			$value = 1;
		}

		DB::table($table)->where($primaryColumn, $primaryValue)->update([$column => $value]);

		return $value;
	}

	public function setPrimary(string $table, string $column, string $primaryColumn, $primaryValue, string $parent = null, $parentId = null):bool {
		if ($parent != null && $parentId != null) {
			DB::table($table)->where($column, 1)->where($parent, $parentId)->update([$column => 0]);
		} else {
			DB::table($table)->where($column, 1)->update([$column => 0]);
		}
		
		DB::table($table)->where($primaryColumn, $primaryValue)->update([$column => 1]);

		return true;
	}

	public function selectDropdown(string $table, string $column, string $primaryColumn, $primaryValue, $value = null) {
		if ($value == 'null') {
			$value = null;
		}

		DB::table($table)->where($primaryColumn, $primaryValue)->update([$column => $value]);

		return true;
	}

	//Header
	public function setOrderColumn(string $name, string $query) {
		$table = session()->get($query);
		$table['orderColumn'] = $name;
		session()->put($query, $table);
		session()->save();

		return true;
	}

	public function setOrderDirection(string $direction, string $query) {
		$table = session()->get($query);
		$table['orderDirection'] = $direction;
		session()->put($query, $table);
		session()->save();

		return true;
	}

	//Footer
	public function changeLimit(string $limit, string $query) {
		$table = session()->get($query);
		$table['limit'] = $limit;
		session()->put($query, $table);
		session()->save();

		return true;
	}

	public function changePage(int $offset, string $query) {
		$table = session()->get($query);
		$table['offset'] = $offset;
		session()->put($query, $table);
		session()->save();

		return true;
	}
}
