<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    //
    public function formSubmit(Request $request)
    {
    	return response()->json([$request->all()]);
    }
}
