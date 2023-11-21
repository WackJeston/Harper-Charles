<?php

namespace App\Http\Controllers;

use App\DataTable;


class AdminBannersController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();
		
		$bannersTable = new DataTable('banners');
		$bannersTable->setQuery('SELECT
			b.*,
			COUNT(b2.id) AS `children`
			FROM banners AS b
			LEFT JOIN banners AS b2 ON b2.parentId=b.id
			WHERE b.parentId IS NULL'
		);
		$bannersTable->addColumn('id', '#');
		$bannersTable->addColumn('title', 'Location');
		$bannersTable->addColumn('children', 'Slides');
		$bannersTable->addLinkButton('order-profile/?', 'fa-solid fa-folder-open', 'Open Record');
		$bannersTable = $bannersTable->render();

    return view('admin/banners', compact(
      'sessionUser',
			'bannersTable',
    ));
  }
}
