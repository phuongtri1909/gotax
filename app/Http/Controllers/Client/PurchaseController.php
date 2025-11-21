<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;    

class PurchaseController extends Controller
{
    public function cassoCallback(Request $request)
    {
        return response()->json(['success' => true], 200);
    }
}
