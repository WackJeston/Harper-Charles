<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\DataTable;
use App\DataForm;
use App\Models\Settings;

class AdminSettingsController extends Controller
{
  public function show()
  {
		$settings = Settings::all();

		$form = new DataForm(request(), '/settingsUpdate');

		foreach ($settings as $i => $setting) {
			if ($setting->text != null) {
				$form->addInput('text', $setting->name, ucfirst($setting->name), $setting->text, 255, 1);
			} elseif ($setting->int != null) {
				$form->addInput('num', $setting->name, ucfirst($setting->name), $setting->int, null, null);
			} elseif ($setting->float != null) {
				$form->addInput('num', $setting->name, ucfirst($setting->name), $setting->float, null, null);
			} elseif (($setting->date) != null) {
				$form->addInput('date', $setting->name, ucfirst($setting->name), $setting->date, null, null);
			} elseif ($setting->datetime != null) {
				$form->addInput('datetime', $setting->name, ucfirst($setting->name), $setting->datetime, null, null);
			} else {
				$form->addInput('checkbox', $setting->name, ucfirst($setting->name), $setting->active, null, null);
			}
		}

		$form = $form->render();
		
    return view('admin/settings', compact(
			'form',
    ));
  }

	public function update(Request $request)
	{
		$settings = Settings::all();
		$updated = false;

		foreach ($settings as $i => $setting) {
			$column = null;
			
			if ($setting->text != null) {
				if ($setting->text != $request->all()[str_replace(' ', '_', $setting->name)]) {
					$column = 'text';
				}
			} elseif ($setting->int != null) {
				if ($setting->int != $request->all()[str_replace(' ', '_', $setting->name)]) {
					$column = 'int';
				}
			} elseif ($setting->float != null) {
				if ($setting->float != $request->all()[str_replace(' ', '_', $setting->name)]) {
					$column = 'float';
				}
			} elseif ($setting->date != null) {
				if ($setting->date != $request->all()[str_replace(' ', '_', $setting->name)]) {
					$column = 'date';
				}
			} elseif ($setting->datetime != null) {
				if (strtotime($setting->datetime) != strtotime($request->all()[str_replace(' ', '_', $setting->name)])) {
					$column = 'datetime';
				}
			} else {
				if (!isset($request->all()[str_replace(' ', '_', $setting->name)]) && $setting->active == 1) {
					Settings::where('id', $setting->id)->update([
						'active' => 0,
					]);

					$column = 'active';
				} elseif (isset($request->all()[str_replace(' ', '_', $setting->name)]) && $setting->active == 0) {
					Settings::where('id', $setting->id)->update([
						'active' => 1,
					]);

					$column = 'active';
				}
			}
		
			if ($column != null && $column != 'active') {
				Settings::where('id', $setting->id)->update([
					$column => $request->all()[str_replace(' ', '_', $setting->name)],
				]);

				$updated = true;
			}
		}

		if ($updated == false) {
			return redirect("/admin/settings")->withErrors(['error' => 'Noting to update.']);
		} else {
			return redirect("/admin/settings")->with('message', 'Settings updated successfully.');
		}
	}
}
