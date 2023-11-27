<?php

namespace App\Http\Controllers;

use App\DataTable;


class AdminBannersController extends Controller
{
  public function show()
  {
    $bannersTable = new DataTable('banners');
		$bannersTable->setQuery('SELECT
			b.id,
			CONCAT(b.page, " (", b.position, ")") AS `location`,
			b.active,
			COUNT(b2.id) AS `children`
			FROM banners AS b
			LEFT JOIN banners AS b2 ON b2.parentId=b.id
			WHERE b.parentId IS NULL
			GROUP BY b.id'
		);
		$bannersTable->addColumn('id', '#');
		$bannersTable->addColumn('location', 'Location');
		$bannersTable->addColumn('children', 'Slides');
		$bannersTable->addColumn('active', 'Active', 1, false, 'toggle');
		$bannersTable->addLinkButton('banner-profile/?', 'fa-solid fa-folder-open', 'Open Record');
		$bannersTable = $bannersTable->render();

    return view('admin/banners', compact(
			'bannersTable',
    ));
  }
}
