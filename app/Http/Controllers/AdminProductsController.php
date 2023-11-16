<?php

namespace App\Http\Controllers;

use File;
use App\DataTable;
use App\DataForm;
use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\ProductImages;

class AdminProductsController extends Controller
{
  public function show(Products $products)
  {
    $sessionUser = auth()->user();

		$createForm = new DataForm(request(), '/productsCreate', 'Add');
		$createForm->addInput('text', 'title', 'Title', null, 100, 1, true);
		$createForm->addInput('text', 'subtitle', 'Subtitle', null, 255, 1);
		$createForm->addInput('textarea', 'description', 'Description', null, 5000, 1);
		$createForm->addInput('text', 'productnumber', 'Product Number', null, 100, 1);
		$createForm->addInput('number', 'price', 'Price (£)', null, null, null, true);
		$createForm = $createForm->render();

		$productsTable = new DataTable('products');
		$productsTable->setQuery('SELECT * FROM products');
		$productsTable->addColumn('id', '#');
		$productsTable->addColumn('title', 'Title', 3);
		$productsTable->addColumn('subtitle', 'Subtitle', 4, true);
		$productsTable->addColumn('productNumber', 'Product Number', 2);
		$productsTable->addLinkButton('product-profile/?', 'fa-solid fa-folder-open', 'Open Record');
		$productsTable = $productsTable->render();

    return view('/admin/products', compact(
      'sessionUser',
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
			'productnumber' => 'unique:products|max:100',
			'price' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
    ]);

    Products::create([
			'title' => $request->title,
			'subtitle' => $request->subtitle,
			'description' => $request->description,
			'productnumber' => $request->productnumber,
			'price' => $request->price,
    ]);

    return redirect('/admin/products')->with('message', 'Product created successfully.');
  }
}
