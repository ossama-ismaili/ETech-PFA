<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function index()
    {
        $products=Product::where('quantity','>',0)->take(3)->get();
        $promotions=Promotion::orderBy('promo','desc')->take(3)->get();
        return view('pages.index')->with('products',$products)->with('promotions',$promotions);
    }

    public function about()
    {
        return view('pages.about');
    }
}
