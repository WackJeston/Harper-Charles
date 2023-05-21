<?php

namespace App\Http\Controllers;

use App\Models\Invoice;

class TestController extends Controller
{
  public function show()
  {
    $pdf = Invoice::createInvoice(16);

		return $pdf->stream();
  }
}
