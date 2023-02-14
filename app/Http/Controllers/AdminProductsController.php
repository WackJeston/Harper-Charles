<?php

namespace App\Http\Controllers;

use File;
use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\ProductImages;

class AdminProductsController extends Controller
{
  public function show(Products $products)
  {
    $sessionUser = auth()->user();

    $product = Products::all();

    return view('/admin/products', compact(
      'sessionUser',
      'product',
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
