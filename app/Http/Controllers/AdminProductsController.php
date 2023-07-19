<?php

namespace App\Http\Controllers;

use File;
use App\dataTable;
use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\ProductImages;

class AdminProductsController extends Controller
{
  public function show(Products $products)
  {
    $sessionUser = auth()->user();

		$productsTable = new DataTable();
		$productsTable->setQuery('SELECT * FROM products');

		$productsTable->addColumn('id', '#');
		$productsTable->addColumn('title', 'Title');
		$productsTable->addColumn('subtitle', 'Subtitle', 2);
		$productsTable->addColumn('productNumber', 'Product Number');

		$productsTable->addLinkButton('product-profile/?', 'fa-solid fa-folder-open', 'Open Record');


    return view('/admin/products', compact(
      'sessionUser',
			'productsTable'
    ));
  }

  public function create(Request $request)
  {
    $request->validate([
        'title' => 'required|max:100',
        'subtitle' => 'max:100',
        'description' => 'max:1000',
        'productnumber' => 'required|unique:products|max:100',
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
