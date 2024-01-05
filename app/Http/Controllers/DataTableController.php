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
	
	public function moveSequence(int $id, string $direction, string $tableName, string $sequenceColumn) {
		$orderDirection = $direction == 'up' ? 'DESC' : 'ASC';
		$sequenceDirection = $direction == 'up' ? '<' : '>';
		$targetSequence = DB::SELECT(sprintf('SELECT sequence FROM %s WHERE id = %d LIMIT 1', $tableName, $id))[0]->sequence;

		if ($sequenceColumn != 'null') {
			$parentId = DB::SELECT(sprintf('SELECT %s FROM %s WHERE id = %d LIMIT 1', $sequenceColumn, $tableName, $id))[0]->{$sequenceColumn};
			$records = DB::SELECT(sprintf('SELECT id, sequence FROM %s WHERE %s = %d AND sequence %s= %d ORDER BY sequence %s LIMIT 2', $tableName, $sequenceColumn, $parentId, $sequenceDirection, $targetSequence, $orderDirection));

		} else {
			$records = DB::SELECT(sprintf('SELECT id, sequence FROM %s WHERE sequence %s= %d ORDER BY sequence %s LIMIT 2', $tableName, $sequenceDirection, $targetSequence, $orderDirection));
		}

		if (count($records) < 2) {
			return false;
		
		} else {
			DB::UPDATE(sprintf('UPDATE %s SET sequence = %d WHERE id = %d', $tableName, $records[0]->sequence, $records[1]->id));
			DB::UPDATE(sprintf('UPDATE %s SET sequence = %d WHERE id = %d', $tableName, $records[1]->sequence, $records[0]->id));

			return [
				$records[0]->id,
				$records[1]->id
			];
		}
	}

	//Header
	public function setOrderColumn(string $name, string $query) {
		$table = session()->get($query);
		$table['orderColumn'] = $name;
		session()->put($query, $table);

		return true;
	}

	public function setOrderDirection(string $direction, string $query) {
		$table = session()->get($query);
		$table['orderDirection'] = $direction;
		session()->put($query, $table);

		return true;
	}

	//Footer
	public function changeLimit(string $limit, string $query) {
		$table = session()->get($query);
		$table['limit'] = $limit;
		session()->put($query, $table);

		return true;
	}

	public function changePage(int $offset, string $query) {
		$table = session()->get($query);
		$table['offset'] = $offset;
		session()->put($query, $table);

		return true;
	}

	public function resetTableSequence(string $query) {
		$table = session()->get($query);
		$table['orderColumn'] = 'sequence';
		$table['orderDirection'] = 'ASC';
		session()->put($query, $table);

		return true;
	}
}
