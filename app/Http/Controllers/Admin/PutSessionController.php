<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;

class PutSessionController extends AppBaseController
{
    public function putSession(Request $request){

        session()->put('initial_date', $request->initial_date);
        session()->put('final_date', $request->final_date);
        
        return response()->json(['success' => true]);
    }
}
