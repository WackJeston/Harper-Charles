<?php

namespace App\Http\Controllers;

use File;
use App\DataTable;
use App\DataForm;
use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\ProductImages;

class AdminProductsController extends AdminController
{
  public function show(Products $products)
  {
		if (session()->has('admin-products-search')) {
			$formValue = session()->get('admin-products-search')[0];
			$query = session()->get('admin-products-search')[1];
		
		} else {
			$formValue = [
				'search' => null,
				'active' => 'all',
			];
			$query = 'SELECT * FROM products';
		}

		$searchForm = new DataForm(request(), '/productsSearch', 'Search');
		$searchForm->addInput('text', 'search', 'Search', $formValue['search'], 255, 0);
		$searchForm->addInput('select', 'active', 'Active', $formValue['active'], null, null, false, null, [], false);
		$searchForm->populateOptions('active', [
			[
				'value' => 'all',
				'label' => '',
			],
			[
				'value' => 1,
				'label' => 'Yes',
			],
			[
				'value' => 0,
				'label' => 'No',
			],
		], false);
		$searchForm = $searchForm->render();

    $createForm = new DataForm(request(), '/productsCreate', 'Add');
		$createForm->addInput('text', 'title', 'Title', null, 100, 1, true);
		$createForm->addInput('text', 'subtitle', 'Subtitle', null, 255, 1);
		$createForm->addInput('textarea', 'description', 'Description', null, 5000, 1);
		$createForm->addInput('text', 'productnumber', 'Product Number', null, 100, 1);
		$createForm->addInput('number', 'price', 'Price (£)', null, null, null, true);
		$createForm = $createForm->render();

		$productsTable = new DataTable('products');
		$productsTable->setQuery($query);
		$productsTable->addColumn('id', '#');
		$productsTable->addColumn('title', 'Title', 3);
		$productsTable->addColumn('subtitle', 'Subtitle', 4, true);
		$productsTable->addColumn('productNumber', 'Product Number', 2);
		$productsTable->addColumn('active', 'Active', 1, false, 'toggle');
		$productsTable->addLinkButton('product-profile/?', 'fa-solid fa-folder-open', 'Open Record');
		$productsTable = $productsTable->render();

    return view('/admin/products', compact(
			'searchForm',
			'createForm',
			'productsTable'
    ));
  }

  public function create(Request $request)
  {
    $request->validate([
			'title' => 'required|max:100',
			'subtitle' => 'max:255',
			'description' => 'max:5000',
			'productnumber' => 'nullable|unique:products|max:100',
			'price' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
    ]);

    Products::create([
			'title' => $request->title,
			'subtitle' => $request->subtitle,
			'description' => $request->description,
			'productnumber' => $request->productnumber,
			'price' => $request->price,
    ]);

    return redirect('/admin/products')->with('message', 'Product created.');
  }

	public function search(Request $request)
	{
		$request->validate([
			'search' => 'max:255',
		]);

		if (empty($request->search) && $request->active == 'all') {
			$query = 'SELECT * FROM products';
		
		} else {
			if (empty($request->search)) {
				$search = '';

			} else {
				$search = sprintf('id LIKE "%%%1$s%%"
					OR title LIKE "%%%1$s%%"
					OR productNumber LIKE "%%%1$s%%"
					OR price LIKE "%%%1$s%%"',
					$request->search
				);
			}

			if (!empty($request->search) && $request->active == 'all') {
				$query = sprintf('SELECT
					* 
					FROM products
					WHERE %s',
					$search
				);

			} elseif (empty($request->search) && in_array($request->active, [0, 1])) {
				$query = sprintf('SELECT
					* 
					FROM products
					WHERE active = %s',
					$request->active
				);
			
			} else {
				$query = sprintf('SELECT
					* 
					FROM products
					WHERE active = %s
					AND (%s)',
					$request->active,
					$search
				);
			}

			$explode = str_split($query);

			foreach ($explode as $i => $character) {
				if ($character == '%') {
					$explode[$i] = '%%';
				}
			}

			$query = implode($explode);
		}

		$values = [
			'search' => $request->search,
			'active' => $request->active,
		];
			
		session()->put('admin-products-search', [$values, $query]);

		return redirect('/admin/products');
	}
}
